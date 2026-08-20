<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SessionReportController extends Controller
{
    /**
     * Get active telemetry sessions.
     */
    public function index()
    {
        $sessions = [
            [
                'session_id' => 'AQ-SESS-2026-0820-001',
                'title' => 'Oceanic Rift Benthic Transect #4',
                'sensor_mode' => '6D EnKF + Optical YOLOv8x',
                'duration' => '04:12:45',
                'specimens_logged' => 1482,
                'shannon_index' => 2.418,
                'status' => 'COMPLETED',
                'timestamp' => now()->subHours(2)->toIso8601String(),
            ],
            [
                'session_id' => 'AQ-SESS-2026-0820-002',
                'title' => 'Stochastic Lotka-Volterra EnKF Real-Time Run',
                'sensor_mode' => 'Neural SDE + Stratonovich Integration',
                'duration' => '01:45:10',
                'specimens_logged' => 842,
                'shannon_index' => 2.104,
                'status' => 'RECORDING',
                'timestamp' => now()->toIso8601String(),
            ],
        ];

        return response()->json([
            'status' => 'success',
            'sessions' => $sessions,
        ]);
    }

    /**
     * Export session report as LaTeX / HTML printable PDF document.
     */
    public function exportPdf(Request $request)
    {
        $sessionId = $request->input('session_id', 'AQ-SESS-2026-0820-001');

        $reportHtml = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>AquaPulse AI Telemetry Session Report - {$sessionId}</title>
            <style>
                body { font-family: 'Courier New', monospace; background: #0f172a; color: #e2e8f0; padding: 40px; }
                h1 { color: #06b6d4; border-bottom: 2px solid #06b6d4; padding-bottom: 10px; }
                .badge { background: #06b6d4; color: #0f172a; padding: 4px 8px; font-weight: bold; border-radius: 4px; }
                .section { background: rgba(30, 41, 59, 0.8); border: 1px solid #334155; padding: 20px; border-radius: 8px; margin-top: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                th, td { border: 1px solid #475569; padding: 8px 12px; text-align: left; }
                th { background: #1e293b; color: #f4d03f; }
                .latex { font-family: serif; font-style: italic; color: #38bdf8; }
            </style>
        </head>
        <body>
            <h1>AQUAPULSE AI TELEMETRY SYSTEM REPORT</h1>
            <p><strong>SESSION ID:</strong> {$sessionId} | <span class='badge'>CLASSIFIED ECOLOGICAL REPORT</span></p>
            <p><strong>TIMESTAMP:</strong> " . date('Y-m-d H:i:s UTC') . "</p>

            <div class='section'>
                <h2>1. STOCHASTIC ENKF LOTKA-VOLTERRA FORMULATION</h2>
                <p>The system integrates 100-member Ensemble Kalman Filtering with Stratonovich Stochastic Differential Equations:</p>
                <div class='latex'>
                    dX = (&alpha; X - &beta; X Y) dt + &sigma;_1 X dW_1<br>
                    dY = (&delta; X Y - &gamma; Y) dt + &sigma;_2 Y dW_2
                </div>
                <p><strong>Prey Population (X):</strong> 54.21 &plusmn; 2.14 | <strong>Predator Population (Y):</strong> 24.88 &plusmn; 1.08</p>
                <p><strong>Extinction Probability P(ext):</strong> 4.2% [SAFE OPERATING ZONE]</p>
            </div>

            <div class='section'>
                <h2>2. BIODIVERSITY CENSUS & SHANNON DIVERSITY METRICS</h2>
                <p><strong>Shannon Index (H'):</strong> 2.418 | <strong>Pielou Evenness (J'):</strong> 0.867</p>
                <table>
                    <tr><th>Taxon</th><th>Common Name</th><th>Count</th><th>Diversity Weight</th></tr>
                    <tr><td>Thunnus albacares</td><td>Yellowfin Tuna</td><td>482</td><td>32.5%</td></tr>
                    <tr><td>Delphinus delphis</td><td>Common Dolphin</td><td>124</td><td>18.4%</td></tr>
                    <tr><td>Carcharodon carcharias</td><td>Great White Shark</td><td>18</td><td>8.2%</td></tr>
                    <tr><td>Chelonia mydas</td><td>Green Sea Turtle</td><td>64</td><td>14.2%</td></tr>
                    <tr><td>Aurelia aurita</td><td>Moon Jellyfish</td><td>794</td><td>26.7%</td></tr>
                </table>
            </div>

            <div class='section'>
                <h2>3. NEURAL MODULE DIAGNOSTICS</h2>
                <p>&bull; GMM Optical Segmentation: 99.1% Acc | Latency: 4.2 ms</p>
                <p>&bull; BNN Epistemic Uncertainty: &sigma;&sup2; = 0.014 | Latency: 6.8 ms</p>
                <p>&bull; YOLOv8x-Marine BotSORT Tracker: 60 FPS Target Lock Active</p>
            </div>

            <script>window.print();</script>
        </body>
        </html>";

        return response($reportHtml, 200)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="AquaPulse_Telemetry_Report_' . $sessionId . '.html"');
    }
}
