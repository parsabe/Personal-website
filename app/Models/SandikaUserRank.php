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

    /**
     * Add Contribution Points (CP) and update Sandika Rank tier.
     */
    public static function addXp($userId, $points)
    {
        $rank = self::firstOrCreate(
            ['user_id' => $userId],
            ['xp' => 0, 'rank_title' => 'Rookie', 'level' => 1]
        );

        $rank->xp += $points;
        $cp = $rank->xp;

        // Sandika Rank Tier Rules
        if ($cp >= 2000) {
            $rank->rank_title = 'Bossman 👑';
            $rank->level = 7;
        } elseif ($cp >= 4000) {
            $rank->rank_title = 'Admiral ⚓';
            $rank->level = 6;
        } elseif ($cp >= 150) {
            $rank->rank_title = 'Lieutenant 🎖️';
            $rank->level = 5;
        } elseif ($cp >= 100) {
            $rank->rank_title = 'Sergeant 🎖️';
            $rank->level = 4;
        } elseif ($cp >= 50) {
            $rank->rank_title = 'Captain ⚔️ (Verified)';
            $rank->level = 3;
        } elseif ($cp >= 20) {
            $rank->rank_title = 'Soldier 🛡️';
            $rank->level = 2;
        } else {
            $rank->rank_title = 'Rookie 🔰';
            $rank->level = 1;
        }

        $rank->save();
        return $rank;
    }
}
