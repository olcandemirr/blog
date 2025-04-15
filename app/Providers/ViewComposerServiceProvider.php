<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // SEO ve OG meta etiketlerini tüm görünümlerde paylaş
        View::composer('layouts.app', function ($view) {
            try {
                // Veritabanı bağlantısını ve tabloları kontrol et
                if (!DB::connection()->getPdo() || !Schema::hasTable('settings')) {
                    // Veritabanı veya tablo yoksa varsayılan değerleri kullan
                    return $view->with([
                        'siteTitle' => 'Blog Platform',
                        'siteDescription' => '',
                        'siteKeywords' => '',
                        'siteLogo' => null,
                        'siteFavicon' => null,
                        'ogTitle' => '%title% | Blog Platform',
                        'ogDescription' => '',
                        'ogImage' => null,
                        'twitterCard' => 'summary',
                        'twitterSite' => '',
                        'googleAnalyticsId' => null,
                        'googleTagManagerId' => null,
                        'facebookPixelId' => null,
                    ]);
                }

                // Genel ayarlar
                $siteTitle = Setting::get('site_title', 'Blog Platform');
                $siteDescription = Setting::get('meta_description', '');
                $siteKeywords = Setting::get('meta_keywords', '');
                $siteLogo = Setting::get('site_logo');
                $siteFavicon = Setting::get('site_favicon');
                
                // OG ve Twitter ayarları
                $ogTitle = Setting::get('og_title_format', '%title% | %site_title%');
                $ogDescription = Setting::get('og_description', $siteDescription);
                $ogImage = Setting::get('og_image');
                $twitterCard = Setting::get('twitter_card_type', 'summary');
                $twitterSite = Setting::get('twitter_site', '');
                
                // Analytics kodları
                $googleAnalyticsId = Setting::get('google_analytics_id');
                $googleTagManagerId = Setting::get('google_tag_manager_id');
                $facebookPixelId = Setting::get('facebook_pixel_id');
                
                $view->with(compact(
                    'siteTitle',
                    'siteDescription',
                    'siteKeywords',
                    'siteLogo',
                    'siteFavicon',
                    'ogTitle',
                    'ogDescription',
                    'ogImage',
                    'twitterCard',
                    'twitterSite',
                    'googleAnalyticsId',
                    'googleTagManagerId',
                    'facebookPixelId'
                ));
            } catch (\Exception $e) {
                // Hata durumunda varsayılan değerleri kullan
                $view->with([
                    'siteTitle' => 'Blog Platform',
                    'siteDescription' => '',
                    // ... diğer varsayılan değerler
                ]);
            }
        });
    }
} 