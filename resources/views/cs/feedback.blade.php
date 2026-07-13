<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CS Career Fair - Feedback Portal</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    
    <!-- Custom Premium Styles -->
    <style>
        body {
            background-color: #0b0f19;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,0.2) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(263,45%,30%,0.15) 0, transparent 50%);
            min-height: 100vh;
        }
        
        .glass-card {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .glowing-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glowing-btn:hover {
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
            transform: translateY(-1px);
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="flex flex-col min-h-screen text-slate-100 font-sans antialiased">

    <!-- Header / Navbar -->
    <header class="w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
        <a href="/" class="flex items-center space-x-2 text-xl font-bold tracking-tight text-white hover:opacity-90 transition-opacity">
            <span class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-transparent bg-clip-text">PARSABE</span>
            <span class="text-slate-400 font-medium text-sm">/ CS Portal</span>
        </a>
        <div class="flex items-center space-x-4">
            <a href="{{ route('cs.certificates.index') }}" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">
                Certificate Portal
            </a>
            <span class="text-slate-600">|</span>
            <a href="/" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">
                ← Back to main site
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-xl fade-in">
            
            <!-- App Logo / Title -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center p-3 bg-indigo-500/10 rounded-2xl border border-indigo-500/20 mb-4">
                    <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    Specialist Feedback Portal
                </h1>
                <p class="mt-2 text-sm text-slate-400">
                    Share your ideas, feedback, questions, and confirm if you received your files
                </p>
            </div>

            @if (count($errors) > 0)
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-200 text-sm">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-rose-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <span class="font-semibold block mb-1">Please fix the following:</span>
                            <ul class="list-disc list-inside">
                                @foreach (is_array($errors) ? $errors : $errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-200 text-sm">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-emerald-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <span class="font-semibold block text-base mb-1">Success!</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Feedback Form Card -->
            <div class="glass-card rounded-3xl p-8 sm:p-10">
                <form action="{{ route('cs.feedback.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-slate-300 mb-2">
                                First Name
                            </label>
                            <input id="first_name" name="first_name" type="text" required value="{{ old('first_name') }}"
                                class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700/80 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                placeholder="Your first name">
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-slate-300 mb-2">
                                Last Name
                            </label>
                            <input id="last_name" name="last_name" type="text" required value="{{ old('last_name') }}"
                                class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700/80 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                placeholder="Your last name">
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                            Email Address
                        </label>
                        <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}"
                            class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700/80 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="your.email@example.com">
                        <p class="mt-1.5 text-xs text-slate-500">
                            Must match your Campus Specialist registration details.
                        </p>
                    </div>

                    <!-- Ideas -->
                    <div>
                        <label for="ideas" class="block text-sm font-medium text-slate-300 mb-2">
                            Do you have any suggestions to make the process of the career fair much better?
                        </label>
                        <textarea id="ideas" name="ideas" rows="3" required
                            class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700/80 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="Share your suggestions on how we can improve the career fair organization, scheduling, or execution...">{{ old('ideas') }}</textarea>
                    </div>

                    <!-- General Feedback -->
                    <div>
                        <label for="feedback" class="block text-sm font-medium text-slate-300 mb-2">
                            General Feedback
                        </label>
                        <textarea id="feedback" name="feedback" rows="3" required
                            class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700/80 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="How was your experience as a specialist? Let us know.">{{ old('feedback') }}</textarea>
                    </div>

                    <!-- Questions -->
                    <div>
                        <label for="questions" class="block text-sm font-medium text-slate-300 mb-2">
                            Questions
                        </label>
                        <textarea id="questions" name="questions" rows="3" required
                            class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700/80 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="Any questions about certificates, events, or next steps?">{{ old('questions') }}</textarea>
                    </div>

                    <!-- Received All Files -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-3">
                            Have you successfully received all of your certificates and files?
                        </label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="radio" name="received_all_files" value="yes" required {{ old('received_all_files') === 'yes' ? 'checked' : '' }}
                                    class="w-5 h-5 text-indigo-600 bg-slate-900/50 border-slate-700 focus:ring-indigo-500 focus:ring-offset-slate-900 focus:ring-2">
                                <span class="text-slate-300 group-hover:text-white transition-colors">Yes, I received everything</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="radio" name="received_all_files" value="no" required {{ old('received_all_files') === 'no' ? 'checked' : '' }}
                                    class="w-5 h-5 text-indigo-600 bg-slate-900/50 border-slate-700 focus:ring-indigo-500 focus:ring-offset-slate-900 focus:ring-2">
                                <span class="text-slate-300 group-hover:text-white transition-colors">No, files are missing/corrupted</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="glowing-btn w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-slate-900 transition-all">
                            Submit Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-7xl mx-auto px-6 py-6 text-center text-slate-500 text-xs mt-auto">
        &copy; 2026 Parsa Besharat. All rights reserved.
    </footer>
</body>
</html>
