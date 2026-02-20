<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlackWall Project- Parsa Besharat</title>

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

        <main class="flex-1 overflow-y-auto p-6 md:p-10 scroll-smooth">
            <div class="max-w-4xl mx-auto space-y-10">
                
                <!-- Header -->
                <div class="text-center space-y-6">
                    <h1 class="text-4xl md:text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400">
                        BlackWall
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300 font-medium">
                        A Safety Line Against Rogue AI
                    </p>
                    <div class="flex justify-center">
                        <img src="https://github.com/parsabe/BlackWall/blob/master/baclwall-poster.png?raw=true" 
                             alt="BlackWall Poster" 
                             class="rounded-2xl shadow-lg max-w-full h-auto border border-gray-200 dark:border-gray-700">
                    </div>
                      <div class="flex justify-center">
                        <a href="https://github.com/parsabe/BlackWall" target="_blank" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-full font-bold shadow-lg hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                            View Source Code
                        </a>
                    </div>
                </div>

                <!-- Overview -->
                <section class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 pb-3">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Overview</h2>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-3 text-center group">
                            <a href="https://youtu.be/y0_I8nw1jCA" target="_blank" class="block relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                <img src="https://img.youtube.com/vi/y0_I8nw1jCA/maxresdefault.jpg" alt="BlackWall intro" class="w-full object-cover aspect-video">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center pl-1">
                                        <svg class="w-6 h-6 text-gray-900" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </a>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Click to watch the BlackWall intro</p>
                        </div>

                        <div class="space-y-3 text-center group">
                            <a href="https://youtu.be/OFX2ZUcbHWI" target="_blank" class="block relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                <img src="https://img.youtube.com/vi/OFX2ZUcbHWI/maxresdefault.jpg" alt="Video preview" class="w-full object-cover aspect-video">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center pl-1">
                                        <svg class="w-6 h-6 text-gray-900" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </a>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Click to watch the video</p>
                        </div>

                        
                    </div>
                </section>

                <!-- Abstract -->
                <section class="space-y-4">
                    <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 pb-3">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Abstract</h2>
                    </div>
                    <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed text-justify">
                        <p>
                            The increasing integration of social media and conversational AI into daily life has intensified concerns around the spread of harmful, illegal, and psychologically sensitive content, including suicidal ideation, self-harm, depression, and other forms of negative influence. Recent cases of emotional attachment to chatbots and instances where AI systems have unintentionally misled users on mental health issues highlight the urgency of reliable safety mechanisms. This paper presents Blackwall, a domain-aware and interpretable framework designed to identify, assess, and rank high-risk content across online platforms. By operating across heterogeneous data sources and providing transparent risk explanations, Blackwall supports early intervention, responsible moderation, and safer human–AI interaction. The framework aims to contribute toward ethically grounded content safety systems that can mitigate psychological harm while preserving transparency and trust.
                        </p>
                    </div>
                </section>

                <!-- Introduction -->
                <section id="introduction" class="space-y-4">
                    <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 pb-3">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Introduction</h2>
                    </div>
                    
                    <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed text-justify space-y-4">
                        <p>
                            The <strong>Blackwall</strong> project focuses on developing a reliable and interpretable artificial
                            intelligence system for identifying and assessing
                            <strong>suicidal ideation alongside other harmful, illegal, and psychologically sensitive content</strong>,
                            including self-harm, depression, and broader forms of negative or misleading influence.
                            Rather than operating on social media platforms directly, Blackwall is trained and evaluated on curated
                            datasets originating from online environments, with the primary objective of preventing intelligent systems
                            from generating, reinforcing, or amplifying dangerous content.
                        </p>
                        <p>
                            The system is designed not only to distinguish between low-risk and high-risk content, but also to provide
                            transparent and explainable risk assessments that can support mental health professionals, safety moderators,
                            and the development of responsible AI-driven tools.
                        </p>
                        <p>
                            Blackwall is evaluated using a Twitter-derived dataset consisting of short text samples labeled as
                            <em>“Not Suicide Post”</em> or <em>“Potential Suicide Post”</em>, and a Reddit SuicideWatch–derived dataset
                            containing longer-form texts annotated with a severity score ranging from 0 to 5, reflecting increasing
                            levels of suicide risk.
                        </p>
                        
                        <div class="flex justify-center py-8">
                            <img src="{{ asset('images/mindmap.png') }}" alt="Mindmap" class="rounded-xl shadow-md max-w-full h-auto bg-white p-2">
                        </div>

                        <p>
                            To improve robustness under distribution shifts between heterogeneous data sources, Blackwall incorporates
                            <strong>Domain Adversarial Training (DAT)</strong> to reduce domain-specific bias and encourage
                            domain-invariant representations. In addition, we include a <strong>PAC-oriented evaluation and conditioning
                            strategy</strong> to assess whether learned decision rules generalize consistently under standard learnability
                            assumptions, supporting stable performance as data scale and domains vary.
                        </p>
                        <p>
                            Overall, Blackwall aims to demonstrate how
                            domain-robust and explainable AI can serve as a protective mechanism against unsafe or rogue behavior in
                            intelligent systems, contributing to safer human–AI interaction while adhering to ethical and technical
                            robustness requirements.
                        </p>
                    </div>
                </section>

            </div>
        </main>
    </div>
</body>
</html>