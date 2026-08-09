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

        // 3. Call local Ollama AI backend
        $aiContent = $this->askOllama($userText);

        if (!$aiContent) {
            $aiContent = "🛡️ **[BlackWall AI Core]**: I am operational. Query received: \"{$userText}\". Neural defense matrix active.";
        }

        // 4. Return safe AI response
        return response()->json([
            'status' => 'success',
            'response' => $aiContent
        ]);
    }

    /**
     * Calls local Ollama REST API endpoint to generate response using qwen2.5:0.5b or active model.
     */
    private function askOllama(string $prompt): ?string
    {
        $baseUrl = env('OLLAMA_BASE_URL', 'http://localhost:11434');
        $modelsToTry = [
            env('OLLAMA_MODEL', 'qwen2.5:0.5b'),
            'qwen2.5:0.5b',
            'qwen3.6:latest',
        ];

        $systemPrompt = "You are BlackWall AI Core, a high-tech AI neural defense matrix and intelligent assistant created by Parsa Besharat. Answer questions clearly, accurately, concisely, and politely. Use clean Markdown formatting.";
        $fullPrompt = "System: {$systemPrompt}\nUser: {$prompt}\nBlackWall AI:";

        foreach (array_unique($modelsToTry) as $model) {
            try {
                $response = Http::timeout(30)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post(rtrim($baseUrl, '/') . '/api/generate', [
                        'model' => $model,
                        'prompt' => $fullPrompt,
                        'stream' => false,
                    ]);

                if ($response->successful()) {
                    $responseText = trim($response->json('response'));
                    if (!empty($responseText)) {
                        return "🛡️ **[BlackWall AI Core]**\n\n" . $responseText;
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Ollama call failed for model {$model}: " . $e->getMessage());
            }
        }

        return null;
    }
}
