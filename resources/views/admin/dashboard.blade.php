<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARSABE Executive Admin Portal</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Orbitron:wght@600;800&display=swap" rel="stylesheet">

    <!-- Tailwind & External Assets -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="{{ asset('js/tailwind-config.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-exec.css') }}">
</head>

<body class="antialiased min-h-screen p-4 lg:p-8">

    <div class="max-w-7xl mx-auto space-y-6">

        <!-- HEADER BAR -->
        <header class="exec-card p-6 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-2xl shadow-lg">
                    👔
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-wide flex items-center gap-2">
                        PARSABE EXECUTIVE CORE
                        <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">OPERATIONAL</span>
                    </h1>
                    <p class="text-xs text-slate-400">System Governance, Contact Submissions & Feedback Hub</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="text" id="dashboard-search" placeholder="Search inquiries or feedback..."
                    class="bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 w-64">
                
                <a href="/" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-medium transition">
                    Exit Core
                </a>
            </div>
        </header>

        <!-- METRIC STAT CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="exec-card p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-400 block font-medium">Contact Inquiries</span>
                    <span class="text-2xl font-bold text-white mt-1 block">{{ $contacts->count() }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-lg">
                    📬
                </div>
            </div>

            <div class="exec-card p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-400 block font-medium">CS Feedbacks</span>
                    <span class="text-2xl font-bold text-white mt-1 block">{{ $feedbacks->count() }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-lg">
                    💬
                </div>
            </div>

            <div class="exec-card p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-400 block font-medium">2FA Security</span>
                    <span class="text-xs font-bold text-emerald-400 mt-1 block">VERIFIED ACTIVE</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg">
                    🔒
                </div>
            </div>

            <div class="exec-card p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-400 block font-medium">Ollama AI Backend</span>
                    <span class="text-xs font-bold text-indigo-400 mt-1 block">LOCAL QWEN 2.5</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-lg">
                    🤖
                </div>
            </div>
        </div>

        <!-- CONTACT INQUIRIES SECTION -->
        <section class="exec-card rounded-3xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    📬 Direct Contact Submissions
                </h2>
                @if($contacts->count() > 0)
                    <form action="{{ route('parsa.contacts.purge_all') }}" method="POST" onsubmit="return confirm('Purge all contact messages?');">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-rose-500/20 text-rose-300 hover:bg-rose-500/30 rounded-lg text-[11px] border border-rose-500/30 transition">
                            Purge All
                        </button>
                    </form>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="exec-table-header">
                            <th class="p-3 rounded-l-xl">Sender</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Message Snippet</th>
                            <th class="p-3">Received</th>
                            <th class="p-3 text-right rounded-r-xl">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $c)
                            <tr class="exec-row dashboard-card text-xs" data-searchable="{{ strtolower($c->name . ' ' . $c->email . ' ' . $c->message) }}">
                                <td class="p-3 font-semibold text-white">{{ $c->name }}</td>
                                <td class="p-3 text-slate-300 font-mono">{{ $c->email }}</td>
                                <td class="p-3 text-slate-300 max-w-xs truncate">{{ $c->message }}</td>
                                <td class="p-3 text-slate-400">{{ $c->created_at->format('M d, H:i') }}</td>
                                <td class="p-3 text-right">
                                    <form action="{{ route('parsa.contact.delete', $c->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete message?');">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-rose-500/20 text-rose-300 hover:bg-rose-500/30 rounded-md text-[10px] transition">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-slate-500 text-xs">No contact inquiries recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- CS FEEDBACK SECTION -->
        <section class="exec-card rounded-3xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    💬 Student CS Feedback Submissions
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="exec-table-header">
                            <th class="p-3 rounded-l-xl">Student Name</th>
                            <th class="p-3">Rating</th>
                            <th class="p-3">Feedback Message</th>
                            <th class="p-3">Date</th>
                            <th class="p-3 text-right rounded-r-xl">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedbacks as $f)
                            <tr class="exec-row dashboard-card text-xs" data-searchable="{{ strtolower(($f->student->name ?? 'Anonymous') . ' ' . $f->comment) }}">
                                <td class="p-3 font-semibold text-white">{{ $f->student->name ?? 'Student' }}</td>
                                <td class="p-3 text-amber-400 font-bold">★ {{ $f->rating ?? 5 }}/5</td>
                                <td class="p-3 text-slate-300 max-w-xs truncate">{{ $f->comment }}</td>
                                <td class="p-3 text-slate-400">{{ $f->created_at->format('M d, H:i') }}</td>
                                <td class="p-3 text-right">
                                    <form action="{{ route('parsa.feedback.delete', $f->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete feedback?');">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-rose-500/20 text-rose-300 hover:bg-rose-500/30 rounded-md text-[10px] transition">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-slate-500 text-xs">No feedback submissions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </div>

    <!-- External Admin Dashboard ESM Module -->
    <script type="module" src="{{ asset('js/admin-dashboard.js') }}"></script>
</body>
</html>
