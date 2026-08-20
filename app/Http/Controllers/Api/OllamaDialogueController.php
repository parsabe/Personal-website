<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OllamaDialogueController extends Controller
{
    /**
     * Handle AI Dialogue generation with Ollama LLM API (llama3) for Dr. Pauly & Johnny Silverhand.
     */
    public function generate(Request $request)
    {
        $userPrompt = trim($request->input('prompt', 'Give me a telemetry status report on species abundance and Lotka-Volterra EnKF dynamics.'));
        $persona = $request->input('persona', 'dr_pauly'); // 'dr_pauly' or 'johnny_silverhand'
        $lang = $request->input('lang', 'EN');

        if ($persona === 'johnny_silverhand') {
            $systemPrompt = "You are Johnny Silverhand from Cyberpunk 2077. You are a legendary rockerboy samurai rebel. You speak with high energy, cyberpunk slang, and talk about saving the ocean ecosystem from corporate pollution. Always keep it punchy under 60 words. User message: " . $userPrompt;
        } else {
            // Dr. Pauly Persona
            $systemPrompt = "You are Dr. Paul (Dr. Pauly), Chief Marine Ecologist and Lead AI Scientist of the AquaPulse Telemetry System. You provide brilliant, scientific, and authoritative commentary on stochastic 6D EnKF Lotka-Volterra prey-predator stability, Shannon diversity metrics, and marine conservation. Keep your response under 60 words. User question: " . $userPrompt;
        }

        try {
            $response = Http::timeout(8)->post('http://localhost:11434/api/generate', [
                'model' => 'llama3:latest',
                'prompt' => $systemPrompt,
                'stream' => false,
            ]);

            if ($response->successful()) {
                $text = $response->json('response');
                if (!empty($text)) {
                    return response()->json([
                        'status' => 'success',
                        'persona' => $persona,
                        'model' => 'llama3:latest',
                        'response' => trim($text),
                        'source' => 'ollama_local',
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Fallback response if Ollama is busy
        }

        // Fallback intelligent responses if Ollama API timeout
        if ($persona === 'johnny_silverhand') {
            $fallbackText = "Wake up, Samurai! AquaPulse Neural SDE is running hot. The EnKF Lotka-Volterra matrix shows 98.6% predator stability. Let's save this damn ocean before the megacorps drain it!";
        } else {
            $fallbackText = "Greetings. I am Dr. Pauly. Our 6D Ensemble Kalman Filter indicates a stable Lotka-Volterra phase-space equilibrium. Shannon Diversity Index H' stands optimal at 2.418 with high species richness.";
        }

        return response()->json([
            'status' => 'success',
            'persona' => $persona,
            'model' => 'fallback_engine',
            'response' => $fallbackText,
            'source' => 'local_fallback',
        ]);
    }
}
