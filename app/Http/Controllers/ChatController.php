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
     * Render the main Online Chat Portal view.
     */
    public function index()
    {
        $user = Auth::user();
        $authenticated = Auth::check();

        return view('pages.chat.chat', compact('user', 'authenticated'));
    }

    /**
     * Fetch delivered messages with sender avatars, full names, and reactions.
     */
    public function fetchMessages(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized', 'messages' => []], 401);
        }

        // 1. Release due scheduled messages
        ChatMessage::due()->update(['delivered_at' => now()]);

        // 2. Query delivered messages with user & reactions
        $messages = ChatMessage::delivered()
            ->with(['user', 'reactions'])
            ->orderBy('created_at', 'asc')
            ->take(150)
            ->get();

        $currentUserId = Auth::id();

        // 3. Transform messages
        $formatted = $messages->map(function ($msg) use ($currentUserId) {
            $senderUser = $msg->user;
            $avatarUrl = $senderUser && $senderUser->avatar 
                ? asset($senderUser->avatar) 
                : asset('images/profile.jpg');

            $fullName = $senderUser 
                ? trim(($senderUser->first_name . ' ' . $senderUser->last_name)) ?: $senderUser->name 
                : $msg->sender_name;

            // Group reactions by emoji
            $reactionGroups = [];
            foreach ($msg->reactions as $reaction) {
                $emoji = $reaction->emoji;
                if (!isset($reactionGroups[$emoji])) {
                    $reactionGroups[$emoji] = [
                        'emoji' => $emoji,
                        'count' => 0,
                        'user_reacted' => false,
                    ];
                }
                $reactionGroups[$emoji]['count']++;
                if ($reaction->user_id === $currentUserId) {
                    $reactionGroups[$emoji]['user_reacted'] = true;
                }
            }

            return [
                'id' => $msg->id,
                'user_id' => $msg->user_id,
                'sender_name' => $fullName,
                'username' => $senderUser->username ?? $msg->username ?? Str::slug($fullName),
                'avatar_url' => $avatarUrl,
                'message' => $msg->message,
                'type' => $msg->type,
                'file_url' => $msg->file_path ? asset($msg->file_path) : null,
                'file_name' => $msg->file_name,
                'file_size' => $msg->formatted_file_size,
                'mime_type' => $msg->mime_type,
                'reactions' => array_values($reactionGroups),
                'created_at' => $msg->created_at->format('H:i, M d'),
                'is_me' => $currentUserId === $msg->user_id,
            ];
        });

        // 4. Scheduled count
        $scheduledCount = ChatMessage::where('user_id', $currentUserId)
            ->whereNull('delivered_at')
            ->whereNotNull('scheduled_at')
            ->count();

        return response()->json([
            'status' => 'success',
            'messages' => $formatted,
            'scheduled_count' => $scheduledCount,
        ]);
    }

    /**
     * Fetch list of all registered users for contact sidebar.
     */
    public function fetchUsers()
    {
        if (!Auth::check()) {
            return response()->json(['users' => []], 401);
        }

        $users = User::select('id', 'name', 'first_name', 'last_name', 'username', 'email', 'avatar', 'bio', 'social_links')
            ->orderBy('name', 'asc')
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
     * Send a standard text / gif / emoji message.
     */
    public function sendMessage(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized', 'message' => 'Please log in to chat.'], 401);
        }

        $request->validate([
            'message' => 'nullable|string|max:5000',
            'type' => 'required|in:text,image,gif,file,voice,video',
            'scheduled_at' => 'nullable|date',
            'file_url' => 'nullable|string',
        ]);

        $user = Auth::user();
        $senderName = trim(($user->first_name . ' ' . $user->last_name)) ?: $user->name;
        $isScheduled = $request->filled('scheduled_at') && strtotime($request->scheduled_at) > time();

        $msg = ChatMessage::create([
            'user_id' => $user->id,
            'sender_name' => $senderName,
            'username' => $user->username ?? Str::slug($user->name),
            'message' => $request->message,
            'type' => $request->type,
            'file_path' => $request->file_url,
            'scheduled_at' => $isScheduled ? $request->scheduled_at : null,
            'delivered_at' => $isScheduled ? null : now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $isScheduled ? 'Message scheduled!' : 'Message sent!',
            'data' => $msg
        ]);
    }

    /**
     * Upload file attachment / voice / video note.
     */
    public function uploadFile(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate([
            'file' => 'required|file',
            'type' => 'required|in:image,file,voice,video',
            'scheduled_at' => 'nullable|date',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $mimeType = $file->getClientMimeType();

        $uploadDir = public_path('uploads/chat');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = $file->getClientOriginalExtension() ?: 'bin';
        if ($request->type === 'voice' && empty($extension)) {
            $extension = 'webm';
        }

        $filename = time() . '_' . Str::random(10) . '.' . $extension;
        $file->move($uploadDir, $filename);

        $filePath = 'uploads/chat/' . $filename;
        $user = Auth::user();
        $senderName = trim(($user->first_name . ' ' . $user->last_name)) ?: $user->name;
        $isScheduled = $request->filled('scheduled_at') && strtotime($request->scheduled_at) > time();

        $msg = ChatMessage::create([
            'user_id' => $user->id,
            'sender_name' => $senderName,
            'username' => $user->username ?? Str::slug($user->name),
            'message' => $request->input('message'),
            'type' => $request->type,
            'file_path' => $filePath,
            'file_name' => $originalName,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'scheduled_at' => $isScheduled ? $request->scheduled_at : null,
            'delivered_at' => $isScheduled ? null : now(),
        ]);

        return response()->json([
            'status' => 'success',
            'file_url' => asset($filePath),
            'data' => $msg
        ]);
    }

    /**
     * Toggle Telegram-style emoji reaction on a message.
     */
    public function toggleReaction(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate([
            'chat_message_id' => 'required|exists:chat_messages,id',
            'emoji' => 'required|string|max:16',
        ]);

        $userId = Auth::id();
        $messageId = $request->chat_message_id;
        $emoji = $request->emoji;

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
     * Update user profile settings (avatar, first/last name, username, bio, social links).
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
            'social_linkedin' => 'nullable|url',
            'social_github' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_website' => 'nullable|url',
            'avatar' => 'nullable|image|max:10240', // 10MB max avatar
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

        if ($request->filled('first_name')) $user->first_name = $request->first_name;
        if ($request->filled('last_name')) $user->last_name = $request->last_name;
        if ($request->filled('username')) $user->username = $request->username;
        if ($request->filled('bio')) $user->bio = $request->bio;

        $user->social_links = [
            'linkedin' => $request->input('social_linkedin'),
            'github' => $request->input('social_github'),
            'twitter' => $request->input('social_twitter'),
            'website' => $request->input('social_website'),
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
     * Fetch active 24-hour stories.
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
                    'avatar_url' => $s->user && $s->user->avatar ? asset($s->user->avatar) : asset('images/profile.jpg'),
                    'content' => $s->content,
                    'media_url' => $s->media_url ? asset($s->media_url) : null,
                    'created_at' => $s->created_at->diffForHumans(),
                ];
            });

        return response()->json(['status' => 'success', 'stories' => $stories]);
    }

    /**
     * Create a new 24-hour status story.
     */
    public function createStory(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate([
            'content' => 'nullable|string|max:1000',
            'media' => 'nullable|file|max:20480',
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

        $story = ChatStory::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'media_url' => $mediaUrl,
            'expires_at' => now()->addHours(24),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Story posted!', 'story' => $story]);
    }
}
