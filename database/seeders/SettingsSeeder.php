<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // General Site Settings
        $generalSettings = [
            [
                'key' => 'site_title',
                'value' => 'Blog Platform',
                'group' => 'general',
                'type' => 'text',
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Share your knowledge with the world',
                'group' => 'general',
                'type' => 'text',
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'group' => 'general',
                'type' => 'file',
            ],
            [
                'key' => 'site_favicon',
                'value' => null,
                'group' => 'general',
                'type' => 'file',
            ],
            [
                'key' => 'footer_text',
                'value' => '© ' . date('Y') . ' Blog Platform. All rights reserved.',
                'group' => 'general',
                'type' => 'textarea',
            ],
            [
                'key' => 'posts_per_page',
                'value' => '10',
                'group' => 'general',
                'type' => 'number',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'group' => 'general',
                'type' => 'boolean',
            ],
        ];

        // SEO Settings
        $seoSettings = [
            [
                'key' => 'meta_title_format',
                'value' => '%title% | %site_title%',
                'group' => 'seo',
                'type' => 'text',
            ],
            [
                'key' => 'meta_description',
                'value' => 'A platform for sharing your knowledge and ideas with the world.',
                'group' => 'seo',
                'type' => 'textarea',
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'blog, articles, knowledge sharing',
                'group' => 'seo',
                'type' => 'textarea',
            ],
            [
                'key' => 'robots_txt',
                'value' => "User-agent: *\nDisallow: /admin/\nDisallow: /login\nDisallow: /register",
                'group' => 'seo',
                'type' => 'textarea',
            ],
            [
                'key' => 'generate_sitemap',
                'value' => '1',
                'group' => 'seo',
                'type' => 'boolean',
            ],
            [
                'key' => 'canonical_url',
                'value' => null,
                'group' => 'seo',
                'type' => 'text',
            ],
        ];

        // Social Media Settings
        $socialSettings = [
            [
                'key' => 'og_title_format',
                'value' => '%title% | %site_title%',
                'group' => 'social',
                'type' => 'text',
            ],
            [
                'key' => 'og_description',
                'value' => 'A platform for sharing your knowledge and ideas with the world.',
                'group' => 'social',
                'type' => 'textarea',
            ],
            [
                'key' => 'og_image',
                'value' => null,
                'group' => 'social',
                'type' => 'file',
            ],
            [
                'key' => 'twitter_card_type',
                'value' => 'summary_large_image',
                'group' => 'social',
                'type' => 'select',
                'options' => json_encode(['summary' => 'Summary', 'summary_large_image' => 'Summary with Large Image']),
            ],
            [
                'key' => 'twitter_site',
                'value' => '@yoursitename',
                'group' => 'social',
                'type' => 'text',
            ],
            [
                'key' => 'facebook_page_url',
                'value' => 'https://facebook.com/yourpage',
                'group' => 'social',
                'type' => 'text',
            ],
            [
                'key' => 'instagram_page_url',
                'value' => 'https://instagram.com/yourpage',
                'group' => 'social',
                'type' => 'text',
            ],
            [
                'key' => 'twitter_page_url',
                'value' => 'https://twitter.com/yourpage',
                'group' => 'social',
                'type' => 'text',
            ],
        ];

        // Analytics Settings
        $analyticsSettings = [
            [
                'key' => 'google_analytics_id',
                'value' => null,
                'group' => 'analytics',
                'type' => 'text',
            ],
            [
                'key' => 'google_tag_manager_id',
                'value' => null,
                'group' => 'analytics',
                'type' => 'text',
            ],
            [
                'key' => 'facebook_pixel_id',
                'value' => null,
                'group' => 'analytics',
                'type' => 'text',
            ],
        ];

        // Merge all settings and save to database
        $allSettings = array_merge($generalSettings, $seoSettings, $socialSettings, $analyticsSettings);

        foreach ($allSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
} 