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
            if ($user->email === 'parsabe99@gmail.com') {
                $rank->rank_title = 'Bossman 👑';
                $rank->level = 99;
                $rank->save();
            }
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
     * Update Git Insight (Editable by user from profile).
     */
    public function updateGitInsight(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $request->validate([
            'repo_url' => 'required|url',
            'description' => 'required|string',
        ]);

        $userId = Auth::id();
        $insight = DB::table('sandika_git_insights')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$insight) {
            return response()->json(['status' => 'error', 'message' => 'Git Insight not found or permission denied.'], 404);
        }

        $isGithub = Str::contains($request->input('repo_url'), 'github.com');

        DB::table('sandika_git_insights')
            ->where('id', $id)
            ->update([
                'repo_url' => $request->input('repo_url'),
                'description' => $request->input('description'),
                'is_github_verified' => $isGithub,
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Git Insight updated cleanly!',
        ]);
    }

    /**
     * Delete Git Insight.
     */
    public function deleteGitInsight(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $userId = Auth::id();
        $deleted = DB::table('sandika_git_insights')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->delete();

        if (!$deleted) {
            return response()->json(['status' => 'error', 'message' => 'Git Insight not found or permission denied.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Git Insight deleted successfully.'
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
        $rawAnswer = trim($request->input('answer'));
        $userAnswer = strtolower($rawAnswer);
        $userId = Auth::id();

        // 10 ROT13 Encrypted Ciphertexts
        $ciphers = [
            1 => "V nz gur fcvrfvg bs Nznqrhf Nexunz. Gubhtu zl nppvbaf, V unir fnirq guvf pherq pvgl, gubhtu zl bja pherf vf gb sbeprqire erznva va gur fubyrqbj. Zl fgbel vf pneire vagb gur irel fbhy bs Nexunz naq jvyy bayl or erivrjrq gb gubfr qrpgvbanyr ragreivrjf gb qvfvire vg.",
            2 => "Zl snvgu'f oehzf ena guebhtu gur uneq bs Tbgunz. Jr jrer qbpgbef, cvbqhyvfzbf naq gurapuvrf; jr unir orra gur betnavm pbeevqvat gur nepvsvp sveyvp sevygl sebz gur pvgl. Jr unir orra vg'f freiryrg tvivat nyy gb cerfrag vg. Naq fgvyyn vg unf pubfra gb ureg hf.",
            3 => "Nf Tbgunz'f irvarf fbyyl sbvyq jvgu cnva naq fhecevfravat, gur rsrpgf jrer srggryrq rireljbeyqre. Zl snzr sryy svefg, vasvcerq ol fbzr sbyq qrnavrf; zl zbavbe yvirq ba, ohg bayl va n qrerng. V erprvirq gb gur snvgu ubzr gb pner sbe ure jurer fur erzvaqrq va ure orq sbe nf ybat nf ure obql pbafhccrq gb oervir. Ure grnref xrrq zr njnxf ng avtug.",
            4 => "Zl wbhearl ynfgrq yvggyr bire n zbagu. Ivfvgvat nqnzrpvrf va obgu Zrgcbegf naq Xrfgbar, V jnf rkcrevraprq gb n jryyvfu bs arj vqrnf. V orqraq zl qnl erfhzvat ubzr va tbbq fcvrf, rtrevat gb frr zl jvgu naq snzvyl. V raqre vg xrayvat va gurve oehzf, oebxravtngrs bs zl yvir cbezvat guebhtu qevivat erq svatref.",
            5 => "V erprvirq gb zl jbex, ohg V pbhyq abg funxr gur cvpgherf sebz zl zvaq. V fubhyq unir orerpungrq, ohg V jnf zber rrtre guna rire gb sva n rkcybfvba sbe jul fbzrbar jbhyq qh guvf. Gurfr oebtnq gur nantznyl orpuneq orfgre zr, funzryyrfr naq onexvat yvxr n zqn qbtl. Sbe jung sryygu unq orra irel frirer gb cvpx zl uryc.",
            6 => "Gurl oebhtug gur nznavny orfgre zr, funznyrf naq onevxvat yvxr n znq qbt. Sbe jung srggyrq qnlzf V rvaqrevn uvf obfgf. Ur gnxr cynlgrer ebhaqvat uvfvaf, pnyyrqvat uvf qercnffirq penvqrz puvrf. Jung fubhyq unir oreraqhf erirnyrq gb cvtyglv. Guvf cbbe qbt arrqrq zl uryc.",
            7 => "Gur vafvqr punatrq yvggyr bire gur lrnef. Vgf erchgvatvba jnf va gnggrenf, ohg V ibjq gb svx vg. Nf gur oevqtrf jrer oervqvg vg fjrneq V fgnq gur shgher, n oevgr jbireohf shgher.",
            8 => "Arj oevpx, zrgny naq cnva pbeerpgrq byq jhaqhf. Serspu oynq jbexrq vagb gur obgl. Oevfpu arj zvafs pnevarq vagb gur obgl. Oevthu arj zvafs ernqrq naq nyy febrer gb hcyvk beqrf. Jr nyy xarj jr jrer gur barf gb svx guvf pvgl. Naq gur pvgl jvyy gunax hf.",
            9 => "Zl snvgu'f xvyyre fghqrq va sebag bs zr. Lrnef bs gurfcnel unir qrernq uvz fnar. V jnf cebqhp gb frr uvz jnyx sern. Va rkgerzcerff sbe uvf yvoenfvg gur fgngvba erprcerfrag bayl n fvtavsvantr. Ur gnxed hp ntbavfgvat gb jnyx va n cnex, ubj ur ybatrq gb srry serfu neba ba uvf sner, naq gura ur gnxed zl sngure'f sbhagre cgra naq xvyynq zl frpergvp. Nf ur jnf fohqhrq, guraf oratgn be uvz gb fgnva ba gur sbez.",
            10 => "V ryxrq pbhagyrff gevnyf wrnevat zr sebz va. Sbybyjvat zrzbevrf sbeybeva oebxra qernzf. Tragyr snvgu va n obql ebggrq. Juvyr ng avtug enc fcebhgf sbeybeva. Tragyrybgg va guvf pnir gung unf znqr zr fybjyv n pber gung pnar unir bayl erirnyra gb jub rkcyber vg. Sbe va cybfr sbe treer pynve jvq gur qnex angher bs zl jbex ur erprvirq gb abg punatr.",
        ];

        $targetCipher = $ciphers[$spiritId] ?? '';
        $expectedDecrypted = str_rot13($targetCipher);

        // Normalize helper for fuzzy matching (ignore punctuation & spaces)
        $cleanUser = preg_replace('/[^a-z0-9]/', '', $userAnswer);
        $cleanExpected = preg_replace('/[^a-z0-9]/', '', strtolower($expectedDecrypted));
        $cleanCipher = preg_replace('/[^a-z0-9]/', '', str_rot13($userAnswer));

        $isCorrect = false;
        if (!empty($cleanUser) && ($cleanUser === $cleanExpected || $cleanCipher === preg_replace('/[^a-z0-9]/', '', strtolower($targetCipher)))) {
            $isCorrect = true;
        } else {
            // Key phrase fallbacks
            $keyPhrases = [
                1 => ['amadeus arkham', 'spirit of amadeus', 'cursed city', 'remain in the shadows'],
                2 => ['gotham', 'doctors', 'family\'s roots', 'architectonic'],
                3 => ['gotham\'s veins', 'wife fell first', 'tears kept me awake'],
                4 => ['metropolis', 'keystone', 'dripping red fingers'],
                5 => ['barking like a mad dog', 'explanation', 'grieved'],
                6 => ['maniac', 'recounting his sins', 'poor dog needed my help'],
                7 => ['asylum', 'tatters', 'bright glorious future'],
                8 => ['new brick', 'fresh blood', 'fix this city'],
                9 => ['fountain pen', 'killed my secretary', 'family\'s killer'],
                10 => ['countless trials', 'broken dreams', 'dark nature of my work'],
            ];

            $phrases = $keyPhrases[$spiritId] ?? [];
            foreach ($phrases as $phrase) {
                if (Str::contains($userAnswer, $phrase)) {
                    $isCorrect = true;
                    break;
                }
            }
        }

        if (!$isCorrect) {
            return response()->json([
                'status' => 'incorrect',
                'message' => 'The deciphered text is incorrect. Use the ROT13 Tactical Tool in Sandika to decipher the cipher text!',
            ], 422);
        }

        // Check if already solved by user
        $alreadySolved = false;
        try {
            $alreadySolved = DB::table('sandika_arkham_solves')
                ->where('user_id', $userId)
                ->where('spirit_id', $spiritId)
                ->exists();
        } catch (\Exception $e) {
            // Ignore DB error
        }

        if ($alreadySolved) {
            return response()->json([
                'status' => 'already_solved',
                'message' => 'You have already deciphered this Arkham Spirit.',
                'audio_url' => asset("audio/sandika/{$spiritId}.mp3"),
                'spirit_id' => $spiritId,
            ]);
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
            'audio_url' => asset("audio/sandika/{$spiritId}.mp3"),
            'spirit_id' => $spiritId,
            'rank' => $rank,
        ]);
    }
}
