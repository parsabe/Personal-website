<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Parsa Besharat</title>

    <meta name="description" content="Parsa Besharat is an Iranian Researcher and AI Engineer. He is currently pursuing his
    MS.c degree in Data Science at the TU Freiberg University in Sachsen, Germany.">
    <meta name="author" content="Parsa Besharat">
    <meta name="keywords"
        content="Parsa Besharat, Researcher, AI Engineer, Data Scientist, Machine Learning, Deep Learning, Natural Language Processing, Computer Vision, TU Freiberg University, Germany">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">



    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.tailwind = { config: { darkMode: 'class' } };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
</head>

<body
    class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-4 lg:p-10 min-h-screen relative overflow-x-hidden">

    <div id="main-container"
        class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden h-[85vh] z-10 transition-colors duration-700">

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

        <main class="flex-1 p-6 lg:p-10 overflow-y-auto scrollbar-hide">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">Search Projects</h1>

                <form action="{{ route('search') }}" method="GET" class="mb-8">
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search projects..."
                            class="w-full px-4 py-3 rounded-xl bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800 dark:text-gray-100 placeholder-gray-500">
                        <button type="submit" class="absolute right-3 top-3 text-gray-500 hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>

                @if(isset($projects) && $projects->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($projects as $project)
                            <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                                <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                                    <img src="{{ asset($project->image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                </div>
                                <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">{{ $project->title }}</h2>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                                    {{ $project->description }}
                                </p>
                                <a href="{{ route($project->route_name) }}" class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                                    Read more
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400 text-center mt-10">No projects found.</p>
                @endif
            </div>
        </main>
    </div>
</body>
</html>

        @include('sidebar')