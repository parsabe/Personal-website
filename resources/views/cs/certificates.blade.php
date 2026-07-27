<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CS Career Fair - Certificate Portal</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script>window.tailwind = { config: { darkMode: 'class' } };</script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Premium Styles -->
    <link rel="stylesheet" href="{{ asset('css/cs-certificates.css') }}">
</head>
<body class="flex flex-col min-h-screen text-slate-100 font-sans antialiased">

    <!-- Header / Navbar -->
    <header class="w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
        <a href="/" class="flex items-center space-x-2 text-xl font-bold tracking-tight text-white hover:opacity-90 transition-opacity">
            <span class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-transparent bg-clip-text">PARSABE</span>
            <span class="text-slate-400 font-medium text-sm">/ CS Portal</span>
        </a>
        <a href="/" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">
            ← Back to main site
        </a>
    </header>

    <!-- Main Container -->
    <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-lg fade-in">
            
            <!-- App Logo / Title -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center p-3 bg-indigo-500/10 rounded-2xl border border-indigo-500/20 mb-4">
                    <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    CS Career Fair
                </h1>
                <p class="mt-2 text-sm text-slate-400">
                    Retrieve and download your official participation certificates
                </p>
            </div>

            @if (count($errors) > 0)
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-200 text-sm">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-rose-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ is_array($errors) ? reset($errors) : $errors->first() }}</span>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-200 text-sm">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-emerald-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Search Form / Download Card -->
            <div class="glass-card rounded-3xl p-8 sm:p-10">
                
                @if(!$student)
                    <!-- Search Step -->
                    <form action="{{ route('cs.certificates.search') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                                Enter your Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}"
                                    class="block w-full pl-11 pr-4 py-3.5 bg-slate-900/50 border border-slate-700/80 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="your.email@example.com">
                            </div>
                            <p class="mt-2 text-xs text-slate-500">
                                Use the email address you signed up with.
                            </p>
                        </div>

                        <div>
                            <button type="submit" class="glowing-btn w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-slate-900 transition-all">
                                Verify & Continue
                            </button>
                        </div>
                    </form>
                @else
                    <!-- Download Step -->
                    <div class="space-y-6">
                        <div class="text-center pb-4 border-b border-slate-800">
                            <span class="text-xs font-semibold tracking-wider uppercase px-2.5 py-1 bg-indigo-500/10 text-indigo-400 rounded-full border border-indigo-500/20">
                                Verified
                            </span>
                            <h2 class="mt-3 text-xl font-bold text-white">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </h2>
                            <p class="text-sm text-slate-400 mt-1">
                                {{ $student->email }}
                            </p>
                        </div>

                        <div class="space-y-4">
                            <!-- Certificate Card -->
                            @if($student->downloaded_cert)
                                <div class="flex items-center p-4 bg-slate-900/10 border border-slate-800/40 rounded-2xl opacity-40 select-none cursor-not-allowed">
                                    <div class="p-3 bg-slate-800 text-slate-500 rounded-xl">
                                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 flex-grow text-left">
                                        <h3 class="text-sm font-semibold text-slate-400">
                                            Certificate Downloaded
                                        </h3>
                                        <p class="text-xs text-slate-600 mt-0.5">
                                            Single download limit reached
                                        </p>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('cs.certificates.download') }}"
                                    class="flex items-center p-4 bg-slate-900/30 hover:bg-slate-900/60 border border-slate-800 hover:border-slate-700/80 rounded-2xl transition-all group">
                                    <div class="p-3 bg-red-500/10 text-red-400 rounded-xl group-hover:bg-red-500/20 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 flex-grow text-left">
                                        <h3 class="text-sm font-semibold text-white group-hover:text-indigo-300 transition-colors">
                                            Download Certificate
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            Personalized PDF document
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                            @endif

                            <!-- Images Zip Card -->
                            @if($student->downloaded_zip)
                                <div class="flex items-center p-4 bg-slate-900/10 border border-slate-800/40 rounded-2xl opacity-40 select-none cursor-not-allowed">
                                    <div class="p-3 bg-slate-800 text-slate-500 rounded-xl">
                                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 flex-grow text-left">
                                        <h3 class="text-sm font-semibold text-slate-400">
                                            Images Archive Downloaded
                                        </h3>
                                        <p class="text-xs text-slate-600 mt-0.5">
                                            Single download limit reached
                                        </p>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('cs.certificates.download-images') }}"
                                    class="flex items-center p-4 bg-slate-900/30 hover:bg-slate-900/60 border border-slate-800 hover:border-slate-700/80 rounded-2xl transition-all group">
                                    <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl group-hover:bg-indigo-500/20 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 flex-grow text-left">
                                        <h3 class="text-sm font-semibold text-white group-hover:text-indigo-300 transition-colors">
                                            Download Images Archive
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            images.zip (Photos from event)
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <!-- Reset / Change Email -->
                        <form action="{{ route('cs.certificates.clear') }}" method="POST" class="pt-4 border-t border-slate-800 flex justify-between items-center">
                            @csrf
                            <button type="submit" class="text-xs text-slate-500 hover:text-slate-300 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Change Email
                            </button>
                            <span class="text-xs text-slate-600">
                                Protected Portal
                            </span>
                        </form>
                    </div>
                @endif
                
            </div>
            
            <!-- Footer -->
            <footer class="mt-8 text-center text-xs text-slate-600">
                <p>&copy; {{ date('Y') }} Parsabe. All rights reserved.</p>
            </footer>
            
        </div>
    </main>

</body>
</html>
