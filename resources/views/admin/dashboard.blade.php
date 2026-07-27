<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARSABE // CENTRAL_CORE</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800;900&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind & AlpineJS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="{{ asset('js/tailwind-config.js') }}"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/profile.jpg') }}">

    <!-- Cyberpunk Theme Styles -->
    <style>
        body {
            background-color: #010206;
            background-image: 
                linear-gradient(rgba(1, 2, 6, 0.85), rgba(1, 2, 6, 0.95)),
                url('/images/cyberpunk_bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .cyber-header {
            background: rgba(4, 4, 10, 0.95);
            border-bottom: 2px solid #00f0ff;
            box-shadow: 0 5px 25px rgba(0, 240, 255, 0.1);
        }

        .cyber-panel-main {
            background: rgba(6, 6, 15, 0.85);
            border: 1px solid rgba(0, 240, 255, 0.15);
            box-shadow: 
                0 0 35px rgba(0, 240, 255, 0.05), 
                inset 0 0 25px rgba(0, 240, 255, 0.02);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
        }

        .cyber-card-sub {
            background: rgba(4, 4, 12, 0.8);
            border: 1px solid rgba(0, 240, 255, 0.2);
            box-shadow: 
                0 0 10px rgba(0, 240, 255, 0.05),
                inset 0 0 10px rgba(0, 240, 255, 0.02);
            transition: all 0.3s ease;
        }

        .cyber-card-sub:hover {
            border-color: #00f0ff;
            box-shadow: 
                0 0 20px rgba(0, 240, 255, 0.2),
                inset 0 0 15px rgba(0, 240, 255, 0.05);
        }

        .cyber-stat-box {
            background: rgba(2, 2, 6, 0.8);
            border-left: 4px solid #ff0077;
            border-top: 1px solid rgba(255, 0, 119, 0.2);
            border-right: 1px solid rgba(255, 0, 119, 0.2);
            border-bottom: 1px solid rgba(255, 0, 119, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .cyber-stat-box.cyan {
            border-left-color: #00f0ff;
        }

        .cyber-tab {
            font-family: 'Orbitron', monospace;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .cyber-tab.active {
            background: rgba(255, 0, 119, 0.12);
            border-color: #ff0077;
            color: #ff0077;
            text-shadow: 0 0 8px rgba(255, 0, 119, 0.6);
            box-shadow: 0 0 15px rgba(255, 0, 119, 0.2);
        }

        .cyber-btn-primary {
            background: transparent;
            border: 1.5px solid #00f0ff;
            color: #00f0ff;
            font-family: 'Orbitron', monospace;
            text-shadow: 0 0 6px rgba(0, 240, 255, 0.5);
            box-shadow: 0 0 10px rgba(0, 240, 255, 0.1);
            transition: all 0.3s ease;
        }

        .cyber-btn-primary:hover {
            background: #00f0ff;
            color: #000;
            text-shadow: none;
            box-shadow: 0 0 20px #00f0ff;
        }

        .cyber-btn-danger {
            background: transparent;
            border: 1.5px solid #ff0077;
            color: #ff0077;
            font-family: 'Orbitron', monospace;
            text-shadow: 0 0 6px rgba(255, 0, 119, 0.5);
            box-shadow: 0 0 10px rgba(255, 0, 119, 0.1);
            transition: all 0.3s ease;
        }

        .cyber-btn-danger:hover {
            background: #ff0077;
            color: #000;
            text-shadow: none;
            box-shadow: 0 0 20px #ff0077;
        }

        .cyber-input-search {
            background: rgba(0, 0, 0, 0.85);
            border: 1px solid rgba(0, 240, 255, 0.25);
            color: #00f0ff;
            text-shadow: 0 0 5px rgba(0, 240, 255, 0.4);
            transition: all 0.3s ease;
        }

        .cyber-input-search:focus {
            border-color: #00f0ff;
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.25);
            outline: none;
        }

        .text-glow-cyan {
            text-shadow: 0 0 10px rgba(0, 240, 255, 0.6);
        }

        .text-glow-magenta {
            text-shadow: 0 0 10px rgba(255, 0, 119, 0.6);
        }

        .scanline {
            width: 100%;
            height: 100px;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(0, 240, 255, 0.02), rgba(255, 255, 255, 0));
            position: absolute;
            z-index: 10;
            top: -100px;
            animation: scan 10s linear infinite;
            pointer-events: none;
        }

        @keyframes scan {
            0% { top: -100px; }
            100% { top: 100%; }
        }
    </style>
</head>

<body
    class="text-gray-300 dark:text-slate-200 antialiased min-h-screen relative overflow-x-hidden flex flex-col font-sans"
    x-data="{ tab: 'feedbacks', activeReply: null, replyType: '', replyId: '', replyName: '', replyEmail: '' }">

    <!-- Scanline effect overlay -->
    <div class="scanline"></div>

    <!-- Cyber Header (Standalone Navbar) -->
    <header class="cyber-header w-full px-6 lg:px-12 py-4 flex justify-between items-center z-20">
        <div class="flex items-center space-x-3">
            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
            <a href="/" class="flex items-center space-x-2 text-xl font-bold font-mono tracking-widest text-white hover:opacity-90 transition-opacity">
                <span class="text-glow-magenta text-[#ff0077]">PARSABE</span>
                <span class="text-slate-500 font-light text-sm">// SYSTEM_CORE_v2.0</span>
            </a>
        </div>
        
        <div class="flex items-center space-x-6 font-mono text-[10px] tracking-wider text-slate-500">
            <div class="hidden md:flex items-center space-x-4">
                <span>SECURE_CONNECTION: ESTABLISHED</span>
                <span>//</span>
                <span>DATABASE: sql_parsabe_com</span>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="cyber-btn-danger px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest transition-all">
                    [✕] SHUTDOWN_SESSION
                </button>
            </form>
        </div>
    </header>

    <!-- Main Workspace Container -->
    <main class="flex-grow p-6 lg:p-12 z-10 max-w-7xl mx-auto w-full">
        
        <div class="cyber-panel-main rounded-[2rem] p-4 sm:p-6 lg:p-10 border border-white/5 relative">
            
            <!-- Tech Header Section -->
            <div class="mb-8">
                <span
                    class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#00f0ff]/5 border border-[#00f0ff]/20 text-[#00f0ff] rounded-full text-[10px] font-bold font-mono tracking-widest mb-4 uppercase text-glow-cyan">
                    🔒 CENTRAL_DATABASE_NODE // ROOT_ACCESS
                </span>

                <h1
                    class="text-4xl lg:text-5xl font-black font-mono tracking-wider text-white uppercase text-glow-cyan">
                    ADMIN_DASHBOARD
                </h1>
                
                <p class="text-xs font-mono text-slate-400 mt-2 uppercase tracking-widest">
                    Manage Campus Specialist Feedbacks and general Contact Form messages.
                </p>
            </div>

            <!-- Alert messages -->
            @if (count($errors) > 0)
                <div class="mb-6 p-4 rounded-xl bg-red-950/20 border border-red-500/30 text-red-200 text-xs font-mono">
                    <div class="flex items-start">
                        <span class="mr-2">⚠️</span>
                        <div>
                            @foreach (is_array($errors) ? $errors : $errors->all() as $error)
                                <span class="block text-glow-magenta">{{ $error }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-950/20 border border-emerald-500/30 text-emerald-200 text-xs font-mono">
                    <div class="flex items-start">
                        <span class="mr-2">✔</span>
                        <span class="font-medium text-glow-cyan">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Stats Panel (Futuristic Grid) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8 font-mono">
                <div class="cyber-stat-box p-3.5 sm:p-5 rounded-xl shadow-sm flex flex-col justify-center">
                    <span class="text-[8px] sm:text-[9px] text-slate-500 font-bold uppercase tracking-widest">CS_SUBMISSIONS</span>
                    <span class="text-xl sm:text-3xl font-extrabold text-white mt-1 text-glow-magenta">{{ $feedbacks->count() }}</span>
                </div>
                <div class="cyber-stat-box cyan p-3.5 sm:p-5 rounded-xl shadow-sm flex flex-col justify-center">
                    <span class="text-[8px] sm:text-[9px] text-slate-500 font-bold uppercase tracking-widest">CONTACT_MSGS</span>
                    <span class="text-xl sm:text-3xl font-extrabold text-white mt-1 text-glow-cyan">{{ $contacts->count() }}</span>
                </div>
                <div class="cyber-stat-box p-3.5 sm:p-5 rounded-xl shadow-sm flex flex-col justify-center">
                    <span class="text-[8px] sm:text-[9px] text-slate-500 font-bold uppercase tracking-widest">CS_RESOLVED</span>
                    <span class="text-xl sm:text-3xl font-extrabold text-[#ff0077] mt-1 text-glow-magenta">
                        {{ $feedbacks->whereNotNull('replied_at')->count() }}
                    </span>
                </div>
                <div class="cyber-stat-box cyan p-3.5 sm:p-5 rounded-xl shadow-sm flex flex-col justify-center">
                    <span class="text-[8px] sm:text-[9px] text-slate-500 font-bold uppercase tracking-widest">CONTACT_RESOLVED</span>
                    <span class="text-xl sm:text-3xl font-extrabold text-[#00f0ff] mt-1 text-glow-cyan">
                        {{ $contacts->whereNotNull('replied_at')->count() }}
                    </span>
                </div>
            </div>

            <!-- Cyber Tab Switcher -->
            <div class="flex flex-col sm:flex-row gap-3 border-b border-white/5 pb-4 mb-6">
                <button @click="tab = 'feedbacks'"
                    class="cyber-tab w-full sm:w-auto px-5 py-2.5 rounded-xl text-xs tracking-wider text-center"
                    :class="tab === 'feedbacks' ? 'active' : 'text-slate-500 hover:text-slate-300 bg-white/5'">
                    [01] CS_SPECIALISTS ({{ $feedbacks->count() }})
                </button>
                <button @click="tab = 'contacts'"
                    class="cyber-tab w-full sm:w-auto px-5 py-2.5 rounded-xl text-xs tracking-wider text-center"
                    :class="tab === 'contacts' ? 'active' : 'text-slate-500 hover:text-slate-300 bg-white/5'">
                    [02] CONTACTS_INBOX ({{ $contacts->count() }})
                </button>
            </div>

            <!-- Search Input -->
            <div class="mb-6 relative">
                <input id="dashboard-search" type="text" placeholder="QUERY_DATAFEED..."
                    class="cyber-input-search w-full px-5 py-3.5 pl-12 rounded-xl text-sm font-mono tracking-wider">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-xs font-mono text-slate-600">SYS_</span>
                </div>
            </div>

            <!-- 1. Specialist Feedbacks Section -->
            <div x-show="tab === 'feedbacks'" class="space-y-6 tab-content-feedbacks">
                @forelse($feedbacks as $item)
                    <div class="dashboard-card cyber-card-sub p-4 sm:p-6 rounded-2xl animate-fade-in"
                        data-searchable="{{ strtolower(($item->student ? ($item->student->first_name . ' ' . $item->student->last_name . ' ' . $item->student->email) : ('Non-Attendee ' . $item->email)) . ' ' . $item->ideas . ' ' . $item->feedback . ' ' . $item->questions . ' ' . $item->reply) }}">
                        
                        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-white/5">
                            <div>
                                <h3 class="text-lg font-bold text-white tracking-wide">
                                    {{ $item->student ? ($item->student->first_name . ' ' . $item->student->last_name) : 'Non-Attendee' }}
                                </h3>
                                <p class="text-xs font-mono text-slate-400 mt-0.5">
                                    {{ $item->student ? $item->student->email : $item->email }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 font-mono text-[10px]">
                                @if($item->received_all_files)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-widest">
                                        STATUS_COMPLETED
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-[#ff0077]/10 text-[#ff0077] border border-[#ff0077]/20 uppercase tracking-widest">
                                        STATUS_PENDING_FILES
                                    </span>
                                @endif
                                <span class="text-slate-500 font-bold uppercase">
                                    {{ $item->created_at->format('Y.m.d // H:i') }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 pb-4">
                            <!-- Ideas Section -->
                            <div class="space-y-1.5">
                                <h4 class="text-[10px] font-bold font-mono tracking-wider text-slate-500 uppercase">Suggestions / Ideas</h4>
                                <div class="text-sm text-slate-300 leading-relaxed bg-black/60 p-4 rounded-xl border border-white/5 whitespace-pre-wrap">
                                    {{ $item->ideas }}
                                </div>
                            </div>

                            <!-- Feedback Section -->
                            <div class="space-y-1.5">
                                <h4 class="text-[10px] font-bold font-mono tracking-wider text-slate-500 uppercase">General Feedback</h4>
                                <div class="text-sm text-slate-300 leading-relaxed bg-black/60 p-4 rounded-xl border border-white/5 whitespace-pre-wrap">
                                    {{ $item->feedback }}
                                </div>
                            </div>

                            <!-- Questions Section -->
                            <div class="space-y-1.5">
                                <h4 class="text-[10px] font-bold font-mono tracking-wider text-slate-500 uppercase">Questions</h4>
                                <div class="text-sm text-slate-300 leading-relaxed bg-black/60 p-4 rounded-xl border border-white/5 whitespace-pre-wrap">
                                    {{ $item->questions }}
                                </div>
                            </div>
                        </div>

                        <!-- Response Log -->
                        @if($item->replied_at)
                            <div class="p-4 bg-indigo-500/5 border border-indigo-500/20 rounded-xl mb-4 text-xs leading-relaxed">
                                <span class="block text-[9px] font-mono font-bold text-indigo-400 uppercase tracking-widest mb-1">
                                    RESPONSE_LOG // OUTBOX_ACK // {{ $item->replied_at->format('Y.m.d // H:i') }}
                                </span>
                                <p class="text-slate-400 whitespace-pre-wrap">{{ $item->reply }}</p>
                            </div>
                        @endif

                        <!-- Actions Bar -->
                        <div class="flex flex-wrap justify-end gap-3 pt-3 border-t border-white/5">
                            <button @click="activeReply = 'feedback'; replyId = '{{ $item->id }}'; replyName = '{{ $item->student ? ($item->student->first_name . ' ' . $item->student->last_name) : 'Non-Attendee' }}'; replyEmail = '{{ $item->student ? $item->student->email : $item->email }}'"
                                class="cyber-btn-primary px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider">
                                [✉] SEND_REPLY
                            </button>
                            
                            <form action="{{ route('parsa.feedback.delete', $item->id) }}" method="POST" onsubmit="return confirm('PERMANENTLY ERASE THIS DATA RECORD?');">
                                @csrf
                                <button type="submit" class="cyber-btn-danger px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider">
                                    [🗑] PURGE
                                </button>
                            </form>
                        </div>

                    </div>
                @empty
                    <div class="text-center py-12 p-6 rounded-2xl bg-black/40 border border-white/5">
                        <span class="text-3xl block mb-2 font-mono">⛃</span>
                        <span class="text-slate-500 font-mono text-xs uppercase tracking-widest">NO_CS_RECORDS_FOUND</span>
                    </div>
                @endforelse
            </div>

            <!-- 2. Contact Messages Section -->
            <div x-show="tab === 'contacts'" class="space-y-6 tab-content-contacts">
                @if($contacts->count() > 0)
                    <div class="flex justify-between items-center pb-3 border-b border-white/5 mb-4">
                        <span class="text-xs font-mono text-slate-500 uppercase tracking-widest">Danger Zone // Purge Control</span>
                        <form action="{{ route('parsa.contacts.purge-all') }}" method="POST" onsubmit="return confirm('WARNING: THIS WILL PERMANENTLY ERASE ALL CONTACT MESSAGES FROM THE DATABASE! PROCEED?');">
                            @csrf
                            <button type="submit" class="cyber-btn-danger px-5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest">
                                [☠] PURGE_ALL_CONTACTS
                            </button>
                        </form>
                    </div>
                @endif
                @forelse($contacts as $msg)
                    <div class="dashboard-card cyber-card-sub p-4 sm:p-6 rounded-2xl animate-fade-in"
                        data-searchable="{{ strtolower($msg->name . ' ' . $msg->email . ' ' . $msg->message . ' ' . $msg->reply) }}">
                        
                        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-white/5">
                            <div>
                                <h3 class="text-lg font-bold text-white tracking-wide">
                                    {{ $msg->name }}
                                </h3>
                                <p class="text-xs font-mono text-slate-400 mt-0.5">
                                    {{ $msg->email }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 font-mono text-[10px]">
                                <span class="text-slate-500 font-bold uppercase">
                                    {{ $msg->created_at->format('Y.m.d // H:i') }}
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 pb-4">
                            <h4 class="text-[10px] font-bold font-mono tracking-wider text-slate-500 uppercase mb-1.5">Message</h4>
                            <div class="text-sm text-slate-300 leading-relaxed bg-black/60 p-4 rounded-xl border border-white/5 whitespace-pre-wrap">
                                {{ $msg->message }}
                            </div>
                        </div>

                        <!-- Response Log -->
                        @if($msg->replied_at)
                            <div class="p-4 bg-indigo-500/5 border border-indigo-500/20 rounded-xl mb-4 text-xs leading-relaxed">
                                <span class="block text-[9px] font-mono font-bold text-indigo-400 uppercase tracking-widest mb-1">
                                    RESPONSE_LOG // OUTBOX_ACK // {{ $msg->replied_at->format('Y.m.d // H:i') }}
                                </span>
                                <p class="text-slate-400 whitespace-pre-wrap">{{ $msg->reply }}</p>
                            </div>
                        @endif

                        <!-- Actions Bar -->
                        <div class="flex flex-wrap justify-end gap-3 pt-3 border-t border-white/5">
                            <button @click="activeReply = 'contact'; replyId = '{{ $msg->id }}'; replyName = '{{ $msg->name }}'; replyEmail = '{{ $msg->email }}'"
                                class="cyber-btn-primary px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider">
                                [✉] SEND_REPLY
                            </button>
                            
                            <form action="{{ route('parsa.contact.delete', $msg->id) }}" method="POST" onsubmit="return confirm('PERMANENTLY ERASE THIS DATA RECORD?');">
                                @csrf
                                <button type="submit" class="cyber-btn-danger px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider">
                                    [🗑] PURGE
                                </button>
                            </form>
                        </div>

                    </div>
                @empty
                    <div class="text-center py-12 p-6 rounded-2xl bg-black/40 border border-white/5">
                        <span class="text-3xl block mb-2 font-mono">⛃</span>
                        <span class="text-slate-500 font-mono text-xs uppercase tracking-widest">NO_CONTACT_RECORDS_FOUND</span>
                    </div>
                @endforelse
            </div>

        </div>
    </main>

    <!-- Email Reply Modal (Cyber Tech Layout) -->
    <div x-show="activeReply !== null"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-sm"
        x-cloak>
        
        <div class="w-full max-w-lg p-6 rounded-3xl bg-[#080812] border border-[#00f0ff]/40 shadow-2xl space-y-4"
            @click.away="activeReply = null">
            
            <div class="flex justify-between items-center pb-2 border-b border-white/5">
                <div>
                    <h3 class="text-lg font-bold text-white font-mono uppercase tracking-wider">COMPOSE_EMAIL_RESPONSE</h3>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">TO: <span x-text="replyName"></span> (&lt;<span x-text="replyEmail"></span>&gt;)</p>
                </div>
                <button @click="activeReply = null" class="text-slate-500 hover:text-white text-lg">✕</button>
            </div>

            <!-- Feedback Reply Form -->
            <form x-show="activeReply === 'feedback'" :action="'/parsa/feedback/' + replyId + '/reply'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold font-mono tracking-widest text-slate-500 uppercase mb-2">EMAIL_BODY_TEXT</label>
                    <textarea name="reply" required rows="6"
                        class="w-full px-4 py-3 bg-black/80 border border-slate-800 focus:border-[#00f0ff] rounded-xl text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-[#00f0ff]/10 transition-all"
                        placeholder="Write response text..."></textarea>
                </div>
                
                <div class="flex justify-end gap-3 pt-2 font-mono text-[10px]">
                    <button type="button" @click="activeReply = null" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-semibold">CANCEL</button>
                    <button type="submit" class="cyber-btn-primary px-4 py-2 rounded-xl font-bold shadow-md">DISPATCH_MAIL</button>
                </div>
            </form>

            <!-- Contact Reply Form -->
            <form x-show="activeReply === 'contact'" :action="'/parsa/contact/' + replyId + '/reply'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold font-mono tracking-widest text-slate-500 uppercase mb-2">EMAIL_BODY_TEXT</label>
                    <textarea name="reply" required rows="6"
                        class="w-full px-4 py-3 bg-black/80 border border-slate-800 focus:border-[#00f0ff] rounded-xl text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-[#00f0ff]/10 transition-all"
                        placeholder="Write response text..."></textarea>
                </div>
                
                <div class="flex justify-end gap-3 pt-2 font-mono text-[10px]">
                    <button type="button" @click="activeReply = null" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-semibold">CANCEL</button>
                    <button type="submit" class="cyber-btn-primary px-4 py-2 rounded-xl font-bold shadow-md">DISPATCH_MAIL</button>
                </div>
            </form>

        </div>
    </div>

    <!-- External ESM Javascript Module -->
    <script type="module" src="{{ asset('js/admin-dashboard.js') }}"></script>
</body>

</html>
