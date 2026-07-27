<?php

namespace App\Http\Controllers;

use App\Models\SandikaUserRank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SandikaController extends Controller
{
    /**
     * Render Sandika Portal view (Inside homepage container grid).
     */
    public function index()
    {
        $user = Auth::user();
        $rank = null;
        if ($user) {
            $rank = SandikaUserRank::firstOrCreate(
                ['user_id' => $user->id],
                ['xp' => 50, 'rank_title' => 'Novice Operative', 'level' => 1]
            );
        }

        return view('pages.sandika', compact('user', 'rank'));
    }

    /**
     * Analyze voice log / transmission and reward XP.
     */
    public function analyzeVoiceLog(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $userId = Auth::id();
        $rank = SandikaUserRank::addXp($userId, 45);
        $rank->increment('voice_logs_analyzed');

        return response()->json([
            'status' => 'success',
            'message' => 'Voice audio log analyzed cleanly. +45 XP awarded!',
            'rank' => [
                'xp' => $rank->xp,
                'level' => $rank->level,
                'rank_title' => $rank->rank_title,
                'voice_logs' => $rank->voice_logs_analyzed,
            ]
        ]);
    }

    /**
     * Process file upload in Sandika.
     */
    public function processFile(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate(['file' => 'required|file|max:102400']);

        $userId = Auth::id();
        $rank = SandikaUserRank::addXp($userId, 30);
        $rank->increment('files_processed');

        return response()->json([
            'status' => 'success',
            'message' => 'File ingested and encrypted in Sandika storage. +30 XP awarded!',
            'rank' => [
                'xp' => $rank->xp,
                'level' => $rank->level,
                'rank_title' => $rank->rank_title,
                'files_processed' => $rank->files_processed,
            ]
        ]);
    }
}
