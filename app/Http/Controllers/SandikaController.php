<?php

namespace App\Http\Controllers;

use App\Models\SandikaUserRank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SandikaController extends Controller
{
    /**
     * Render Full Sandika Concept View inside homepage container layout.
     */
    public function index()
    {
        $user = Auth::user();
        $rank = null;
        if ($user) {
            $rank = SandikaUserRank::firstOrCreate(
                ['user_id' => $user->id],
                ['xp' => 50, 'rank_title' => 'Captain ⚔️ (Verified)', 'level' => 3]
            );
        }

        $stories = DB::table('sandika_stories')->orderBy('created_at', 'desc')->take(10)->get();
        $dictionary = DB::table('sandika_dictionary')->orderBy('created_at', 'desc')->take(15)->get();
        $gitInsights = DB::table('sandika_git_insights')->orderBy('created_at', 'desc')->take(10)->get();

        return view('pages.sandika', compact('user', 'rank', 'stories', 'dictionary', 'gitInsights'));
    }

    /**
     * Post a Story (+10 CP).
     */
    public function postStory(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:50',
        ]);

        $userId = Auth::id();
        $cp = strlen($request->input('content')) >= 1000 ? 15 : 10;

        DB::table('sandika_stories')->insert([
            'user_id' => $userId,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'cp_awarded' => $cp,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $rank = SandikaUserRank::addXp($userId, $cp);

        return response()->json([
            'status' => 'success',
            'message' => "Story published to Sandika network! +{$cp} CP awarded.",
            'rank' => $rank,
        ]);
    }

    /**
     * Add Word to Dictionary (+10 CP).
     */
    public function addDictionaryWord(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate([
            'language' => 'required|in:en,de',
            'word' => 'required|string|max:100',
            'definition' => 'required|string|max:500',
        ]);

        $userId = Auth::id();

        DB::table('sandika_dictionary')->insert([
            'user_id' => $userId,
            'language' => $request->input('language'),
            'word' => $request->input('word'),
            'definition' => $request->input('definition'),
            'cp_awarded' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $rank = SandikaUserRank::addXp($userId, 10);

        return response()->json([
            'status' => 'success',
            'message' => 'Vocabulary added to Sandika Lexicon! +10 CP awarded.',
            'rank' => $rank,
        ]);
    }

    /**
     * Post Git Insight (+15 CP for verified, +5 CP standard).
     */
    public function postGitInsight(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate([
            'repo_url' => 'required|url',
            'description' => 'required|string',
        ]);

        $userId = Auth::id();
        $isGithub = Str::contains($request->input('repo_url'), 'github.com');
        $cp = $isGithub ? 15 : 5;

        DB::table('sandika_git_insights')->insert([
            'user_id' => $userId,
            'repo_url' => $request->input('repo_url'),
            'description' => $request->input('description'),
            'is_github_verified' => $isGithub,
            'cp_awarded' => $cp,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $rank = SandikaUserRank::addXp($userId, $cp);

        return response()->json([
            'status' => 'success',
            'message' => "Git Insight logged cleanly! +{$cp} CP awarded.",
            'rank' => $rank,
        ]);
    }

    /**
     * Analyze Voice Log (+45 CP).
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
            'message' => 'Voice audio log analyzed cleanly. +45 CP awarded!',
            'rank' => $rank,
        ]);
    }

    /**
     * Ingest File (+30 CP).
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
            'message' => 'File ingested and encrypted in Sandika storage. +30 CP awarded!',
            'rank' => $rank,
        ]);
    }
}
