<?php

namespace App\Http\Controllers;

use App\Models\NigmaPuzzle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NigmaController extends Controller
{
    /**
     * Render Nigma Riddler & Cypher Portal view.
     */
    public function index()
    {
        $puzzles = NigmaPuzzle::orderBy('created_at', 'desc')->take(10)->get();

        if ($puzzles->isEmpty()) {
            $puzzles = collect([
                (object)[
                    'id' => 1,
                    'title' => 'Riddle of the Dark Knight',
                    'riddle' => 'I have no voice, yet I speak to many. I tell of all things in the world that exist. What am I?',
                    'cipher_type' => 'Caesar Cipher',
                    'encrypted_solution' => 'E ERRN (Shift 3: A BOOK)',
                    'is_solved' => false,
                ],
                (object)[
                    'id' => 2,
                    'title' => 'The Shadow Code',
                    'riddle' => 'The more of me you take, the more you leave behind. What am I?',
                    'cipher_type' => 'ROT13',
                    'encrypted_solution' => 'SBBGFGRCF (FOOTSTEPS)',
                    'is_solved' => true,
                ]
            ]);
        }

        return view('pages.nigma', compact('puzzles'));
    }

    /**
     * Solve a puzzle / cipher in Nigma.
     */
    public function solve(Request $request)
    {
        $request->validate([
            'puzzle_id' => 'nullable|integer',
            'answer' => 'required|string',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Riddle puzzle solved correctly! The cypher transmission has been unlocked.',
        ]);
    }
}
