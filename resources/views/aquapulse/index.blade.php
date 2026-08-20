<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AquaPulse AI | Marine Vision & Ecological Telemetry System</title>
    <meta name="description" content="Production-grade AquaPulse AI Marine Vision & Ecological Telemetry System with 6D EnKF Lotka-Volterra Engine, YOLOv8 tracking, GBIF taxonomy, Ollama LLM Dr. Pauly AI dialogue, and Cyberpunk HUD integration.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;800&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- External TailWind CSS & Chart.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind Cyber Colors Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        cyber: {
                            navy: '#0F172A',
                            darkCard: 'rgba(15, 23, 42, 0.85)',
                            cyan: '#06B6D4',
                            gold: '#F4D03F',
                            red: '#EF4444',
                            emerald: '#10B981',
                            purple: '#A855F7',
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- Separate External CSS Stylesheet -->
    <link rel="stylesheet" href="/css/aquapulse.css">

    <!-- Separate ESM Main Entry Script Module -->
    <script type="module" src="/js/aquapulse/main.js"></script>
</head>
<body class="min-h-screen flex flex-col relative selection:bg-cyber-cyan selection:text-slate-900">

    <!-- CRT Scanlines Background Layer -->
    <div class="fixed inset-0 scanlines z-40"></div>

    <!-- Header Telemetry Bar -->
    <header class="sticky top-0 z-30 glass-panel border-b border-slate-700/80 px-4 lg:px-8 py-3 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyber-cyan to-blue-600 flex items-center justify-center text-slate-900 font-extrabold text-xl shadow-lg pulse-cyan">
                <i class="fa-solid fa-water"></i>
            </div>
            <div>
                <h1 class="text-lg lg:text-xl font-black tracking-wider text-white flex items-center gap-2">
                    <span>AQUAPULSE AI</span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-cyan-500/20 text-cyber-cyan border border-cyan-500/40 font-mono">v11.4 PROD</span>
                </h1>
                <p class="text-xs text-slate-400 font-mono hidden sm:block">Marine Vision, EnKF Telemetry & Dr. Pauly Ollama AI</p>
            </div>
        </div>

        <!-- System Controls & Johnny Silverhand Trigger -->
        <div class="flex items-center gap-2 sm:gap-3">
            <span class="hidden md:flex items-center gap-2 text-xs font-mono bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 px-3 py-1.5 rounded-lg">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>OLLAMA LLM ONLINE</span>
            </span>

            <!-- Johnny Silverhand Cyberpunk Button -->
            <button onclick="triggerJohnnyGlitch()" class="px-3.5 py-1.5 bg-gradient-to-r from-red-600 to-amber-600 hover:from-red-500 hover:to-amber-500 text-white rounded-lg font-mono text-xs font-bold shadow-lg flex items-center gap-2 border border-red-400/40 transition-all transform hover:scale-105" title="Shortcut Key: J">
                <i class="fa-solid fa-skull-crossbones text-cyber-gold animate-bounce"></i>
                <span class="hidden sm:inline">JOHNNY OVERRIDE</span>
                <span class="text-[10px] bg-black/40 px-1 rounded text-cyber-gold">KEY J</span>
            </button>

            <!-- GBIF Taxonomy Modal Trigger -->
            <button onclick="openGbifModal()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-cyber-cyan rounded-lg font-mono text-xs font-semibold border border-cyan-500/30 flex items-center gap-1.5">
                <i class="fa-solid fa-dna"></i>
                <span class="hidden md:inline">GBIF Taxonomy</span>
            </button>

            <!-- PDF Exporter -->
            <a href="/api/v1/export-pdf" target="_blank" class="px-3 py-1.5 bg-cyber-cyan hover:bg-cyan-400 text-slate-950 rounded-lg font-mono text-xs font-bold flex items-center gap-1.5 shadow-md">
                <i class="fa-solid fa-file-pdf"></i>
                <span class="hidden lg:inline">Export PDF</span>
            </a>

            <!-- Theme Switcher (Key T) -->
            <button onclick="toggleTheme()" class="w-9 h-9 bg-slate-800 hover:bg-slate-700 text-amber-400 rounded-lg border border-slate-700 flex items-center justify-center" title="Toggle Theme (Shortcut Key: T)">
                <i class="fa-solid fa-sun-moon"></i>
            </button>

            <!-- Back to Home -->
            <a href="/" class="w-9 h-9 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg border border-slate-700 flex items-center justify-center" title="Return Home">
                <i class="fa-solid fa-house"></i>
            </a>
        </div>
    </header>

    <!-- Main Telemetry Dashboard Content -->
    <main class="flex-1 container mx-auto px-4 lg:px-6 py-6 space-y-6">

        <!-- Banner Metrics Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="glass-panel p-4 rounded-2xl flex items-center justify-between border-l-4 border-cyber-cyan">
                <div>
                    <span class="text-xs text-slate-400 font-mono block mb-1">STOCHASTIC PREY (X)</span>
                    <span id="statPreyX" class="text-2xl font-black text-cyber-cyan font-mono">54.21</span>
                </div>
                <div class="text-cyber-cyan text-xl"><i class="fa-solid fa-fish"></i></div>
            </div>
            <div class="glass-panel p-4 rounded-2xl flex items-center justify-between border-l-4 border-cyber-gold">
                <div>
                    <span class="text-xs text-slate-400 font-mono block mb-1">PREDATOR DYNAMICS (Y)</span>
                    <span id="statPredatorY" class="text-2xl font-black text-cyber-gold font-mono">24.88</span>
                </div>
                <div class="text-cyber-gold text-xl"><i class="fa-solid fa-virus"></i></div>
            </div>
            <div class="glass-panel p-4 rounded-2xl flex items-center justify-between border-l-4 border-emerald-500">
                <div>
                    <span class="text-xs text-slate-400 font-mono block mb-1">SHANNON INDEX (H')</span>
                    <span id="statShannon" class="text-2xl font-black text-emerald-400 font-mono">2.418</span>
                </div>
                <div class="text-emerald-400 text-xl"><i class="fa-solid fa-chart-line"></i></div>
            </div>
            <div class="glass-panel p-4 rounded-2xl flex items-center justify-between border-l-4 border-cyber-red">
                <div>
                    <span class="text-xs text-slate-400 font-mono block mb-1">EXTINCTION RISK %</span>
                    <span id="statRisk" class="text-2xl font-black text-cyber-red font-mono">4.2%</span>
                </div>
                <div class="text-cyber-red text-xl"><i class="fa-solid fa-shield-cat"></i></div>
            </div>
        </div>

        <!-- 4-PANE TELEMETRY GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- PANE 1: Live Marine Vision Stream & YOLOv8 Reticles (8 Cols) -->
            <div class="lg:col-span-7 glass-panel glass-panel-glow rounded-3xl p-5 flex flex-col justify-between relative overflow-hidden">
                <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-700/80">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></span>
                        <h2 class="font-bold text-white text-base font-mono">PANE 1: MARINE OPTICAL TELEMETRY STREAM</h2>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-mono">
                        <span class="bg-cyan-500/10 text-cyber-cyan border border-cyan-500/30 px-2.5 py-1 rounded">YOLOv8x-Marine 98.6%</span>
                        <span class="bg-slate-800 text-slate-300 px-2.5 py-1 rounded border border-slate-700">BotSORT ID #402</span>
                    </div>
                </div>

                <!-- Live Vision Simulation Canvas -->
                <div class="relative w-full h-[380px] bg-slate-950 rounded-2xl overflow-hidden border border-slate-800 shadow-inner">
                    <canvas id="visionCanvas" class="w-full h-full block"></canvas>

                    <!-- HUD Overlay Telemetry Info -->
                    <div class="absolute top-3 left-3 bg-black/70 backdrop-blur-md p-3 rounded-xl border border-slate-700/80 text-[11px] font-mono space-y-1">
                        <div class="text-cyber-cyan flex items-center gap-1.5">
                            <i class="fa-solid fa-compass"></i> LAT: 24.5201° N | LON: 54.3674° E
                        </div>
                        <div class="text-slate-300">DEPTH: 142.8 m | TEMP: 18.4 °C</div>
                        <div class="text-amber-400">TURBIDITY: 0.14 NTU | SALINITY: 35.2 PSU</div>
                    </div>

                    <!-- HUD Mode Toggles -->
                    <div class="absolute bottom-3 right-3 flex items-center gap-2 bg-black/70 backdrop-blur-md p-2 rounded-xl border border-slate-700/80 text-xs font-mono">
                        <button onclick="toggleReticles()" id="btnReticle" class="px-2.5 py-1 rounded bg-cyber-cyan text-slate-950 font-bold">Reticles: ON</button>
                        <button onclick="toggleSonar()" id="btnSonar" class="px-2.5 py-1 rounded bg-slate-800 text-slate-300">Sonar Filter</button>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between text-xs font-mono text-slate-400">
                    <span>Frame: <strong class="text-white" id="frameCount">0</strong> | 60 FPS</span>
                    <span>Specimens Tracked: <strong class="text-cyber-cyan">5 Species Active</strong></span>
                    <span>EnKF Vector: <strong class="text-emerald-400">NORMALIZED</strong></span>
                </div>
            </div>

            <!-- PANE 2: 6D EnKF & Lotka-Volterra Real-Time Live Chart (5 Cols) -->
            <div class="lg:col-span-5 glass-panel rounded-3xl p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-700/80">
                    <h2 class="font-bold text-white text-base font-mono flex items-center gap-2">
                        <i class="fa-solid fa-chart-area text-cyber-gold"></i>
                        <span>PANE 2: 6D EnKF STOCHASTIC ENGINE</span>
                    </h2>
                    <span class="text-xs font-mono text-cyber-gold bg-amber-500/10 border border-amber-500/30 px-2 py-0.5 rounded">100-MEMBER SPREAD</span>
                </div>

                <!-- Differential Formula Card -->
                <div class="bg-slate-900/90 p-3 rounded-xl border border-slate-800 font-mono text-xs text-slate-300 space-y-1 mb-3">
                    <div class="text-cyber-cyan">dX/dt = &alpha;X - &beta;XY + &sigma;₁X dW₁ &nbsp;(Prey X = <span id="preyVal">54.2</span>)</div>
                    <div class="text-cyber-gold">dY/dt = &delta;XY - &gamma;Y + &sigma;₂Y dW₂ &nbsp;(Predator Y = <span id="predVal">24.8</span>)</div>
                </div>

                <!-- Live Chart Canvas -->
                <div class="w-full h-[280px] bg-slate-950/80 p-2 rounded-2xl border border-slate-800">
                    <canvas id="telemetryChart"></canvas>
                </div>
            </div>

        </div>

        <!-- OLLAMA AI DIALOGUE & VOICE SPEECH COMMS PANEL -->
        <div class="glass-panel glass-panel-glow rounded-3xl p-6 relative">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4 pb-3 border-b border-slate-700/80">
                <div>
                    <h2 class="font-black text-white text-lg font-mono flex items-center gap-2">
                        <i class="fa-solid fa-comments text-cyber-cyan"></i>
                        <span>DR. PAULY & JOHNNY OLLAMA AI VOICE COMMS</span>
                    </h2>
                    <span id="personaBadge" class="text-xs font-mono text-cyber-cyan font-bold">DR. PAULY (CHIEF ECOLOGIST)</span>
                </div>

                <!-- Persona Switcher Toggles -->
                <div class="flex items-center gap-2 bg-slate-900 p-1 rounded-xl border border-slate-800 text-xs font-mono">
                    <button onclick="setPersona('dr_pauly')" class="px-3 py-1.5 rounded-lg bg-cyber-cyan text-slate-950 font-bold">Dr. Pauly</button>
                    <button onclick="setPersona('johnny_silverhand')" class="px-3 py-1.5 rounded-lg bg-slate-800 text-cyber-gold hover:bg-slate-700">Johnny Silverhand</button>
                </div>
            </div>

            <!-- Dialogue Output Screen -->
            <div id="commsOutput" class="bg-slate-950/90 p-4 rounded-2xl border border-slate-800 font-mono text-xs leading-relaxed min-h-[90px] max-h-[160px] overflow-y-auto mb-4">
                <span class="text-slate-400">Dr. Pauly is online. Ask a question or use voice input to query the AquaPulse AI engine...</span>
            </div>

            <!-- Speech-to-Text & Input Controls -->
            <div class="flex items-center gap-2">
                <button onclick="toggleMicrophone()" id="btnMic" class="px-4 py-3 bg-slate-800 hover:bg-slate-700 text-cyber-gold rounded-xl font-mono text-xs font-bold border border-slate-700 flex items-center gap-2 shadow-md" title="Click to speak with your microphone">
                    <i class="fa-solid fa-microphone"></i>
                    <span>🎤 Voice</span>
                </button>

                <input type="text" id="commsInput" placeholder="Ask Dr. Pauly or Johnny about EnKF telemetry, Lotka-Volterra dynamics, or ocean conservation..." class="flex-1 bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-white font-mono outline-none focus:border-cyber-cyan" onkeydown="if(event.key === 'Enter') sendComms()">

                <button onclick="sendComms()" class="px-5 py-3 bg-cyber-cyan hover:bg-cyan-400 text-slate-950 font-bold rounded-xl font-mono text-xs shadow-md">
                    Send AI Query
                </button>
            </div>
        </div>

        <!-- SECOND ROW: PANE 3 (AI Module Matrix) & PANE 4 (Biodiversity & Monte Carlo) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- PANE 3: AI Module Status Matrix (7 Cols) -->
            <div class="lg:col-span-7 glass-panel rounded-3xl p-5">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-700/80">
                    <h2 class="font-bold text-white text-base font-mono flex items-center gap-2">
                        <i class="fa-solid fa-microchip text-cyber-cyan"></i>
                        <span>PANE 3: AI TELEMETRY TOOL MATRIX</span>
                    </h2>
                    <span class="text-xs font-mono text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded border border-emerald-500/30">8 MODULES ONLINE</span>
                </div>

                <!-- 8 Interactive AI Module Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    
                    <div onclick="inspectModule('GMM', 'Gaussian Mixture Model', '99.1% Acc', '4.2 ms')" class="bg-slate-900/80 hover:bg-slate-800 p-3 rounded-xl border border-slate-800 hover:border-cyber-cyan cursor-pointer transition-all">
                        <div class="flex items-center justify-between text-xs font-mono text-slate-400 mb-1">
                            <span>GMM</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        </div>
                        <h4 class="font-bold text-white text-xs mb-1">Optical Segmentation</h4>
                        <div class="text-[11px] font-mono text-cyber-cyan">99.1% Acc | 4.2ms</div>
                    </div>

                    <div onclick="inspectModule('BNN', 'Bayesian Neural Net', 'Uncertainty σ²=0.014', '6.8 ms')" class="bg-slate-900/80 hover:bg-slate-800 p-3 rounded-xl border border-slate-800 hover:border-cyber-gold cursor-pointer transition-all">
                        <div class="flex items-center justify-between text-xs font-mono text-slate-400 mb-1">
                            <span>BNN</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        </div>
                        <h4 class="font-bold text-white text-xs mb-1">Epistemic Uncertainty</h4>
                        <div class="text-[11px] font-mono text-cyber-gold">σ² = 0.014 | 6.8ms</div>
                    </div>

                    <div onclick="inspectModule('DANN', 'Domain Adversarial Net', 'Shift Loss 0.082', '5.1 ms')" class="bg-slate-900/80 hover:bg-slate-800 p-3 rounded-xl border border-slate-800 hover:border-emerald-400 cursor-pointer transition-all">
                        <div class="flex items-center justify-between text-xs font-mono text-slate-400 mb-1">
                            <span>DANN</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        </div>
                        <h4 class="font-bold text-white text-xs mb-1">Turbidity Invariance</h4>
                        <div class="text-[11px] font-mono text-emerald-400">Shift Loss 0.082</div>
                    </div>

                    <div onclick="inspectModule('6D EnKF', 'Ensemble Kalman Filter', '100-Member EnKF', '1.2 ms')" class="bg-slate-900/80 hover:bg-slate-800 p-3 rounded-xl border border-slate-800 hover:border-cyan-400 cursor-pointer transition-all">
                        <div class="flex items-center justify-between text-xs font-mono text-slate-400 mb-1">
                            <span>6D EnKF</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        </div>
                        <h4 class="font-bold text-white text-xs mb-1">State Covariance</h4>
                        <div class="text-[11px] font-mono text-cyan-400">100-Members | 1.2ms</div>
                    </div>

                    <div onclick="inspectModule('KDE', 'Kernel Density Estimator', 'Bandwidth h=0.42', '3.5 ms')" class="bg-slate-900/80 hover:bg-slate-800 p-3 rounded-xl border border-slate-800 hover:border-purple-400 cursor-pointer transition-all">
                        <div class="flex items-center justify-between text-xs font-mono text-slate-400 mb-1">
                            <span>KDE</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        </div>
                        <h4 class="font-bold text-white text-xs mb-1">Biomass Heatmaps</h4>
                        <div class="text-[11px] font-mono text-purple-400">h = 0.42 | 3.5ms</div>
                    </div>

                    <div onclick="inspectModule('SMC', 'Sequential Monte Carlo', '5000 Particles', '8.4 ms')" class="bg-slate-900/80 hover:bg-slate-800 p-3 rounded-xl border border-slate-800 hover:border-red-400 cursor-pointer transition-all">
                        <div class="flex items-center justify-between text-xs font-mono text-slate-400 mb-1">
                            <span>SMC</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        </div>
                        <h4 class="font-bold text-white text-xs mb-1">Particle Filtering</h4>
                        <div class="text-[11px] font-mono text-red-400">5,000 Particles</div>
                    </div>

                    <div onclick="inspectModule('Neural SDE', 'Stochastic Differential Eq', 'Stratonovich Mode', '9.1 ms')" class="bg-slate-900/80 hover:bg-slate-800 p-3 rounded-xl border border-slate-800 hover:border-cyber-gold cursor-pointer transition-all">
                        <div class="flex items-center justify-between text-xs font-mono text-slate-400 mb-1">
                            <span>Neural SDE</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        </div>
                        <h4 class="font-bold text-white text-xs mb-1">SDE Integration</h4>
                        <div class="text-[11px] font-mono text-cyber-gold">Stratonovich Mode</div>
                    </div>

                    <div onclick="inspectModule('Hydrodynamics', 'Navier-Stokes Hydro Engine', 'Flow 1.84 m/s', '2.9 ms')" class="bg-slate-900/80 hover:bg-slate-800 p-3 rounded-xl border border-slate-800 hover:border-cyber-cyan cursor-pointer transition-all">
                        <div class="flex items-center justify-between text-xs font-mono text-slate-400 mb-1">
                            <span>Hydrodynamics</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        </div>
                        <h4 class="font-bold text-white text-xs mb-1">Current Vectors</h4>
                        <div class="text-[11px] font-mono text-cyber-cyan">Flow 1.84 m/s</div>
                    </div>

                </div>
            </div>

            <!-- PANE 4: Extinction Risk Gauge & Biodiversity Metrics (5 Cols) -->
            <div class="lg:col-span-5 glass-panel rounded-3xl p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-700/80">
                    <h2 class="font-bold text-white text-base font-mono flex items-center gap-2">
                        <i class="fa-solid fa-leaf text-emerald-400"></i>
                        <span>PANE 4: BIODIVERSITY & ECOLOGICAL CENSUS</span>
                    </h2>
                </div>

                <!-- Shannon & Pielou Stats -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-slate-900/80 p-3 rounded-xl border border-slate-800 text-center">
                        <span class="text-xs font-mono text-slate-400 block mb-1">Shannon H' = -∑ pᵢ ln(pᵢ)</span>
                        <span class="text-xl font-bold text-cyber-cyan font-mono">2.418</span>
                        <span class="text-[10px] text-emerald-400 block mt-1">High Ecosystem Health</span>
                    </div>
                    <div class="bg-slate-900/80 p-3 rounded-xl border border-slate-800 text-center">
                        <span class="text-xs font-mono text-slate-400 block mb-1">Pielou J' = H' / ln(S)</span>
                        <span class="text-xl font-bold text-cyber-gold font-mono">0.867</span>
                        <span class="text-[10px] text-amber-400 block mt-1">Optimal Evenness</span>
                    </div>
                </div>

                <!-- Species Abundance Mini Table -->
                <div class="space-y-2 text-xs font-mono">
                    <div class="flex items-center justify-between bg-slate-900/60 px-3 py-2 rounded-lg border border-slate-800">
                        <span class="text-white">Thunnus albacares (Tuna)</span>
                        <span class="text-cyber-cyan font-bold">482 (32.5%)</span>
                    </div>
                    <div class="flex items-center justify-between bg-slate-900/60 px-3 py-2 rounded-lg border border-slate-800">
                        <span class="text-white">Aurelia aurita (Jellyfish)</span>
                        <span class="text-cyber-gold font-bold">794 (26.7%)</span>
                    </div>
                    <div class="flex items-center justify-between bg-slate-900/60 px-3 py-2 rounded-lg border border-slate-800">
                        <span class="text-white">Delphinus delphis (Dolphin)</span>
                        <span class="text-emerald-400 font-bold">124 (18.4%)</span>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <!-- Footer Status Bar -->
    <footer class="glass-panel border-t border-slate-700/80 px-6 py-4 text-center text-xs font-mono text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2">
        <span>AquaPulse AI Marine Vision Engine &copy; 2026 Parsa Saba. All Rights Reserved.</span>
        <div class="flex items-center gap-4 text-slate-300">
            <span>Press <kbd class="bg-slate-800 px-1.5 py-0.5 rounded text-cyber-gold border border-slate-700">T</kbd> Theme</span>
            <span>Press <kbd class="bg-slate-800 px-1.5 py-0.5 rounded text-cyber-red border border-slate-700">J</kbd> Johnny Glitch</span>
        </div>
    </footer>

    <!-- GBIF Taxonomy Explorer Modal -->
    <div id="gbifModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden items-center justify-center p-4">
        <div class="glass-panel glass-panel-glow w-full max-w-2xl rounded-3xl p-6 relative">
            <button onclick="closeGbifModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white text-lg"><i class="fa-solid fa-xmark"></i></button>
            <h3 class="text-lg font-bold text-cyber-cyan font-mono mb-4 flex items-center gap-2">
                <i class="fa-solid fa-dna"></i> GBIF TAXONOMY EXPEDITION PROXY (24-HR CACHED)
            </h3>

            <div class="flex gap-2 mb-4">
                <input type="text" id="gbifInput" value="Thunnus albacares" class="flex-1 bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white font-mono outline-none focus:border-cyber-cyan">
                <button onclick="searchGbif()" class="px-5 py-2.5 bg-cyber-cyan text-slate-950 font-bold rounded-xl font-mono text-sm hover:bg-cyan-400">Search GBIF</button>
            </div>

            <div id="gbifResults" class="bg-slate-900/90 p-4 rounded-2xl border border-slate-800 text-xs font-mono space-y-2 text-slate-300 max-h-72 overflow-y-auto">
                <div class="text-cyber-gold font-bold text-sm">Thunnus albacares (Bonnaterre, 1788)</div>
                <div>Kingdom: Animalia | Phylum: Chordata | Class: Actinopterygii</div>
                <div>Order: Scombriformes | Family: Scombridae | Genus: Thunnus</div>
                <div class="text-emerald-400 pt-2 border-t border-slate-800">Status: ACCEPTED SPECIES | Confidence: 98% Match</div>
            </div>
        </div>
    </div>

    <!-- AI Module Inspector Modal -->
    <div id="moduleModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden items-center justify-center p-4">
        <div class="glass-panel w-full max-w-lg rounded-3xl p-6 relative">
            <button onclick="closeModuleModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white text-lg"><i class="fa-solid fa-xmark"></i></button>
            <h3 id="modTitle" class="text-lg font-bold text-cyber-gold font-mono mb-2">MODULE INSPECTOR</h3>
            <p id="modSub" class="text-xs text-slate-400 font-mono mb-4">Hyperparameter Telemetry & Formulation</p>
            <div id="modBody" class="bg-slate-900/90 p-4 rounded-2xl border border-slate-800 text-xs font-mono text-slate-300 space-y-2">
            </div>
        </div>
    </div>

    <!-- JOHNNY SILVERHAND CYBERPUNK OVERLAY MODAL -->
    <div id="johnnyModal" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-xl hidden items-center justify-center p-4">
        <div class="glass-panel border-2 border-red-500/80 shadow-[0_0_50px_rgba(239,68,68,0.5)] w-full max-w-2xl rounded-3xl p-6 relative overflow-hidden text-center">
            
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-red-500/40 text-xs font-mono text-red-400">
                <span class="flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-yellow-400 animate-ping"></i> SAMURAI_NEURAL_OVERRIDE_V2.077</span>
                <span>RELIC_STOCHASTIC_MALFUNCTION</span>
            </div>

            <div class="w-48 h-48 mx-auto rounded-2xl overflow-hidden border-2 border-cyber-gold shadow-2xl mb-4 relative">
                <img src="/imgs/aquapulse/johnny.gif" alt="Johnny Silverhand Cyberpunk 2077" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-red-500/10 mix-blend-overlay"></div>
            </div>

            <h2 id="johnnyDialogue" class="text-xl sm:text-2xl font-black text-cyber-gold tracking-wide mb-2 font-mono drop-shadow-md">
                "Wake up, Samurai! We've got an ocean to save."
            </h2>

            <p class="text-xs sm:text-sm text-slate-300 font-mono max-w-md mx-auto mb-6 leading-relaxed bg-slate-950/80 p-3 rounded-xl border border-slate-800">
                Relic subroutine executed inside AquaPulse Neural SDE. Stochastic EnKF matrix hijacked by Silverhand Cyberpunk telemetry core.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-3 font-mono text-xs">
                <button onclick="johnnyAction('bypass')" class="px-5 py-2.5 bg-red-600 hover:bg-red-500 text-white font-bold rounded-xl shadow-lg border border-red-400/50">
                    [ BYPASS RELIC CORRECTION ]
                </button>
                <button onclick="johnnyAction('surge')" class="px-5 py-2.5 bg-cyber-gold hover:bg-amber-400 text-slate-950 font-extrabold rounded-xl shadow-lg">
                    [ SURGE NEURAL SDE ]
                </button>
                <button onclick="closeJohnnyModal()" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl border border-slate-700">
                    [ DISMISS OVERRIDE ]
                </button>
            </div>
        </div>
    </div>

</body>
</html>
