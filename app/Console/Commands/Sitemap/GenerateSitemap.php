<?php

namespace App\Console\Commands\Sitemap;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap for Google map crawler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SitemapGenerator::create('https://musicteachersaid.com/')
            ->writeToFile(public_path('sitemap.xml'));
    }
}
