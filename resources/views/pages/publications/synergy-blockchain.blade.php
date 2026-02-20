<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synergy of Blockchain - Parsa Besharat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>window.tailwind = { config: { darkMode: 'class' } };</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
</head>
<body class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-4 lg:p-10 min-h-screen relative overflow-x-hidden">
    <div id="main-container" class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden h-[85vh] z-10 transition-colors duration-700">
        <div class="absolute top-6 right-8 flex items-center gap-5 z-50">
            <button id="theme-toggle" class="p-2.5 rounded-full ios-glass transition hover:scale-110">
                <span id="theme-icon-light" class="hidden text-sm">☀️</span>
                <span id="theme-icon-dark" class="hidden text-sm">🌙</span>
            </button>
        </div>
        @include('sidebar')
        <main class="flex-1 p-8 lg:p-14 relative overflow-y-auto">
            <div class="relative z-10 mt-12 lg:mt-0">
                <h1 class="text-4xl font-bold mb-6">Synergy of Blockchain</h1>
                <p class="mb-6">Sept 20, 2023</p>
                <a href="https://www.researchgate.net/publication/392626581_Exploring_the_Synergy_of_Blockchain_and_Artificial_Intelligence" target="_blank" class="text-blue-500 hover:underline">View on ResearchGate</a>
            </div>
        </main>
    </div>
</body>
</html>