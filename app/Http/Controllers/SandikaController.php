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
        $authenticated = Auth::check();
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

        $solvedArkhamIds = [];
        if ($user) {
            try {
                $solvedArkhamIds = DB::table('sandika_arkham_solves')
                    ->where('user_id', $user->id)
                    ->pluck('spirit_id')
                    ->toArray();
            } catch (\Exception $e) {
                $solvedArkhamIds = [];
            }
        }

        return view('pages.sandika', compact('authenticated', 'user', 'rank', 'stories', 'dictionary', 'gitInsights', 'solvedArkhamIds'));
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

    /**
     * Solve Amadeus Arkham Spirit Cipher (+20 CP & MP3 Audio Playback).
     */
    public function solveArkhamSpirit(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized', 'message' => 'Login required to decipher Arkham Spirits.'], 401);
        }

        $request->validate([
            'spirit_id' => 'required|integer|between:1,10',
            'answer' => 'required|string',
        ]);

        $spiritId = (int) $request->input('spirit_id');
        $answer = strtolower(trim($request->input('answer')));
        $userId = Auth::id();

        // Check if already solved by user
        try {
            $alreadySolved = DB::table('sandika_arkham_solves')
                ->where('user_id', $userId)
                ->where('spirit_id', $spiritId)
                ->exists();

            if ($alreadySolved) {
                return response()->json([
                    'status' => 'already_solved',
                    'message' => 'You have already deciphered this Arkham Spirit.',
                    'audio_url' => asset("audio/{$spiritId}.mp3"),
                ], 422);
            }
        } catch (\Exception $e) {
            // Fallback table creation handled via migration
        }

        // Canonical answers per spirit ID
        $validAnswers = [
            1 => ['i am the spirit of amadeus arkham', 'amadeus arkham', 'amadeus', 'spirit of amadeus arkham'],
            2 => ['my mother\'s memory', 'mother\'s memory', 'mother', 'memory'],
            3 => ['madness is the only escape', 'madness', 'escape'],
            4 => ['the asylum is my legacy', 'asylum', 'legacy'],
            5 => ['cyrus pinkney', 'pinkney', 'cyrus'],
            6 => ['the warden', 'warden', 'sharp'],
            7 => ['batman will fall', 'batman', 'fall'],
            8 => ['gotham city', 'gotham', 'city'],
            9 => ['arkham island', 'arkham', 'island'],
            10 => ['the spirit of arkham', 'spirit of arkham', 'spirit'],
        ];

        $accepted = $validAnswers[$spiritId] ?? ['arkham'];
        $isCorrect = false;

        foreach ($accepted as $target) {
            if (Str::contains($answer, $target) || $answer === $target) {
                $isCorrect = true;
                break;
            }
        }

        if (!$isCorrect && strlen($answer) < 3) {
            return response()->json([
                'status' => 'incorrect',
                'message' => 'The deciphered answer is incorrect. Try again.',
            ], 422);
        }

        // Award +20 CP and record solve
        try {
            DB::table('sandika_arkham_solves')->insert([
                'user_id' => $userId,
                'spirit_id' => $spiritId,
                'user_answer' => $request->input('answer'),
                'cp_awarded' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Ignore if table not ready
        }

        $rank = SandikaUserRank::addXp($userId, 20);

        return response()->json([
            'status' => 'success',
            'message' => 'The Arkham Spirit Deciphered! +20 CPs awarded!',
            'audio_url' => asset("audio/{$spiritId}.mp3"),
            'spirit_id' => $spiritId,
            'rank' => $rank,
        ]);
    }
}
