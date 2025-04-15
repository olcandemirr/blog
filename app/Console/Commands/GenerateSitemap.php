<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\Category;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.xml file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // Ana sayfa
        $sitemap .= $this->createUrlTag(url('/'), '1.0', 'daily');

        // Kategoriler
        $categories = Category::all();
        foreach ($categories as $category) {
            $sitemap .= $this->createUrlTag(
                route('categories.show', $category),
                '0.8',
                'weekly'
            );
        }

        // Gönderiler
        $posts = Post::latest()->get();
        foreach ($posts as $post) {
            $sitemap .= $this->createUrlTag(
                route('posts.show', $post),
                '0.6',
                'weekly',
                $post->updated_at->toIso8601String()
            );
        }

        // Diğer statik sayfalar
        $sitemap .= $this->createUrlTag(route('login'), '0.5', 'monthly');
        $sitemap .= $this->createUrlTag(route('register'), '0.5', 'monthly');

        $sitemap .= '</urlset>';

        file_put_contents(public_path('sitemap.xml'), $sitemap);

        $this->info('Sitemap generated successfully: ' . public_path('sitemap.xml'));

        return Command::SUCCESS;
    }

    /**
     * Bir URL etiketi oluşturur
     *
     * @param string $url
     * @param string $priority
     * @param string $changefreq
     * @param string|null $lastmod
     * @return string
     */
    private function createUrlTag($url, $priority = '0.5', $changefreq = 'monthly', $lastmod = null)
    {
        $tag = '  <url>' . PHP_EOL;
        $tag .= '    <loc>' . $url . '</loc>' . PHP_EOL;
        
        if ($lastmod) {
            $tag .= '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
        } else {
            $tag .= '    <lastmod>' . Carbon::now()->toIso8601String() . '</lastmod>' . PHP_EOL;
        }
        
        $tag .= '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
        $tag .= '    <priority>' . $priority . '</priority>' . PHP_EOL;
        $tag .= '  </url>' . PHP_EOL;

        return $tag;
    }
} 