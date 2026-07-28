<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlackwallService
{
    protected string $url;

    public function __construct()
    {
        $baseUrl = env('BLACKWALL_API_URL', 'http://127.0.0.1:8002');
        $this->url = rtrim($baseUrl, '/') . '/analyze';
    }

    public function isSafe(string $text): bool
    {
        try {
            $response = Http::timeout(30)->post($this->url, [
                'text' => $text
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['class_id']) && $data['class_id'] === 0;
            }

            Log::error('Blackwall API Error: ' . $response->status());
            return true; // Fallback allow if API is non-blocking in production environment

        } catch (\Exception $e) {
            Log::error('Blackwall Connection Exception: ' . $e->getMessage());
            return true; // Fallback allow with warning
        }
    }
}
