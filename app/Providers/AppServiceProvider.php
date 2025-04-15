<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // HTML sanitize için helper fonksiyon
        if (!function_exists('clean')) {
            function clean($html)
            {
                try {
                    return \Purifier::clean($html);
                } catch (\Exception $e) {
                    return $html; // Purifier yoksa HTML'i olduğu gibi döndür
                }
            }
        }

        // Activity Log Helper
        if (!function_exists('log_activity')) {
            /**
             * Log an activity.
             *
             * @param string $description Description of the activity.
             * @param Model|null $subject The model the activity is related to.
             * @param array|null $properties Additional properties to log.
             * @param string $logName The log channel name.
             * @return void
             */
            function log_activity(string $description, ?Model $subject = null, ?array $properties = null, string $logName = 'default')
            {
                try {
                    ActivityLog::create([
                        'user_id' => Auth::id(),
                        'log_name' => $logName,
                        'description' => $description,
                        'subject_type' => $subject ? get_class($subject) : null,
                        'subject_id' => $subject ? $subject->getKey() : null,
                        'properties' => $properties ? collect($properties) : null,
                    ]);
                } catch (\Exception $e) {
                    // Hata durumunda loglama sessizce başarısız olsun
                    report($e); // Laravel'in hata loglama mekanizmasına raporla
                }
            }
        }

        // Settings Helper
        if (!function_exists('setting')) {
            /**
             * Get a setting value by key.
             *
             * @param string|null $key
             * @param mixed $default
             * @return mixed
             */
            function setting(string $key = null, $default = null)
            {
                if (is_null($key)) {
                    return null; // app('setting') yerine null döndür
                }

                try {
                    // Tablo var mı kontrol et
                    if (!Schema::hasTable('settings')) {
                        return $default;
                    }
                    
                    return Setting::get($key, $default);
                } catch (\Exception $e) {
                    return $default;
                }
            }
        }

        // Global SEO ve site ayarlarını tüm view'lere ekle
        try {
            // Veritabanı bağlantısını kontrol et
            if (DB::connection()->getPdo() && Schema::hasTable('settings')) {
                $generalSettings = Setting::getAllByGroup('general');
                $seoSettings = Setting::getAllByGroup('seo');
                $socialSettings = Setting::getAllByGroup('social');
                
                View::share('generalSettings', $generalSettings);
                View::share('seoSettings', $seoSettings);
                View::share('socialSettings', $socialSettings);
            }
        } catch (\Exception $e) {
            // Hata yok sayılır (migration henüz yapılmamış olabilir)
        }
    }
}
