<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // 1. Array of your named routes from the menu
        $menuRoutes = [
            'home',
            'about',
            'contact',
            'projects',
            'publications',
            'myplaylist',
            'search', 
            'books',
            'vpn-server',
            'fun',
            'blog',
        ];

        // 2. Loop through the menu and add them to the sitemap
        foreach ($menuRoutes as $routeName) {
            $sitemap->add(
                Url::create(route($routeName))
                    ->setPriority($routeName === 'home' ? 1.0 : 0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        }

        // 3. Save to public folder
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully with all menu items!');
    }
}