<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SandikaUserRank extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'xp',
        'rank_title',
        'level',
        'voice_logs_analyzed',
        'files_processed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function addXp($userId, $points)
    {
        $rank = self::firstOrCreate(
            ['user_id' => $userId],
            ['xp' => 0, 'rank_title' => 'Novice Operative', 'level' => 1]
        );

        $rank->xp += $points;

        // Level calculation
        $newLevel = floor($rank->xp / 100) + 1;
        $rank->level = $newLevel;

        // Rank titles
        if ($rank->xp >= 1000) {
            $rank->rank_title = 'Arkham Overseer';
        } elseif ($rank->xp >= 500) {
            $rank->rank_title = 'Mastermind Strategist';
        } elseif ($rank->xp >= 250) {
            $rank->rank_title = 'Commander Agent';
        } elseif ($rank->xp >= 100) {
            $rank->rank_title = 'Field Operative';
        } else {
            $rank->rank_title = 'Novice Operative';
        }

        $rank->save();
        return $rank;
    }
}
