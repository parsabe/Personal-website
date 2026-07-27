<?php

namespace App\Http\Controllers;

use App\Models\NigmaPuzzle;
use App\Models\SandikaUserRank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NigmaController extends Controller
{
    /**
     * Riddles Archive Data (20 riddles from original Nigma app)
     */
    protected $riddles = [
        1 => ['title' => 'Riddle 1: The Invisible Companion', 'riddle' => 'I have no voice, yet I speak to many. I tell of all things in the world that exist. What am I?', 'answer' => 'book', 'cipher' => 'Caesar Shift +3'],
        2 => ['title' => 'Riddle 2: The Footstep Paradox', 'riddle' => 'The more of me you take, the more you leave behind. What am I?', 'answer' => 'footsteps', 'cipher' => 'ROT13'],
        3 => ['title' => 'Riddle 3: The Echo of Time', 'riddle' => 'What breaks yet never falls, and what falls yet never breaks?', 'answer' => 'day and night', 'cipher' => 'Vigenère'],
        4 => ['title' => 'Riddle 4: The Hollow Sentinel', 'riddle' => 'I have cities, but no houses; forests, but no trees; and rivers, but no water. What am I?', 'answer' => 'map', 'cipher' => 'Caesar Shift +5'],
        5 => ['title' => 'Riddle 5: The Eternal Flame', 'riddle' => 'I am not alive, but I grow; I do not have lungs, but I need air; I do not have a mouth, but water kills me. What am I?', 'answer' => 'fire', 'cipher' => 'Atbash'],
        6 => ['title' => 'Riddle 6: The Weight of Shadows', 'riddle' => 'What is so fragile that saying its name breaks it?', 'answer' => 'silence', 'cipher' => 'ROT13'],
        7 => ['title' => 'Riddle 7: The Unbroken Thread', 'riddle' => 'What runs everywhere but never moves?', 'answer' => 'road', 'cipher' => 'Caesar Shift +7'],
        8 => ['title' => 'Riddle 8: The Silent Key', 'riddle' => 'I have keys but no locks. I have space but no room. You can enter, but can’t go outside. What am I?', 'answer' => 'keyboard', 'cipher' => 'Base64'],
        9 => ['title' => 'Riddle 9: The Dark Reflection', 'riddle' => 'I follow you all day long, but when the night comes I am gone. What am I?', 'answer' => 'shadow', 'cipher' => 'ROT13'],
        10 => ['title' => 'Riddle 10: The Cryptic Coin', 'riddle' => 'What has a head and a tail, but no body?', 'answer' => 'coin', 'cipher' => 'Caesar Shift +2'],
        11 => ['title' => 'Riddle 11: The Endless Loop', 'riddle' => 'What gets wetter as it dries?', 'answer' => 'towel', 'cipher' => 'ROT13'],
        12 => ['title' => 'Riddle 12: The Light in Darkness', 'riddle' => 'What can fill a room but takes up no space?', 'answer' => 'light', 'cipher' => 'Atbash'],
        13 => ['title' => 'Riddle 13: The Golden Chamber', 'riddle' => 'A box without hinges, key, or lid, yet golden treasure inside is hid. What am I?', 'answer' => 'egg', 'cipher' => 'Caesar Shift +4'],
        14 => ['title' => 'Riddle 14: The Unseen Force', 'riddle' => 'I am light as a feather, yet the strongest man cannot hold me for much longer than a minute. What am I?', 'answer' => 'breath', 'cipher' => 'ROT13'],
        15 => ['title' => 'Riddle 15: The Time Keeper', 'riddle' => 'What has hands but cannot clap?', 'answer' => 'clock', 'cipher' => 'Caesar Shift +1'],
        16 => ['title' => 'Riddle 16: The Silent Watcher', 'riddle' => 'I have one eye, but cannot see. What am I?', 'answer' => 'needle', 'cipher' => 'Atbash'],
        17 => ['title' => 'Riddle 17: The Flying Cipher', 'riddle' => 'What has wings but cannot fly, has legs but cannot walk?', 'answer' => 'building', 'cipher' => 'ROT13'],
        18 => ['title' => 'Riddle 18: The Whispering Wind', 'riddle' => 'I pass through glass without breaking it. What am I?', 'answer' => 'light', 'cipher' => 'Caesar Shift +6'],
        19 => ['title' => 'Riddle 19: The Secret Keeper', 'riddle' => 'If you have me, you want to share me. If you share me, you haven’t got me. What am I?', 'answer' => 'secret', 'cipher' => 'ROT13'],
        20 => ['title' => 'Riddle 20: The Final Cryptogram', 'riddle' => 'The one who makes it, has no need of it; the one who buys it, has no use for it. The one who uses it can neither see nor feel it. What is it?', 'answer' => 'coffin', 'cipher' => 'Vigenère'],
    ];

    /**
     * Render Nigma Riddler Portal view.
     */
    public function index()
    {
        $userId = Auth::id();
        $solvedIds = [];

        if ($userId) {
            $solvedIds = DB::table('nigma_user_solves')
                ->where('user_id', $userId)
                ->pluck('riddle_id')
                ->toArray();
        }

        $riddlesList = [];
        foreach ($this->riddles as $id => $data) {
            $riddlesList[] = (object)[
                'id' => $id,
                'title' => $data['title'],
                'riddle' => $data['riddle'],
                'cipher_type' => $data['cipher'],
                'is_solved' => in_array($id, $solvedIds),
            ];
        }

        return view('pages.nigma', [
            'puzzles' => $riddlesList,
            'totalSolved' => count($solvedIds),
        ]);
    }

    /**
     * Solve a riddle in Nigma (+15 CP awarded in Sandika!).
     */
    public function solve(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized', 'message' => 'Please sign in to submit puzzle solutions.'], 401);
        }

        $request->validate([
            'puzzle_id' => 'required|integer',
            'answer' => 'required|string',
        ]);

        $puzzleId = (int)$request->input('puzzle_id');
        $userAnswer = strtolower(trim($request->input('answer')));

        if (!isset($this->riddles[$puzzleId])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid riddle ID.'], 404);
        }

        $expectedAnswer = strtolower($this->riddles[$puzzleId]['answer']);

        if (strpos($userAnswer, $expectedAnswer) === false && $userAnswer !== $expectedAnswer) {
            return response()->json(['status' => 'error', 'message' => 'Incorrect answer key. Try again!'], 400);
        }

        $userId = Auth::id();

        // Check if already solved
        $alreadySolved = DB::table('nigma_user_solves')
            ->where('user_id', $userId)
            ->where('riddle_id', $puzzleId)
            ->exists();

        if ($alreadySolved) {
            return response()->json([
                'status' => 'info',
                'message' => 'You have already solved this riddle transmission!',
            ]);
        }

        // Record solve
        DB::table('nigma_user_solves')->insert([
            'user_id' => $userId,
            'riddle_id' => $puzzleId,
            'solution' => $userAnswer,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Award +15 CP in Sandika!
        $rank = SandikaUserRank::addXp($userId, 15);

        return response()->json([
            'status' => 'success',
            'message' => "Riddle solved correctly! Cipher decrypted. +15 CP awarded to your Sandika Agent rank ({$rank->rank_title})!",
            'rank' => $rank,
        ]);
    }
}
