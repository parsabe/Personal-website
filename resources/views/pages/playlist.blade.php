<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parsa Besharat - Researcher & AI Engineer</title>

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

        @include('sidebar')

        <main class="flex-1 p-8 lg:p-14 relative overflow-y-auto">
            <div class="relative z-10 mt-12 lg:mt-0">
                
                <div class="mb-16">
                    <span
                        class="inline-flex items-center gap-2 px-4 py-1.5 ios-glass text-gray-900 dark:text-white rounded-full text-sm font-bold mb-6">
                         🎵 MY PLAYLIST
                    </span>

                    <h1
                        class="text-4xl lg:text-5xl font-extrabold mb-8 tracking-tight text-gray-900 dark:text-white drop-shadow-sm">
                        Favorite <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500 dark:from-green-400 dark:to-blue-400">Tracks & Mixes.</span>
                    </h1>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-10">
                        
                        <div class="ios-glass p-3 rounded-3xl border border-white/20 dark:border-white/10 shadow-lg">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 ml-2 flex items-center gap-2">
                                <span class="text-[#1DB954]">🎧</span> Spotify
                            </h3>
                            <iframe data-testid="embed-iframe" style="border-radius:12px" src="https://open.spotify.com/embed/playlist/1wbC8swsFWpJalHbfq3yF6?utm_source=generator" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                        </div>

                        <div class="ios-glass p-3 rounded-3xl border border-white/20 dark:border-white/10 shadow-lg">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 ml-2 flex items-center gap-2">
                                <span class="text-[#FF0000]">▶️</span> YouTube
                            </h3>
                            <iframe width="100%" height="352" style="border-radius:12px" src="https://www.youtube.com/embed/videoseries?list=PLDeaK_8P01I8Riyis-oppzFdIgbC7Wu79" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>

                    </div>
                    
                    <div class="flex">
                        <a href="https://youtube.com/playlist?list=PLDeaK_8P01I8Riyis-oppzFdIgbC7Wu79&si=VUTi55ohf40qvmSY" target="_blank" class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition transform flex items-center gap-2">
                            Open in YouTube App ↗
                        </a>
                    </div>
                </div>

                <div id="work" class="mb-16">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
                        <span class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg text-orange-600 dark:text-orange-400 text-xl">💼</span>
                        Work Experience
                    </h3>
                    
                    <div class="relative border-l-2 border-gray-200 dark:border-gray-800 ml-3.5 space-y-12 pb-4">
                        
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1.5 h-5 w-5 rounded-full border-4 border-white dark:border-gray-900 bg-orange-500"></div>
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1">
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white">TU Bergakademie Freiberg</h4>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Part-time · 1 yr 5 mos</span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Freiberg, Saxony, Germany</p>
                            
                            <div class="space-y-8">
                                <div class="relative border-l-2 border-gray-300 dark:border-gray-700 ml-1 pl-6">
                                    <div class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200">Head Research Assistant</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Apr 2025 – Present · 10 mos · On-site</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['Local LLMs', 'Agentic AI', 'Azure Data Factory', 'Azure Data Lake', 'Azure Databricks', 'Azure ML', 'Azure Data Warehouse', 'Microsoft Fabric'] as $skill)
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="relative border-l-2 border-gray-300 dark:border-gray-700 ml-1 pl-6">
                                    <div class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200">Working Student Data Scientist</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Jan 2025 – Apr 2025 · 4 mos · Hybrid</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">PyTorch</span>
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">Machine Learning</span>
                                        @foreach(['Deep Learning', 'NI LabVIEW'] as $skill)
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="relative border-l-2 border-gray-300 dark:border-gray-700 ml-1 pl-6">
                                    <div class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200">Working Student Software Engineer</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Sep 2024 – Dec 2024 · 4 mos · On-site</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">Software Development</span>
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">Deep Learning</span>
                                        @foreach(['DBT', 'Kali Linux', 'Software Development', 'Snowflake', 'Deep Learning', 'NI LabVIEW'] as $skill)
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1.5 h-5 w-5 rounded-full border-4 border-white dark:border-gray-900 bg-orange-500"></div>
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1">
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white">SAPCO</h4>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Full-time · 1 yr 9 mos</span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Tehran, Iran · On-site</p>
                            
                            <div class="space-y-8">
                                <div class="relative border-l-2 border-gray-300 dark:border-gray-700 ml-1 pl-6">
                                    <div class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200">AI Engineer</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Sep 2022 – Sep 2023 · 1 yr 1 mo</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">PyTorch</span>
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">SQL</span>
                                        @foreach(['Machine Learning', 'NLP', 'Responsible AI', 'Local LLMs', 'Generative AI', 'Deep Learning'] as $skill)
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="relative border-l-2 border-gray-300 dark:border-gray-700 ml-1 pl-6">
                                    <div class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200">Data Scientist</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Jan 2022 – Sep 2022 · 9 mos</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">Software Development</span>
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">Web Servers</span>
                                        @foreach(['Machine Learning', 'Power BI', 'Google Data Studio', 'Deep Learning', 'Python'] as $skill)
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1.5 h-5 w-5 rounded-full border-4 border-white dark:border-gray-900 bg-orange-500"></div>
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1">
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white">ApexTeam</h4>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Part-time · 2 yrs 11 mos</span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Karaj, Iran · Hybrid</p>
                            
                            <div class="space-y-8">
                                <div class="relative border-l-2 border-gray-300 dark:border-gray-700 ml-1 pl-6">
                                    <div class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200">Data Scientist</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">May 2020 – Jan 2022 · 1 yr 9 mos</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">Web Servers</span>
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">Microsoft SQL Server</span>
                                        @foreach(['Data Analysis', 'Deep Learning', 'MySQL / PostgreSQL'] as $skill)
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="relative border-l-2 border-gray-300 dark:border-gray-700 ml-1 pl-6">
                                    <div class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200">Software Engineer</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Mar 2019 – May 2020 · 1 yr 3 mos</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">Web Servers</span>
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">Bash</span>
                                        @foreach(['PHP', 'Laravel', 'Backend Development', 'Nginx'] as $skill)
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1.5 h-5 w-5 rounded-full border-4 border-white dark:border-gray-900 bg-orange-500"></div>
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1">
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white">Islamic Azad University</h4>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Part-time · 6 mos</span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Karaj, Iran · On-site</p>
                            
                            <div class="space-y-8">
                                <div class="relative border-l-2 border-gray-300 dark:border-gray-700 ml-1 pl-6">
                                    <div class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200">Research Assistant</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Jan 2019 – Mar 2019 · 3 mos</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">SQL</span>
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">IT Infrastructure</span>
                                        @foreach(['IT Infrastructure', 'SQL', 'MySQL'] as $skill)
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="relative border-l-2 border-gray-300 dark:border-gray-700 ml-1 pl-6">
                                    <div class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200">Teacher Assistant</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Oct 2018 – Dec 2018 · 3 mos</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">SQL</span>
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">PHP</span>
                                        @foreach(['SQL', 'PHP', 'Python'] as $skill)
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="education" class="mb-16">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
                        <span class="p-2 bg-pink-100 dark:bg-pink-900/30 rounded-lg text-pink-600 dark:text-pink-400 text-xl">🎓</span>
                        Education
                    </h3>
                    
                    <div class="relative border-l-2 border-gray-300 dark:border-gray-700 ml-3.5 space-y-10 pb-4">
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1.5 h-5 w-5 rounded-full border-4 border-white dark:border-gray-900 bg-pink-500"></div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white">TU Bergakademie Freiberg</h4>
                            <p class="text-base font-medium text-gray-700 dark:text-gray-300 mt-1">Master's Degree – Data Science and Data Processing & AI Technology</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Oct 2023 – Present · Freiberg, Germany</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2"><strong class="text-gray-900 dark:text-white">Key Topics:</strong> Data Science, Machine Learning and +3 skills</p>
                        </div>

                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1.5 h-5 w-5 rounded-full border-4 border-white dark:border-gray-900 bg-pink-500"></div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white">Islamic Azad University</h4>
                            <p class="text-base font-medium text-gray-700 dark:text-gray-300 mt-1">Bachelor's Degree – Computer Engineering</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Oct 2018 – Apr 2023 · Karaj, Iran</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2"><strong class="text-gray-900 dark:text-white">Key Topics:</strong> Microsoft SQL Server, PostgreSQL and +9 skills</p>
                        </div>
                    </div>
                </div>

                <div id="certificates" class="mb-16">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
                        <span class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400 text-xl">📜</span>
                        Certificates
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $certs = [
                                ['title' => 'Microsoft Azure Fundamentals (AZ-900)', 'issuer' => 'Microsoft', 'date' => 'Issued July 2025', 'id' => 'Credential ID: 64228f9b79adc...'],
                                ['title' => 'CS50: Introduction to Computer Science', 'issuer' => 'Harvard University', 'date' => 'Issued March 2024', 'id' => ''],
                                ['title' => 'Big Data Programming Languages', 'issuer' => 'Udemy', 'date' => 'Issued December 2022', 'id' => 'Credential ID: 0004'],
                                ['title' => 'CS50’s Introduction to AI with Python', 'issuer' => 'Harvard University', 'date' => 'Issued April 2024', 'id' => ''],
                                ['title' => 'Artificial Intelligence and Business Strategy', 'issuer' => 'Project Management Institute', 'date' => 'Issued July 2025', 'skills' => 'Business Strategy • AI for Business'],
                                ['title' => 'AZ-900 Microsoft Azure Fundamentals', 'issuer' => 'Microsoft', 'date' => 'Issued July 2025', 'skills' => 'Azure Products • Microsoft Fabric • IAM • Cloud Compute • Storage • ML Studio'],
                                ['title' => 'Microsoft Power BI Data Analyst Associate (PL-300)', 'issuer' => 'Microsoft', 'date' => 'Issued July 2025', 'skills' => 'Microsoft Fabric • Power BI • Linux • Python • ML • Data Science'],
                                ['title' => 'AI with Python — CS50', 'issuer' => 'Harvard University', 'date' => 'Issued April 2024', 'skills' => 'SQL • Neural Networks • Search/Optimization'],
                            ];
                        @endphp
                        @foreach($certs as $cert)
                        <div class="ios-glass p-5 rounded-2xl border border-white/20 dark:border-white/10 hover:scale-[1.02] transition-transform duration-300">
                            <h5 class="font-bold text-gray-900 dark:text-white mb-1">{{ $cert['title'] }}</h5>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $cert['issuer'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $cert['date'] }}</p>
                            @if($cert['id'])
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 truncate">{{ $cert['id'] }}</p>
                            @endif
                            @if(isset($cert['skills']))
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <strong class="text-gray-700 dark:text-gray-300">Skills:</strong> {{ $cert['skills'] }}
                                </p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <div id="patents" class="mb-16">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
                        <span class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400 text-xl">💡</span>
                        Patents
                    </h3>
                    <div class="grid grid-cols-1 gap-6">
                        @php
                            $patents = [
                                [
                                    'title' => 'Netrunner Architecture for Rogue Agent Mitigation',
                                    'meta' => 'Patent: TU7823436 • Issued Jan 21, 2026',
                                    'desc' => 'Honored for the "BlackWall" architecture, which implements asynchronous traffic analysis to autonomously detect and block rogue AI behaviors.'
                                ],
                                [
                                    'title' => 'Data-Driven Scientific Discovery',
                                    'meta' => 'Patent: TU7825912 • Issued Sep 9, 2025',
                                    'desc' => 'Recognized for a SINDy-based financial forecasting proposal that successfully integrates differential equations into dynamic economic modeling.'
                                ],
                                [
                                    'title' => 'From Words to Feelings: LLM-Based Emotion AI',
                                    'meta' => 'Patent App: TU7824224 • Filed Jun 25, 2025',
                                    'desc' => 'Advanced LLM framework capable of recognizing and predicting human emotions from diverse data modalities (text, speech, video) using cross-modal embeddings and attention-based fusion.'
                                ]
                            ];
                        @endphp
                        @foreach($patents as $patent)
                        <div class="ios-glass p-6 rounded-2xl border border-white/20 dark:border-white/10 hover:scale-[1.01] transition-transform duration-300">
                            <h5 class="font-bold text-gray-900 dark:text-white text-lg mb-2">{{ $patent['title'] }}</h5>
                            <p class="text-sm font-medium text-orange-600 dark:text-orange-400 mb-3">{{ $patent['meta'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $patent['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div id="skills" class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
                        <span class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400 text-xl">🛠️</span>
                        Skills
                    </h3>
                    
                    @php
                        $skillCategories = [
                            'Industry Knowledge' => ['Data Science', 'Machine Learning', 'Data Analysis', 'Software Development', 'IT Infrastructure Management', 'Computer Networking', 'SEO'],
                            'Tools & Technologies' => ['AWS', 'Microsoft SQL Server', 'PostgreSQL', 'MySQL', 'Oracle Database', 'Power BI', 'Jupyter', 'Git', 'Docker', 'Nginx', 'Linux', 'Bash', 'PHP', 'Laravel', 'Kubernetes', 'Azure IOT', 'Azure ML', 'Azure DevOps', 'Azure Databricks', 'Azure Data Factory', 'Office Suite'],
                            'Data Science & AI' => ['Machine Learning', 'Deep Learning', 'Python', 'PyTorch', 'Scikit-learn', 'Matplotlib & Seaborn', 'Tableau'],
                            'Networking & Systems' => ['CompTIA Network+', 'MCSE: Server Infrastructure', 'Cisco Systems Products', 'Cisco CCNA SP']
                        ];
                    @endphp

                    <div class="space-y-8">
                        @foreach($skillCategories as $category => $skills)
                        <div>
                            <h5 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">{{ $category }}</h5>
                            <div class="flex flex-wrap gap-2">
                                @foreach($skills as $skill)
                                <span class="px-3 py-1.5 rounded-lg text-sm font-medium bg-white/50 dark:bg-black/30 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 hover:bg-white dark:hover:bg-black/50 transition-colors cursor-default">
                                    {{ $skill }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </main>

    </div>

</body>

</html>


