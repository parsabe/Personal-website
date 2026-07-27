<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Render the main Online Chat Portal view.
     */
    public function index()
    {
        $user = Auth::user();
        $defaultUsername = $user ? ($user->username ?? Str::slug($user->name)) : 'guest_' . Str::random(5);
        $defaultName = $user ? $user->name : 'Guest User';

        return view('pages.chat.chat', compact('user', 'defaultUsername', 'defaultName'));
    }

    /**
     * Fetch delivered messages and release due scheduled messages.
     */
    public function fetchMessages(Request $request)
    {
        // 1. Release due scheduled messages
        ChatMessage::due()->update([
            'delivered_at' => now()
        ]);

        // 2. Query delivered messages
        $messages = ChatMessage::delivered()
            ->with('user:id,name,email')
            ->orderBy('created_at', 'asc')
            ->take(100)
            ->get();

        // 3. Transform for JSON API
        $formatted = $messages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'user_id' => $msg->user_id,
                'sender_name' => $msg->sender_name,
                'username' => $msg->username ?? Str::slug($msg->sender_name),
                'message' => $msg->message,
                'type' => $msg->type,
                'file_url' => $msg->file_path ? asset($msg->file_path) : null,
                'file_name' => $msg->file_name,
                'file_size' => $msg->formatted_file_size,
                'mime_type' => $msg->mime_type,
                'scheduled_at' => $msg->scheduled_at ? $msg->scheduled_at->toDateTimeString() : null,
                'created_at' => $msg->created_at->format('H:i, M d'),
                'is_me' => Auth::check() && Auth::id() === $msg->user_id,
            ];
        });

        // 4. Return upcoming scheduled count for current user if logged in
        $scheduledCount = 0;
        if (Auth::check()) {
            $scheduledCount = ChatMessage::where('user_id', Auth::id())
                ->whereNull('delivered_at')
                ->whereNotNull('scheduled_at')
                ->count();
        }

        return response()->json([
            'status' => 'success',
            'messages' => $formatted,
            'scheduled_count' => $scheduledCount,
        ]);
    }

    /**
     * Send a standard text / gif / emoji message or schedule it.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'sender_name' => 'required|string|max:100',
            'username' => 'nullable|string|max:100',
            'message' => 'nullable|string|max:5000',
            'type' => 'required|in:text,image,gif,file,voice,video',
            'scheduled_at' => 'nullable|date',
            'file_url' => 'nullable|string',
        ]);

        $user = Auth::user();
        $isScheduled = $request->filled('scheduled_at') && strtotime($request->scheduled_at) > time();

        $msg = ChatMessage::create([
            'user_id' => $user ? $user->id : null,
            'sender_name' => $request->sender_name,
            'username' => $request->username ?? Str::slug($request->sender_name),
            'message' => $request->message,
            'type' => $request->type,
            'file_path' => $request->file_url,
            'scheduled_at' => $isScheduled ? $request->scheduled_at : null,
            'delivered_at' => $isScheduled ? null : now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $isScheduled ? 'Message scheduled successfully!' : 'Message sent!',
            'data' => $msg
        ]);
    }

    /**
     * Upload attachments, images, voice notes, video clips, and large files.
     */
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'sender_name' => 'required|string|max:100',
            'username' => 'nullable|string|max:100',
            'type' => 'required|in:image,file,voice,video',
            'scheduled_at' => 'nullable|date',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $mimeType = $file->getClientMimeType();

        // Create target directory if it doesn't exist
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
        $isScheduled = $request->filled('scheduled_at') && strtotime($request->scheduled_at) > time();

        $msg = ChatMessage::create([
            'user_id' => $user ? $user->id : null,
            'sender_name' => $request->sender_name,
            'username' => $request->username ?? Str::slug($request->sender_name),
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
            'message' => 'File uploaded successfully!',
            'file_url' => asset($filePath),
            'data' => $msg
        ]);
    }
}
