<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        ];

        DB::table('projects')->insert($projects);
    }
}