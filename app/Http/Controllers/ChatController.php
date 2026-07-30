<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatMessageReaction;
use App\Models\ChatStory;
use App\Models\User;
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
     * Update user profile settings.
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
        ]);

        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $uploadDir = public_path('uploads/avatars');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $avatarFile->getClientOriginalExtension();
            $avatarFile->move($uploadDir, $filename);
            $user->avatar = 'uploads/avatars/' . $filename;
        }

        if ($request->has('first_name')) $user->first_name = $request->input('first_name');
        if ($request->has('last_name')) $user->last_name = $request->input('last_name');
        if ($request->filled('username')) $user->username = $request->input('username');
        if ($request->has('bio')) $user->bio = $request->input('bio');

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
            'message' => 'Profile updated successfully!',
            'user' => [
                'name' => trim($user->first_name . ' ' . $user->last_name) ?: $user->name,
                'username' => $user->username,
                'avatar_url' => asset($user->avatar),
                'bio' => $user->bio,
                'social_links' => $user->social_links,
            ]
        ]);
    }

    /**
     * Fetch active 24-hour stories with Instagram Tools.
     */
    public function fetchStories()
    {
        $stories = ChatStory::active()
            ->with('user:id,name,first_name,last_name,username,avatar')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'user_id' => $s->user_id,
                    'user_name' => trim(($s->user->first_name . ' ' . $s->user->last_name)) ?: $s->user->name,
                    'username' => $s->user->username ?? Str::slug($s->user->name),
                    'avatar_url' => $s->user && $s->user->avatar ? asset($s->user->avatar) : asset('images/profile.jpg'),
                    'content' => $s->content,
                    'media_url' => $s->media_url ? asset($s->media_url) : null,
                    'story_type' => $s->story_type ?? 'standard',
                    'poll_options' => $s->poll_options ? json_decode($s->poll_options, true) : null,
                    'mentions' => $s->mentions ? json_decode($s->mentions, true) : null,
                    'sticker_data' => $s->sticker_data ? json_decode($s->sticker_data, true) : null,
                    'created_at' => $s->created_at->diffForHumans(),
                ];
            });

        return response()->json(['status' => 'success', 'stories' => $stories]);
    }

    /**
     * Create a new 24-hour status story with rich Instagram Tools (Polls, Mentions, Question stickers).
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
            'poll_options' => $pollOptions,
            'mentions' => $mentions,
            'sticker_data' => $stickerData,
            'expires_at' => now()->addHours(24),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Instagram Story posted with tools!', 'story' => $story]);
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

        $story = ChatStory::find($request->input('story_id'));
        if (!$story || !$story->poll_options) {
            return response()->json(['status' => 'error', 'message' => 'Invalid story poll.'], 404);
        }

        $poll = json_decode($story->poll_options, true);
        $userId = Auth::id();

        if (in_array($userId, $poll['voters'] ?? [])) {
            return response()->json(['status' => 'info', 'message' => 'You have already voted on this poll!']);
        }

        if ($request->input('option') === 'a') {
            $poll['votes_a']++;
        } else {
            $poll['votes_b']++;
        }
        $poll['voters'][] = $userId;

        $story->poll_options = json_encode($poll);
        $story->save();

        return response()->json(['status' => 'success', 'message' => 'Vote recorded!', 'poll' => $poll]);
    }
}
