<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatMessageReaction;
use App\Models\ChatStory;
use App\Models\User;
use App\Models\UserFollow;
use App\Models\UserPost;
use App\Models\PostComment;
use App\Models\SandikaUserRank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Render the main Chat Portal view.
     */
    public function index()
    {
        $authenticated = Auth::check();
        $user = Auth::user();

        return view('pages.chat.chat', compact('authenticated', 'user'));
    }

    /**
     * Fetch all chat messages or messages for specific recipient.
     */
    public function fetchMessages(Request $request)
    {
        $recipientId = $request->query('recipient_id');
        $myId = Auth::id();

        $query = ChatMessage::where(function ($q) {
            $q->whereNull('scheduled_at')
              ->orWhere('scheduled_at', '<=', now());
        });

        if ($recipientId && $myId) {
            $query->where(function ($q) use ($myId, $recipientId) {
                $q->where(function ($sub) use ($myId, $recipientId) {
                    $sub->where('user_id', $myId)->where('recipient_id', $recipientId);
                })->orWhere(function ($sub) use ($myId, $recipientId) {
                    $sub->where('user_id', $recipientId)->where('recipient_id', $myId);
                })->orWhereNull('recipient_id');
            });
        }

        $messages = $query->with(['user:id,name,first_name,last_name,username,avatar', 'reactions.user:id,name,first_name,last_name,username'])
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function ($m) use ($myId) {
            $reactionsGrouped = $m->reactions->groupBy('emoji')->map(function ($group) {
                return [
                    'emoji' => $group->first()->emoji,
                    'count' => $group->count(),
                    'users' => $group->pluck('user.name')->toArray(),
                    'reacted_by_me' => Auth::check() ? $group->contains('user_id', Auth::id()) : false,
                ];
            })->values();

            return [
                'id' => $m->id,
                'user_id' => $m->user_id,
                'recipient_id' => $m->recipient_id,
                'sender_name' => $m->sender_name,
                'username' => $m->username,
                'message' => $m->message,
                'type' => $m->type,
                'file_path' => $m->file_path ? asset($m->file_path) : null,
                'avatar_url' => $m->user && $m->user->avatar ? asset($m->user->avatar) : asset('images/profile.jpg'),
                'created_at' => $m->created_at->format('H:i'),
                'created_at_human' => $m->created_at->diffForHumans(),
                'reactions' => $reactionsGrouped,
                'is_me' => $myId ? ($m->user_id === $myId) : false,
            ];
        });

        // Unread message counts per user
        $unreadCounts = [];
        if ($myId) {
            try {
                $counts = ChatMessage::select('user_id', DB::raw('count(*) as total'))
                    ->where('recipient_id', $myId)
                    ->whereNull('delivered_at')
                    ->groupBy('user_id')
                    ->pluck('total', 'user_id')
                    ->toArray();
                $unreadCounts = $counts;
            } catch (\Exception $e) {
                $unreadCounts = [];
            }
        }

        return response()->json(['status' => 'success', 'messages' => $messages, 'unread_counts' => $unreadCounts]);
    }

    /**
     * Fetch list of registered chat users.
     */
    public function fetchUsers()
    {
        $users = User::select('id', 'name', 'first_name', 'last_name', 'username', 'email', 'avatar', 'bio', 'social_links')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => trim($u->first_name . ' ' . $u->last_name) ?: $u->name,
                    'username' => $u->username ?? Str::slug($u->name),
                    'email' => $u->email,
                    'avatar_url' => $u->avatar ? asset($u->avatar) : asset('images/profile.jpg'),
                    'bio' => $u->bio ?? 'Member',
                    'social_links' => $u->social_links ?? [],
                    'is_me' => $u->id === Auth::id(),
                ];
            });

        return response()->json(['status' => 'success', 'users' => $users]);
    }

    /**
     * Send a standard text / gif / emoji / file message.
     */
    public function sendMessage(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized', 'message' => 'Please log in to chat.'], 401);
        }

        $request->validate([
            'message' => 'nullable|string|max:5000',
            'type' => 'required|in:text,image,gif,file,voice,video',
            'recipient_id' => 'nullable|exists:users,id',
            'scheduled_at' => 'nullable|date',
            'file_url' => 'nullable|string',
        ]);

        $user = Auth::user();
        $senderName = trim(($user->first_name . ' ' . $user->last_name)) ?: $user->name;
        $isScheduled = $request->filled('scheduled_at') && strtotime($request->input('scheduled_at')) > time();

        $msgData = [
            'user_id' => $user->id,
            'recipient_id' => $request->input('recipient_id'),
            'sender_name' => $senderName,
            'username' => $user->username ?? Str::slug($user->name),
            'message' => $request->input('message'),
            'type' => $request->input('type'),
            'file_path' => $request->input('file_url'),
            'scheduled_at' => $isScheduled ? $request->input('scheduled_at') : null,
            'delivered_at' => $isScheduled ? null : now(),
        ];

        $msg = ChatMessage::create($msgData);

        return response()->json([
            'status' => 'success',
            'message' => $isScheduled ? 'Message scheduled!' : 'Message sent!',
            'data' => $msg
        ]);
    }

    /**
     * Process file / photo / video / audio upload for chat (Up to 4GB).
     */
    public function uploadFile(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate([
            'file' => 'required|file|max:4194304', // 4GB max
        ]);

        $file = $request->file('file');
        $uploadDir = public_path('uploads/chat_files');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = time() . '_' . Str::random(10) . '.' . $extension;
        $file->move($uploadDir, $filename);

        $filePath = 'uploads/chat_files/' . $filename;

        $type = 'file';
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $type = 'image';
        } elseif (in_array($extension, ['mp3', 'wav', 'ogg', 'm4a', 'webm'])) {
            $type = 'voice';
        } elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv'])) {
            $type = 'video';
        }

        return response()->json([
            'status' => 'success',
            'file_url' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'type' => $type,
        ]);
    }

    /**
     * Toggle reaction on a message.
     */
    public function toggleReaction(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate([
            'chat_message_id' => 'required|exists:chat_messages,id',
            'emoji' => 'required|string',
        ]);

        $messageId = $request->input('chat_message_id');
        $emoji = $request->input('emoji');
        $userId = Auth::id();

        $existing = ChatMessageReaction::where('chat_message_id', $messageId)
            ->where('user_id', $userId)
            ->where('emoji', $emoji)
            ->first();

        if ($existing) {
            $existing->delete();
            $action = 'removed';
        } else {
            ChatMessageReaction::create([
                'chat_message_id' => $messageId,
                'user_id' => $userId,
                'emoji' => $emoji,
            ]);
            $action = 'added';
        }

        return response()->json(['status' => 'success', 'action' => $action]);
    }

    /**
     * Dedicated Standalone User Profile Page View.
     */
    public function myProfilePage()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view your profile.');
        }

        $user = Auth::user();
        $posts = UserPost::where('user_id', $user->id)
            ->published()
            ->with(['user', 'comments.user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($p) use ($user) {
                return $this->formatPostArray($p, $user->id);
            });

        $articles = \App\Models\BlogPost::where('author_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $followersCount = UserFollow::where('following_id', $user->id)->count();
        $followingCount = UserFollow::where('follower_id', $user->id)->count();

        return view('pages.user_profile', compact('user', 'posts', 'articles', 'followersCount', 'followingCount'));
    }

    /**
     * Update user profile settings (name, username, bio, avatars gallery, headers gallery, theme).
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $user = User::find(Auth::id());

        $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'username' => 'nullable|string|max:100|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:2000',
            'social_linkedin' => 'nullable|string|max:255',
            'social_github' => 'nullable|string|max:255',
            'social_twitter' => 'nullable|string|max:255',
            'social_website' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:10240',
            'header_banner' => 'nullable|image|max:10240',
            'profile_theme_color' => 'nullable|string|max:50',
        ]);

        $avatarsGallery = is_array($user->avatars_gallery) ? $user->avatars_gallery : [];
        $headersGallery = is_array($user->headers_gallery) ? $user->headers_gallery : [];

        // Handle Avatar File Upload
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $uploadDir = public_path('uploads/avatars');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'avatar_' . $user->id . '_' . time() . '_' . rand(1000, 9999) . '.' . $avatarFile->getClientOriginalExtension();
            $avatarFile->move($uploadDir, $filename);
            $path = 'uploads/avatars/' . $filename;

            $user->avatar = $path;
            if (!in_array($path, $avatarsGallery)) {
                $avatarsGallery[] = $path;
            }
        }

        // Handle Header Banner File Upload
        if ($request->hasFile('header_banner')) {
            $headerFile = $request->file('header_banner');
            $uploadDir = public_path('uploads/headers');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'header_' . $user->id . '_' . time() . '_' . rand(1000, 9999) . '.' . $headerFile->getClientOriginalExtension();
            $headerFile->move($uploadDir, $filename);
            $path = 'uploads/headers/' . $filename;

            $user->header_banner = $path;
            if (!in_array($path, $headersGallery)) {
                $headersGallery[] = $path;
            }
        }

        // Handle Batch Multiple Avatars Upload
        if ($request->hasFile('multiple_avatars')) {
            $uploadDir = public_path('uploads/avatars');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            foreach ($request->file('multiple_avatars') as $file) {
                $filename = 'avatar_' . $user->id . '_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadDir, $filename);
                $path = 'uploads/avatars/' . $filename;
                if (!in_array($path, $avatarsGallery)) {
                    $avatarsGallery[] = $path;
                }
            }
            if (empty($user->avatar) && count($avatarsGallery) > 0) {
                $user->avatar = $avatarsGallery[0];
            }
        }

        // Handle Batch Multiple Headers Upload
        if ($request->hasFile('multiple_headers')) {
            $uploadDir = public_path('uploads/headers');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            foreach ($request->file('multiple_headers') as $file) {
                $filename = 'header_' . $user->id . '_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadDir, $filename);
                $path = 'uploads/headers/' . $filename;
                if (!in_array($path, $headersGallery)) {
                    $headersGallery[] = $path;
                }
            }
            if (empty($user->header_banner) && count($headersGallery) > 0) {
                $user->header_banner = $headersGallery[0];
            }
        }

        $user->avatars_gallery = array_values(array_unique($avatarsGallery));
        $user->headers_gallery = array_values(array_unique($headersGallery));

        if ($request->has('first_name')) $user->first_name = $request->input('first_name');
        if ($request->has('last_name')) $user->last_name = $request->input('last_name');
        if ($request->filled('username')) $user->username = $request->input('username');
        if ($request->has('bio')) $user->bio = $request->input('bio');
        if ($request->filled('profile_theme_color')) $user->profile_theme_color = $request->input('profile_theme_color');

        $formatUrl = function ($url) {
            if (!$url) return null;
            $url = trim($url);
            if ($url === '') return null;
            if (!preg_match('~^https?://~i', $url)) {
                return 'https://' . $url;
            }
            return $url;
        };

        $user->social_links = [
            'linkedin' => $formatUrl($request->input('social_linkedin')),
            'github' => $formatUrl($request->input('social_github')),
            'twitter' => $formatUrl($request->input('social_twitter')),
            'website' => $formatUrl($request->input('social_website')),
        ];

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile settings updated successfully!',
            'user' => [
                'name' => trim($user->first_name . ' ' . $user->last_name) ?: $user->name,
                'username' => $user->username,
                'avatar_url' => asset($user->avatar ?: 'images/profile.jpg'),
                'header_url' => $user->header_banner ? asset($user->header_banner) : null,
                'bio' => $user->bio,
                'social_links' => $user->social_links,
            ]
        ]);
    }

    /**
     * Switch active avatar from uploaded avatars gallery.
     */
    public function selectAvatar(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate(['avatar_path' => 'required|string']);
        $user = User::find(Auth::id());
        $path = $request->input('avatar_path');

        $gallery = is_array($user->avatars_gallery) ? $user->avatars_gallery : [];
        if (in_array($path, $gallery)) {
            $user->avatar = $path;
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Active profile avatar updated!']);
        }

        return response()->json(['status' => 'error', 'message' => 'Avatar image not found in your gallery.'], 404);
    }

    /**
     * Switch active header banner from uploaded headers gallery.
     */
    public function selectHeader(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate(['header_path' => 'required|string']);
        $user = User::find(Auth::id());
        $path = $request->input('header_path');

        $gallery = is_array($user->headers_gallery) ? $user->headers_gallery : [];
        if (in_array($path, $gallery)) {
            $user->header_banner = $path;
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Active profile header banner updated!']);
        }

        return response()->json(['status' => 'error', 'message' => 'Header image not found in your gallery.'], 404);
    }

    /**
     * Delete an avatar image from gallery.
     */
    public function deleteAvatarFromGallery(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate(['avatar_path' => 'required|string']);
        $user = User::find(Auth::id());
        $path = $request->input('avatar_path');

        $gallery = is_array($user->avatars_gallery) ? $user->avatars_gallery : [];
        if (($key = array_search($path, $gallery)) !== false) {
            unset($gallery[$key]);
            $user->avatars_gallery = array_values($gallery);

            if ($user->avatar === $path) {
                $user->avatar = count($user->avatars_gallery) > 0 ? $user->avatars_gallery[0] : null;
            }
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Avatar removed from gallery.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Image not found.'], 404);
    }

    /**
     * Delete a header banner image from gallery.
     */
    public function deleteHeaderFromGallery(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate(['header_path' => 'required|string']);
        $user = User::find(Auth::id());
        $path = $request->input('header_path');

        $gallery = is_array($user->headers_gallery) ? $user->headers_gallery : [];
        if (($key = array_search($path, $gallery)) !== false) {
            unset($gallery[$key]);
            $user->headers_gallery = array_values($gallery);

            if ($user->header_banner === $path) {
                $user->header_banner = count($user->headers_gallery) > 0 ? $user->headers_gallery[0] : null;
            }
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Header banner removed from gallery.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Image not found.'], 404);
    }

    /**
     * Fetch active 24-hour stories grouped BY USER (Instagram-style story tray).
     */
    public function fetchStories()
    {
        $rawStories = ChatStory::active()
            ->with('user:id,name,first_name,last_name,username,avatar,account_privacy,story_privacy')
            ->orderBy('created_at', 'asc')
            ->get();

        $currentUserId = Auth::id();

        // Group stories by User ID so each user appears EXACTLY ONCE in story tray
        $grouped = [];
        foreach ($rawStories as $s) {
            if (!$s->user) continue;

            // Privacy check: if story is private to followers only or private to self
            if ($s->privacy === 'private' && $s->user_id !== $currentUserId) {
                continue;
            }
            if ($s->privacy === 'followers' && $s->user_id !== $currentUserId) {
                $isFollower = $currentUserId ? UserFollow::where('follower_id', $currentUserId)->where('following_id', $s->user_id)->exists() : false;
                if (!$isFollower) continue;
            }

            $userId = $s->user_id;
            if (!isset($grouped[$userId])) {
                $grouped[$userId] = [
                    'user_id' => $userId,
                    'user_name' => trim(($s->user->first_name . ' ' . $s->user->last_name)) ?: $s->user->name,
                    'username' => $s->user->username ?? Str::slug($s->user->name),
                    'avatar_url' => $s->user->avatar ? asset($s->user->avatar) : asset('images/profile.jpg'),
                    'stories' => [],
                ];
            }

            $grouped[$userId]['stories'][] = [
                'id' => $s->id,
                'user_id' => $s->user_id,
                'content' => $s->content,
                'media_url' => $s->media_url ? asset($s->media_url) : null,
                'story_type' => $s->story_type ?? 'standard',
                'countdown_target_at' => $s->countdown_target_at ? $s->countdown_target_at->toIso8601String() : null,
                'poll_options' => $s->poll_options ? json_decode($s->poll_options, true) : null,
                'mentions' => $s->mentions ? json_decode($s->mentions, true) : null,
                'sticker_data' => $s->sticker_data ? json_decode($s->sticker_data, true) : null,
                'created_at' => $s->created_at->diffForHumans(),
                'expires_at' => $s->expires_at->toIso8601String(),
            ];
        }

        return response()->json([
            'status' => 'success',
            'story_users' => array_values($grouped)
        ]);
    }

    /**
     * Create a new 24-hour status story with Countdown & Instagram Tools.
     */
    public function createStory(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate([
            'content' => 'nullable|string|max:1000',
            'media' => 'nullable|file|max:512000',
            'story_type' => 'nullable|string|in:standard,poll,question,countdown',
            'countdown_target_at' => 'nullable|date',
            'privacy' => 'nullable|string|in:public,followers,private',
            'poll_option_a' => 'nullable|string|max:100',
            'poll_option_b' => 'nullable|string|max:100',
            'mentions' => 'nullable|string|max:255',
            'sticker_question' => 'nullable|string|max:255',
        ]);

        $mediaUrl = null;
        if ($request->hasFile('media')) {
            $mediaFile = $request->file('media');
            $uploadDir = public_path('uploads/stories');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'story_' . Auth::id() . '_' . time() . '.' . $mediaFile->getClientOriginalExtension();
            $mediaFile->move($uploadDir, $filename);
            $mediaUrl = 'uploads/stories/' . $filename;
        }

        $storyType = $request->input('story_type', 'standard');
        $countdownTarget = $request->filled('countdown_target_at') ? $request->input('countdown_target_at') : null;
        $privacy = $request->input('privacy', 'public');

        $pollOptions = null;
        if ($storyType === 'poll' && $request->filled('poll_option_a') && $request->filled('poll_option_b')) {
            $pollOptions = json_encode([
                'option_a' => $request->input('poll_option_a'),
                'option_b' => $request->input('poll_option_b'),
                'votes_a' => 0,
                'votes_b' => 0,
                'voters' => [],
            ]);
        }

        $mentions = null;
        if ($request->filled('mentions')) {
            $mentions = json_encode(array_map('trim', explode(',', $request->input('mentions'))));
        }

        $stickerData = null;
        if ($request->filled('sticker_question')) {
            $stickerData = json_encode(['question' => $request->input('sticker_question')]);
        }

        $story = ChatStory::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'media_url' => $mediaUrl,
            'story_type' => $storyType,
            'countdown_target_at' => $countdownTarget,
            'privacy' => $privacy,
            'poll_options' => $pollOptions,
            'mentions' => $mentions,
            'sticker_data' => $stickerData,
            'expires_at' => now()->addHours(24),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Instagram Story posted successfully!', 'story' => $story]);
    }

    /**
     * Vote on an Instagram story poll.
     */
    public function voteStoryPoll(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate([
            'story_id' => 'required|exists:chat_stories,id',
            'option' => 'required|in:a,b',
        ]);

        $story = ChatStory::findOrFail($request->input('story_id'));
        $pollData = json_decode($story->poll_options, true);

        if (!$pollData) {
            return response()->json(['status' => 'error', 'message' => 'No active poll on this story.'], 400);
        }

        $voters = $pollData['voters'] ?? [];
        $userId = Auth::id();

        if (in_array($userId, $voters)) {
            return response()->json(['status' => 'error', 'message' => 'You have already voted on this poll.'], 400);
        }

        if ($request->input('option') === 'a') {
            $pollData['votes_a']++;
        } else {
            $pollData['votes_b']++;
        }
        $pollData['voters'][] = $userId;

        $story->poll_options = json_encode($pollData);
        $story->save();

        return response()->json(['status' => 'success', 'poll_options' => $pollData]);
    }

    /**
     * Toggle Follow / Unfollow user.
     */
    public function toggleFollow($id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $currentUserId = Auth::id();
        if ($currentUserId == $id) {
            return response()->json(['status' => 'error', 'message' => 'You cannot follow yourself.'], 400);
        }

        $follow = UserFollow::where('follower_id', $currentUserId)
            ->where('following_id', $id)
            ->first();

        if ($follow) {
            $follow->delete();
            $action = 'unfollowed';
        } else {
            UserFollow::create([
                'follower_id' => $currentUserId,
                'following_id' => $id,
            ]);
            $action = 'followed';
        }

        $followersCount = UserFollow::where('following_id', $id)->count();

        return response()->json([
            'status' => 'success',
            'action' => $action,
            'followers_count' => $followersCount
        ]);
    }

    /**
     * Fetch user stats (posts, followers, following, privacy).
     */
    public function getUserStats($id)
    {
        $user = User::findOrFail($id);
        $postsCount = \App\Models\BlogPost::where('author_id', $id)->count();
        $followersCount = UserFollow::where('following_id', $id)->count();
        $followingCount = UserFollow::where('follower_id', $id)->count();

        $isFollowing = Auth::check() ? Auth::user()->isFollowing($id) : false;

        return response()->json([
            'status' => 'success',
            'stats' => [
                'posts' => $postsCount,
                'followers' => $followersCount,
                'following' => $followingCount,
                'is_following' => $isFollowing,
                'account_privacy' => $user->account_privacy ?? 'public',
            ]
        ]);
    }

    /**
     * Fetch logged in user's story archive & highlights.
     */
    public function fetchStoryArchive()
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $archived = ChatStory::where('user_id', Auth::id())
            ->archived()
            ->orderBy('created_at', 'desc')
            ->get();

        $highlights = ChatStory::where('user_id', Auth::id())
            ->highlights()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'archived' => $archived,
            'highlights' => $highlights
        ]);
    }

    /**
     * Toggle Pin as Story Highlight.
     */
    public function toggleStoryHighlight($id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $story = ChatStory::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $story->is_highlight = !$story->is_highlight;
        $story->save();

        return response()->json([
            'status' => 'success',
            'is_highlight' => $story->is_highlight,
            'message' => $story->is_highlight ? 'Story pinned to Profile Highlights!' : 'Story unpinned from Highlights.'
        ]);
    }

    /**
     * Create Twitter/X style post on user profile with optional scheduling.
     */
    public function createUserPost(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate([
            'content' => 'nullable|string|max:2000',
            'media' => 'nullable|file|max:512000',
            'privacy' => 'nullable|string|in:public,followers,private',
            'scheduled_at' => 'nullable|date',
        ]);

        if (!$request->filled('content') && !$request->hasFile('media')) {
            return response()->json(['status' => 'error', 'message' => 'Post cannot be empty.'], 422);
        }

        $mediaUrl = null;
        $mediaType = 'text';
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $uploadDir = public_path('uploads/posts');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'post_' . Auth::id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $mediaUrl = 'uploads/posts/' . $filename;

            $mime = $file->getClientMimeType();
            $mediaType = str_contains($mime, 'video') ? 'video' : 'image';
        }

        $scheduledAt = $request->filled('scheduled_at') ? $request->input('scheduled_at') : null;

        $post = UserPost::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'media_url' => $mediaUrl,
            'media_type' => $mediaType,
            'privacy' => $request->input('privacy', 'public'),
            'scheduled_at' => $scheduledAt,
        ]);

        // Award +15 Contribution Points (CP) to author's Sandika Rank
        $updatedRank = SandikaUserRank::addXp(Auth::id(), 15);

        $msg = $scheduledAt ? 'Post scheduled successfully!' : "Post published to your profile! (+15 Sandika CPs earned! Total: {$updatedRank->xp} CP)";

        return response()->json(['status' => 'success', 'message' => $msg, 'post' => $post, 'new_cp' => $updatedRank->xp]);
    }

    /**
     * Fetch Twitter/X style posts for target user profile.
     */
    public function fetchUserPosts($id)
    {
        $user = User::findOrFail($id);
        $currentUserId = Auth::id();

        $posts = UserPost::where('user_id', $id)
            ->published()
            ->with(['user:id,name,first_name,last_name,username,avatar', 'comments.user:id,name,username,avatar'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($p) use ($currentUserId, $id) {
                if ($p->privacy === 'private' && $currentUserId !== $id) return false;
                if ($p->privacy === 'followers' && $currentUserId !== $id) {
                    return $currentUserId ? UserFollow::where('follower_id', $currentUserId)->where('following_id', $id)->exists() : false;
                }
                return true;
            })
            ->map(function ($p) use ($currentUserId) {
                return $this->formatPostArray($p, $currentUserId);
            });

        $avatarsGallery = is_array($user->avatars_gallery) ? array_map(fn($p) => asset($p), $user->avatars_gallery) : [];
        if ($user->avatar && !in_array(asset($user->avatar), $avatarsGallery)) {
            array_unshift($avatarsGallery, asset($user->avatar));
        }

        $headersGallery = is_array($user->headers_gallery) ? array_map(fn($p) => asset($p), $user->headers_gallery) : [];
        if ($user->header_banner && !in_array(asset($user->header_banner), $headersGallery)) {
            array_unshift($headersGallery, asset($user->header_banner));
        }

        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'avatar_url' => $user->avatar ? asset($user->avatar) : asset('images/profile.jpg'),
                'header_url' => $user->header_banner ? asset($user->header_banner) : null,
                'avatars_gallery' => array_values(array_unique($avatarsGallery)),
                'headers_gallery' => array_values(array_unique($headersGallery)),
                'bio' => $user->bio,
                'social_links' => $user->social_links,
            ],
            'posts' => array_values($posts->toArray())
        ]);
    }

    /**
     * Fetch global timeline feed of public and followed user posts.
     */
    public function fetchPublicFeedPosts()
    {
        $currentUserId = Auth::id();

        $posts = UserPost::published()
            ->with(['user:id,name,first_name,last_name,username,avatar', 'comments.user:id,name,username,avatar'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->filter(function ($p) use ($currentUserId) {
                if ($p->privacy === 'private' && $p->user_id !== $currentUserId) return false;
                if ($p->privacy === 'followers' && $p->user_id !== $currentUserId) {
                    return $currentUserId ? UserFollow::where('follower_id', $currentUserId)->where('following_id', $p->user_id)->exists() : false;
                }
                return true;
            })
            ->map(function ($p) use ($currentUserId) {
                return $this->formatPostArray($p, $currentUserId);
            });

        return response()->json([
            'status' => 'success',
            'posts' => array_values($posts->toArray())
        ]);
    }

    /**
     * Helper to format post output with comments, likes, reposts, bookmarks.
     */
    private function formatPostArray($p, $currentUserId)
    {
        $likedBy = is_array($p->liked_by_users) ? $p->liked_by_users : [];
        $bookmarkedBy = is_array($p->bookmarked_by_users) ? $p->bookmarked_by_users : [];
        $repostedBy = is_array($p->reposted_by_users) ? $p->reposted_by_users : [];

        $formattedComments = $p->comments->map(function ($c) {
            return [
                'id' => $c->id,
                'user_name' => $c->user ? $c->user->name : 'Member',
                'username' => $c->user ? $c->user->username : 'user',
                'avatar_url' => $c->user && $c->user->avatar ? asset($c->user->avatar) : asset('images/profile.jpg'),
                'comment' => $c->comment,
                'created_at' => $c->created_at->diffForHumans(),
            ];
        });

        return [
            'id' => $p->id,
            'user_id' => $p->user_id,
            'user_name' => $p->user ? (trim(($p->user->first_name . ' ' . $p->user->last_name)) ?: $p->user->name) : 'User',
            'username' => $p->user ? ($p->user->username ?? Str::slug($p->user->name)) : 'user',
            'avatar_url' => $p->user && $p->user->avatar ? asset($p->user->avatar) : asset('images/profile.jpg'),
            'content' => $p->content,
            'media_url' => $p->media_url ? asset($p->media_url) : null,
            'media_type' => $p->media_type,
            'likes_count' => $p->likes_count,
            'reposts_count' => $p->reposts_count,
            'bookmarks_count' => $p->bookmarks_count,
            'is_liked' => $currentUserId ? in_array($currentUserId, $likedBy) : false,
            'is_bookmarked' => $currentUserId ? in_array($currentUserId, $bookmarkedBy) : false,
            'is_reposted' => $currentUserId ? in_array($currentUserId, $repostedBy) : false,
            'comments' => $formattedComments,
            'comments_count' => count($formattedComments),
            'created_at' => $p->created_at->diffForHumans(),
            'scheduled_at' => $p->scheduled_at ? $p->scheduled_at->diffForHumans() : null,
        ];
    }

    /**
     * Toggle like on Twitter/X user post.
     */
    public function toggleLikeUserPost($id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $post = UserPost::findOrFail($id);
        $likedBy = is_array($post->liked_by_users) ? $post->liked_by_users : [];
        $userId = Auth::id();

        if (in_array($userId, $likedBy)) {
            $likedBy = array_values(array_diff($likedBy, [$userId]));
            $post->likes_count = max(0, $post->likes_count - 1);
            $isLiked = false;
        } else {
            $likedBy[] = $userId;
            $post->likes_count++;
            $isLiked = true;
        }

        $post->liked_by_users = $likedBy;
        $post->save();

        return response()->json([
            'status' => 'success',
            'likes_count' => $post->likes_count,
            'is_liked' => $isLiked
        ]);
    }

    /**
     * Toggle Repost on Twitter/X user post.
     */
    public function toggleRepostUserPost($id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $post = UserPost::findOrFail($id);
        $repostedBy = is_array($post->reposted_by_users) ? $post->reposted_by_users : [];
        $userId = Auth::id();

        if (in_array($userId, $repostedBy)) {
            $repostedBy = array_values(array_diff($repostedBy, [$userId]));
            $post->reposts_count = max(0, $post->reposts_count - 1);
            $isReposted = false;
        } else {
            $repostedBy[] = $userId;
            $post->reposts_count++;
            $isReposted = true;
        }

        $post->reposted_by_users = $repostedBy;
        $post->save();

        return response()->json([
            'status' => 'success',
            'reposts_count' => $post->reposts_count,
            'is_reposted' => $isReposted
        ]);
    }

    /**
     * Toggle Bookmark on Twitter/X user post.
     */
    public function toggleBookmarkUserPost($id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $post = UserPost::findOrFail($id);
        $bookmarkedBy = is_array($post->bookmarked_by_users) ? $post->bookmarked_by_users : [];
        $userId = Auth::id();

        if (in_array($userId, $bookmarkedBy)) {
            $bookmarkedBy = array_values(array_diff($bookmarkedBy, [$userId]));
            $post->bookmarks_count = max(0, $post->bookmarks_count - 1);
            $isBookmarked = false;
        } else {
            $bookmarkedBy[] = $userId;
            $post->bookmarks_count++;
            $isBookmarked = true;
        }

        $post->bookmarked_by_users = $bookmarkedBy;
        $post->save();

        return response()->json([
            'status' => 'success',
            'bookmarks_count' => $post->bookmarks_count,
            'is_bookmarked' => $isBookmarked
        ]);
    }

    /**
     * Add comment to a user post.
     */
    public function addPostComment(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate(['comment' => 'required|string|max:1000']);
        $post = UserPost::findOrFail($id);

        $comment = PostComment::create([
            'user_post_id' => $post->id,
            'user_id' => Auth::id(),
            'comment' => $request->input('comment'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment added!',
            'comment' => [
                'id' => $comment->id,
                'user_name' => Auth::user()->name,
                'username' => Auth::user()->username ?? 'user',
                'avatar_url' => Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('images/profile.jpg'),
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->diffForHumans(),
            ]
        ]);
    }

    /**
     * Delete user post.
     */
    public function deleteUserPost($id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $post = UserPost::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $post->delete();

        return response()->json(['status' => 'success', 'message' => 'Post deleted successfully.']);
    }
}
