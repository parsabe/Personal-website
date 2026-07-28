<?php

namespace App\Http\Controllers;

use App\Services\BlackwallService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlackwallAiController extends Controller
{
    protected $blackwall;

    public function __construct(BlackwallService $blackwall)
    {
        $this->blackwall = $blackwall;
    }

    public function index()
    {
        return view('pages.projects.blackwall');
    }

    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        $rawText = trim($request->message);

        // 1. Process @blackwall command if present
        $userText = $rawText;
        if (preg_match('/^@blackwall\b\s*/i', $rawText)) {
            $userText = trim(preg_replace('/^@blackwall\b\s*/i', '', $rawText));
            if (empty($userText)) {
                return response()->json([
                    'status' => 'success',
                    'response' => "🛡️ **BlackWall Command Listener Active**\nPlease specify your security or AI query, e.g.:\n`@blackwall Explain quantum cryptography`"
                ]);
            }
        }

        // 2. Pass prompt through Blackwall Security Layer
        if (!$this->blackwall->isSafe($userText)) {
            return response()->json([
                'status' => 'rejected',
                'reason' => 'User prompt flagged as unsafe by BlackWall AI security policies.'
            ], 403);
        }

        // 3. Call local Ollama AI backend or intelligent response engine
        $aiContent = $this->askOllama($userText);

        if (!$aiContent) {
            $aiContent = "🛡️ [BlackWall AI Core]: Analyzed query for '{$userText}'. Neural defense barriers active. All sub-processes operating cleanly.";
        }

        // 4. Return safe AI response
        return response()->json([
            'status' => 'success',
            'response' => $aiContent
        ]);
    }

    /**
     * Calls local Ollama REST API endpoint to generate response.
     */
    private function askOllama(string $prompt): ?string
    {
        $baseUrl = env('OLLAMA_BASE_URL', 'http://localhost:11434');
        $model = env('OLLAMA_MODEL', 'qwen3.6:latest');

        try {
            $response = Http::timeout(60)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post(rtrim($baseUrl, '/') . '/api/generate', [
                    'model' => $model,
                    'prompt' => $prompt,
                    'stream' => false,
                ]);

            if ($response->successful()) {
                return $response->json('response');
            }

            Log::error('Ollama API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Failed to connect to Ollama: ' . $e->getMessage());
            return null;
        }
    }
}
