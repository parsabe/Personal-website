<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $projects = [
            [
                'title' => 'BlackWall',
                'description' => 'A domain-aware and interpretable framework designed to identify, assess, and rank high-risk content across online platforms.',
                'image' => 'images/blackwall.png',
                'route_name' => 'projects.blackwall',
            ],
            [
                'title' => 'MLMatrix',
                'description' => 'This repository features four in-depth articles covering a range of cutting-edge technologies and their applications.',
                'image' => 'images/mlmatrix.png',
                'route_name' => 'projects.mlmatrix',
            ],
            [
                'title' => 'FunRoot',
                'description' => 'This repository contains my projects that I do just for fun.',
                'image' => 'images/funroot.png',
                'route_name' => 'projects.funroot',
            ],
            [
                'title' => 'Ceasar Toolkit',
                'description' => 'Ceasar Cipher Toolkit is a free, open-source CLI framework for encoding and decoding files using the classic Ceasar cipher.',
                'image' => 'images/ceasar.png',
                'route_name' => 'projects.ceasartoolkit',
            ],
            [
                'title' => 'SCP',
                'description' => 'This project provides a modular, extensible, and research-oriented deep learning pipeline for image classification.',
                'image' => 'images/scp.png',
                'route_name' => 'projects.scp',
            ],
            [
                'title' => 'NetNexus',
                'description' => 'A collection of innovative web projects including a dynamic website, an engaging online riddle game, a social media platform, and a chat portal.',
                'image' => 'images/netnexus.jpg',
                'route_name' => 'projects.netnexus',
            ],
            [
                'title' => 'Parsai',
                'description' => 'Parsai combines a powerful VS Code extension and a Telegram bot to provide versatile coding assistance using OpenAI\'s GPT-4.',
                'image' => 'images/parsai.jpg',
                'route_name' => 'projects.parsai',
            ],
            [
                'title' => 'HounaarToolkit',
                'description' => 'HounaarToolkit is a versatile Python toolkit that provides a set of tools for various tasks, including data analysis, YouTube video downloading, and more.',
                'image' => 'images/hounaar.png',
                'route_name' => 'projects.hounaartoolkit',
            ],
            [
                'title' => 'Sandika',
                'description' => 'Sandika is an Online Simple Social media platform where poeple can post texts, images, videos, solve riddles and some more.',
                'image' => 'images/sandika.jpg',
                'route_name' => 'projects.sandika',
            ],
            // Publications
            [
                'title' => 'BlackWall - Protect an AI from going rogue via an AI',
                'description' => 'The increasing integration of social media and conversational AI into daily life has intensified concerns around the spread of harmful, illegal, and psychologically sensitive content.',
                'image' => 'images/blackwall.png',
                'route_name' => 'publications.blackwall_paper',
            ],
            [
                'title' => 'Moodium: From Words to Feelings',
                'description' => 'Emotion recognition is a critical component of affective computing. This paper proposes a culturally aware, LLM-integrated framework that fuses audio, visual, and textual data.',
                'image' => 'images/moodium.png',
                'route_name' => 'publications.moodium',
            ],
            [
                'title' => 'Financial Forecasting Equations with Scientific Machine Learning',
                'description' => 'Financial markets are inherently nonlinear, dynamic, and noisy. This document explores a novel approach to financial forecasting by integrating Scientific Machine Learning (SciML).',
                'image' => 'images/scm.jpeg',
                'route_name' => 'publications.scm',
            ],
            [
                'title' => 'CAPTCHA Unmasked: The Math That Outsmarts Bots',
                'description' => 'It breaks down image processing tricks like distortions, warping, and noise, making CAPTCHAs harder to crack. There’s also a focus on machine learning.',
                'image' => 'images/captcha.png',
                'route_name' => 'publications.captcha',
            ],
            [
                'title' => 'AI and Blockchain, Enhancing Security, Transparency, and Integrity',
                'description' => 'In this seminar, I have explained the main role of AI in Blockchain for Enhancing its Security, Transparency, and Integrity.',
                'image' => 'images/ai-block.png',
                'route_name' => 'publications.ai_blockchain',
            ],
            [
                'title' => 'Synergy of Blockchain',
                'description' => 'This essay explores the synergy between blockchain and artificial intelligence (AI), showcasing their transformative potential in various industries.',
                'image' => 'images/blockchain.jpg',
                'route_name' => 'publications.synergy_blockchain',
            ],
            [
                'title' => 'An Analysis on Vulnerabilities (PHP)',
                'description' => 'The article likely serves as a comprehensive guide on fortifying PHP websites against prevalent security threats.',
                'image' => 'images/vuls.png',
                'route_name' => 'publications.php_vuls',
            ],
            [
                'title' => 'Data Mining Usage in CRM',
                'description' => 'This article describes how recent advancements in data technology and the internet have led to a significant shift in communication and advertising strategies.',
                'image' => 'images/crm.png',
                'route_name' => 'publications.crm',
            ],
            [
                'title' => 'Quantum-dot Cellular Automata (QCA)',
                'description' => 'QCA, focusing on its innovative approach that harnesses the quantum mechanical properties of electrons within quantum dots for data representation and processing.',
                'image' => 'images/qca.png',
                'route_name' => 'publications.qca',
            ],
            // Main Pages
            [
                'title' => 'Home',
                'description' => 'Parsa Besharat is an Iranian Researcher and AI Engineer. Welcome to my personal portfolio.',
                'image' => 'images/profile.jpg',
                'route_name' => 'home',
            ],
            [
                'title' => 'About Me',
                'description' => 'I am currently pursuing a Master\'s degree in Data Science at TU Freiberg. Learn more about my work experience, education, and skills.',
                'image' => 'images/profile.jpg',
                'route_name' => 'about',
            ],
            [
                'title' => 'My Playlist',
                'description' => 'Favorite Tracks & Mixes. Check out my Spotify and YouTube playlists.',
                'image' => 'images/profile.jpg',
                'route_name' => 'myplaylist',
            ],
            [
                'title' => 'VPN Server Store',
                'description' => 'VPN Server Store. Currently under construction.',
                'image' => 'images/profile.jpg',
                'route_name' => 'vpn-server',
            ],
            [
                'title' => 'Support',
                'description' => 'Support page. Currently under construction.',
                'image' => 'images/profile.jpg',
                'route_name' => 'support',
            ],
            [
                'title' => 'Blogs',
                'description' => 'My personal blog. Currently under construction.',
                'image' => 'images/profile.jpg',
                'route_name' => 'blog',
            ],
            [
                'title' => 'Projects Index',
                'description' => 'Browse all my projects including BlackWall, MLMatrix, FunRoot, and more.',
                'image' => 'images/profile.jpg',
                'route_name' => 'projects',
            ],
            [
                'title' => 'Publications Index',
                'description' => 'Browse all my research papers and publications.',
                'image' => 'images/profile.jpg',
                'route_name' => 'publications',
            ],
            [
                'title' => 'Fun Page',
                'description' => 'A page for fun content.',
                'image' => 'images/profile.jpg',
                'route_name' => 'fun',
            ],
            [
                'title' => 'Chat',
                'description' => 'Chat page.',
                'image' => 'images/profile.jpg',
                'route_name' => 'chat',
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['route_name' => $project['route_name']], // Check if exists by route_name
                $project // Update or Create with these values
            );
        }
    }
}