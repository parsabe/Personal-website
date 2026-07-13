<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parsa Control Panel - Admin Dashboard</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Tailwind & AlpineJS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.tailwind = { config: { darkMode: 'class' } };
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
</head>

<body
    class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-4 lg:p-10 min-h-screen relative overflow-x-hidden"
    x-data="{ tab: 'feedbacks', activeReply: null, replyType: '', replyId: '', replyName: '', replyEmail: '' }">

    <div id="main-container"
        class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden h-[85vh] z-10 transition-colors duration-700">

        <!-- Theme Switcher & System Dots -->
        <div class="absolute top-6 right-8 flex items-center gap-5 z-50">
            <button id="theme-toggle" class="p-2.5 rounded-full ios-glass transition hover:scale-110">
                <span id="theme-icon-light" class="hidden text-sm">☀️</span>
                <span id="theme-icon-dark" class="hidden text-sm">🌙</span>
            </button>

            <div class="flex gap-2">
                <div class="w-3.5 h-3.5 rounded-full bg-[#ff5f56] shadow-sm border border-[#e0443e]"></div>
                <div class="w-3.5 h-3.5 rounded-full bg-[#ffbd2e] shadow-sm border border-[#dea123]"></div>
                <div class="w-3.5 h-3.5 rounded-full bg-[#27c93f] shadow-sm border border-[#1aab29]"></div>
            </div>
        </div>

        @include('sidebar')

        <main class="flex-1 p-8 lg:p-14 relative overflow-y-auto scroll-smooth">
            <div class="relative z-10 mt-12 lg:mt-0">
                
                <!-- Header Title -->
                <div>
                    <span
                        class="inline-flex items-center gap-2 px-4 py-1.5 ios-glass text-gray-900 dark:text-white rounded-full text-sm font-bold mb-6">
                        🔒 CENTRAL CONTROL PORTAL
                    </span>

                    <h1
                        class="text-4xl lg:text-5xl font-extrabold mb-4 tracking-tight text-gray-900 dark:text-white drop-shadow-sm">
                        Admin <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-pink-600 dark:from-orange-400 dark:to-pink-500">Dashboard.</span>
                    </h1>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-8 font-medium">
                        Unified inbox to read, manage, reply to, and delete feedback and contact submissions.
                    </p>

                    <!-- Alert messages -->
                    @if (count($errors) > 0)
                        <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-200 text-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-rose-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    @foreach (is_array($errors) ? $errors : $errors->all() as $error)
                                        <span class="block font-medium">{{ $error }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-200 text-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Stats overview panel -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-8">
                        <div class="p-5 rounded-2xl bg-white/40 dark:bg-black/30 border border-white/20 dark:border-white/10 shadow-sm flex flex-col justify-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">CS Submissions</span>
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $feedbacks->count() }}</span>
                        </div>
                        <div class="p-5 rounded-2xl bg-white/40 dark:bg-black/30 border border-white/20 dark:border-white/10 shadow-sm flex flex-col justify-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">Contact Messages</span>
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $contacts->count() }}</span>
                        </div>
                        <div class="p-5 rounded-2xl bg-white/40 dark:bg-black/30 border border-white/20 dark:border-white/10 shadow-sm flex flex-col justify-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">CS Replied</span>
                            <span class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400 mt-1">
                                {{ $feedbacks->whereNotNull('replied_at')->count() }}
                            </span>
                        </div>
                        <div class="p-5 rounded-2xl bg-white/40 dark:bg-black/30 border border-white/20 dark:border-white/10 shadow-sm flex flex-col justify-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">Contact Replied</span>
                            <span class="text-3xl font-extrabold text-orange-600 dark:text-orange-400 mt-1">
                                {{ $contacts->whereNotNull('replied_at')->count() }}
                            </span>
                        </div>
                    </div>

                    <!-- Tab Switcher Navigation -->
                    <div class="flex gap-4 border-b border-gray-200 dark:border-white/10 pb-3 mb-6">
                        <button @click="tab = 'feedbacks'"
                            class="px-5 py-2.5 rounded-xl font-bold transition-all text-sm"
                            :class="tab === 'feedbacks' ? 'bg-orange-500 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-white/20 dark:hover:bg-white/5'">
                            🎓 Specialist Feedbacks ({{ $feedbacks->count() }})
                        </button>
                        <button @click="tab = 'contacts'"
                            class="px-5 py-2.5 rounded-xl font-bold transition-all text-sm"
                            :class="tab === 'contacts' ? 'bg-orange-500 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-white/20 dark:hover:bg-white/5'">
                            ✉️ Contact Form Messages ({{ $contacts->count() }})
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="mb-6 relative">
                        <input id="dashboard-search" type="text" placeholder="Search entries..."
                            class="w-full px-5 py-3.5 pl-12 bg-white/40 dark:bg-black/30 border border-gray-300 dark:border-white/10 rounded-2xl text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:focus:ring-orange-400 transition-all font-semibold">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-lg">🔍</span>
                        </div>
                    </div>

                    <!-- 1. Specialist Feedbacks Section -->
                    <div x-show="tab === 'feedbacks'" class="space-y-6 tab-content-feedbacks">
                        @forelse($feedbacks as $item)
                            <div class="dashboard-card p-6 rounded-2xl bg-white/50 dark:bg-black/40 border border-white/30 dark:border-white/10 hover:border-orange-500/50 dark:hover:border-orange-400/50 shadow-md transition-all duration-300"
                                data-searchable="{{ strtolower($item->student->first_name . ' ' . $item->student->last_name . ' ' . $item->student->email . ' ' . $item->ideas . ' ' . $item->feedback . ' ' . $item->questions . ' ' . $item->reply) }}">
                                
                                <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-gray-200 dark:border-white/10">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $item->student->first_name }} {{ $item->student->last_name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 font-medium">
                                            {{ $item->student->email }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if($item->received_all_files)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                                ✅ Received All Files
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                                ❌ Missing Files
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-400 dark:text-gray-500 font-semibold uppercase">
                                            {{ $item->created_at->format('M d, Y H:i') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 pb-4">
                                    <!-- Ideas Section -->
                                    <div class="space-y-1.5">
                                        <h4 class="text-xs font-bold tracking-wider text-gray-400 dark:text-gray-500 uppercase">Suggestions / Ideas</h4>
                                        <div class="text-sm text-gray-700 dark:text-gray-300 font-medium leading-relaxed bg-white/20 dark:bg-black/10 p-3.5 rounded-xl border border-white/10 dark:border-white/5 whitespace-pre-wrap">
                                            {{ $item->ideas }}
                                        </div>
                                    </div>

                                    <!-- Feedback Section -->
                                    <div class="space-y-1.5">
                                        <h4 class="text-xs font-bold tracking-wider text-gray-400 dark:text-gray-500 uppercase">General Feedback</h4>
                                        <div class="text-sm text-gray-700 dark:text-gray-300 font-medium leading-relaxed bg-white/20 dark:bg-black/10 p-3.5 rounded-xl border border-white/10 dark:border-white/5 whitespace-pre-wrap">
                                            {{ $item->feedback }}
                                        </div>
                                    </div>

                                    <!-- Questions Section -->
                                    <div class="space-y-1.5">
                                        <h4 class="text-xs font-bold tracking-wider text-gray-400 dark:text-gray-500 uppercase">Questions</h4>
                                        <div class="text-sm text-gray-700 dark:text-gray-300 font-medium leading-relaxed bg-white/20 dark:bg-black/10 p-3.5 rounded-xl border border-white/10 dark:border-white/5 whitespace-pre-wrap">
                                            {{ $item->questions }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Response Log -->
                                @if($item->replied_at)
                                    <div class="p-4 bg-indigo-500/5 dark:bg-indigo-500/10 border border-indigo-500/20 rounded-xl mb-4 text-sm">
                                        <span class="block text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-1">
                                            Response Sent on {{ $item->replied_at->format('M d, Y H:i') }}
                                        </span>
                                        <p class="text-gray-700 dark:text-gray-300 font-medium leading-relaxed whitespace-pre-wrap">{{ $item->reply }}</p>
                                    </div>
                                @endif

                                <!-- Actions Bar -->
                                <div class="flex justify-end gap-3 pt-3 border-t border-gray-200 dark:border-white/10">
                                    <button @click="activeReply = 'feedback'; replyId = '{{ $item->id }}'; replyName = '{{ $item->student->first_name }} {{ $item->student->last_name }}'; replyEmail = '{{ $item->student->email }}'"
                                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-md">
                                        ✉️ Reply via Email
                                    </button>
                                    
                                    <form action="{{ route('parsa.feedback.delete', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this submission permanently?');">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-rose-600/10 hover:bg-rose-600 border border-rose-500/20 text-rose-600 dark:text-rose-400 hover:text-white rounded-xl text-xs font-bold transition-all">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @empty
                            <div class="text-center py-12 p-6 rounded-2xl bg-white/40 dark:bg-black/30 border border-white/20 dark:border-white/10">
                                <span class="text-3xl block mb-2">📭</span>
                                <span class="text-gray-500 dark:text-gray-400 font-semibold text-sm">No feedbacks submitted yet.</span>
                            </div>
                        @endforelse
                    </div>

                    <!-- 2. Contact Messages Section -->
                    <div x-show="tab === 'contacts'" class="space-y-6 tab-content-contacts">
                        @forelse($contacts as $msg)
                            <div class="dashboard-card p-6 rounded-2xl bg-white/50 dark:bg-black/40 border border-white/30 dark:border-white/10 hover:border-orange-500/50 dark:hover:border-orange-400/50 shadow-md transition-all duration-300"
                                data-searchable="{{ strtolower($msg->name . ' ' . $msg->email . ' ' . $msg->message . ' ' . $msg->reply) }}">
                                
                                <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-gray-200 dark:border-white/10">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $msg->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 font-medium">
                                            {{ $msg->email }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-gray-400 dark:text-gray-500 font-semibold uppercase">
                                            {{ $msg->created_at->format('M d, Y H:i') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="pt-4 pb-4">
                                    <h4 class="text-xs font-bold tracking-wider text-gray-400 dark:text-gray-500 uppercase mb-1.5">Message</h4>
                                    <div class="text-sm text-gray-700 dark:text-gray-300 font-medium leading-relaxed bg-white/20 dark:bg-black/10 p-3.5 rounded-xl border border-white/10 dark:border-white/5 whitespace-pre-wrap">
                                        {{ $msg->message }}
                                    </div>
                                </div>

                                <!-- Response Log -->
                                @if($msg->replied_at)
                                    <div class="p-4 bg-indigo-500/5 dark:bg-indigo-500/10 border border-indigo-500/20 rounded-xl mb-4 text-sm">
                                        <span class="block text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-1">
                                            Response Sent on {{ $msg->replied_at->format('M d, Y H:i') }}
                                        </span>
                                        <p class="text-gray-700 dark:text-gray-300 font-medium leading-relaxed whitespace-pre-wrap">{{ $msg->reply }}</p>
                                    </div>
                                @endif

                                <!-- Actions Bar -->
                                <div class="flex justify-end gap-3 pt-3 border-t border-gray-200 dark:border-white/10">
                                    <button @click="activeReply = 'contact'; replyId = '{{ $msg->id }}'; replyName = '{{ $msg->name }}'; replyEmail = '{{ $msg->email }}'"
                                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-md">
                                        ✉️ Reply via Email
                                    </button>
                                    
                                    <form action="{{ route('parsa.contact.delete', $msg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message permanently?');">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-rose-600/10 hover:bg-rose-600 border border-rose-500/20 text-rose-600 dark:text-rose-400 hover:text-white rounded-xl text-xs font-bold transition-all">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @empty
                            <div class="text-center py-12 p-6 rounded-2xl bg-white/40 dark:bg-black/30 border border-white/20 dark:border-white/10">
                                <span class="text-3xl block mb-2">📭</span>
                                <span class="text-gray-500 dark:text-gray-400 font-semibold text-sm">No contact messages received yet.</span>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </main>

    </div>

    <!-- Email Reply Modal (AlpineJS driven) -->
    <div x-show="activeReply !== null"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
        x-cloak>
        
        <div class="w-full max-w-lg p-6 rounded-3xl bg-slate-900 border border-white/10 shadow-2xl space-y-4"
            @click.away="activeReply = null">
            
            <div class="flex justify-between items-center pb-2 border-b border-white/5">
                <div>
                    <h3 class="text-lg font-bold text-white">Draft Email Response</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5">To: <span x-text="replyName"></span> (&lt;<span x-text="replyEmail"></span>&gt;)</p>
                </div>
                <button @click="activeReply = null" class="text-slate-400 hover:text-white text-lg">✕</button>
            </div>

            <!-- Forms pointing to respective endpoints -->
            <!-- Feedback Form -->
            <form x-show="activeReply === 'feedback'" :action="'/parsa/feedback/' + replyId + '/reply'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Message Body</label>
                    <textarea name="reply" required rows="6"
                        class="w-full px-4 py-3 bg-slate-950/60 border border-slate-800 focus:border-orange-500 rounded-2xl text-sm text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition-all"
                        placeholder="Write your email response here..."></textarea>
                </div>
                
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="activeReply = null" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md">Send Reply</button>
                </div>
            </form>

            <!-- Contact Form -->
            <form x-show="activeReply === 'contact'" :action="'/parsa/contact/' + replyId + '/reply'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Message Body</label>
                    <textarea name="reply" required rows="6"
                        class="w-full px-4 py-3 bg-slate-950/60 border border-slate-800 focus:border-orange-500 rounded-2xl text-sm text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition-all"
                        placeholder="Write your email response here..."></textarea>
                </div>
                
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="activeReply = null" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md">Send Reply</button>
                </div>
            </form>

        </div>
    </div>

    <!-- Client-Side Real-Time Filter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('dashboard-search');
            
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    const query = e.target.value.toLowerCase().trim();
                    const cards = document.querySelectorAll('.dashboard-card');
                    
                    cards.forEach(card => {
                        const content = card.getAttribute('data-searchable') || '';
                        if (content.includes(query)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>
