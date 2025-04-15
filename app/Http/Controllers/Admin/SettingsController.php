<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $activeGroup = $request->get('group', 'general');
        $groups = ['general', 'seo', 'social', 'analytics'];
        
        $settings = Setting::where('group', $activeGroup)
            ->orderBy('id')
            ->get();
            
        return view('admin.settings.index', compact('settings', 'activeGroup', 'groups'));
    }

    public function update(Request $request)
    {
        $group = $request->get('group', 'general');
        $settings = Setting::where('group', $group)->get();
        
        // Değiştirildi mi kontrolü için (log için)
        $changedSettings = [];
        
        foreach ($settings as $setting) {
            $key = $setting->key;
            $oldValue = $setting->value;
            
            // Dosya kontrolü
            if ($setting->type === 'file' && $request->hasFile($key)) {
                $file = $request->file($key);
                
                // Eski dosyayı sil
                if ($oldValue) {
                    Storage::disk('public')->delete($oldValue);
                }
                
                // Yeni dosyayı kaydet
                $filename = $key . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('settings', $filename, 'public');
                
                $setting->value = $path;
                $setting->save();
                
                $changedSettings[$key] = ['old' => $oldValue, 'new' => $path];
                continue;
            }
            
            // Boolean kontrolü
            if ($setting->type === 'boolean') {
                $value = $request->has($key) ? '1' : '0';
            } else {
                $value = $request->get($key, $oldValue);
            }
            
            // Değer değiştiyse güncelle
            if ($value !== $oldValue) {
                $setting->value = $value;
                $setting->save();
                $changedSettings[$key] = ['old' => $oldValue, 'new' => $value];
            }
        }
        
        // Aktivite logu tut
        if (!empty($changedSettings)) {
            log_activity(
                'Admin updated ' . $group . ' settings',
                null,
                ['changes' => $changedSettings, 'group' => $group, 'admin_id' => auth()->id()]
            );
        }
        
        // SEO ayarları değiştiyse, çeşitli işlemler yapılabilir
        if ($group === 'seo') {
            // Örneğin robots.txt güncelleme
            if (array_key_exists('robots_txt', $changedSettings)) {
                file_put_contents(public_path('robots.txt'), $request->get('robots_txt'));
            }
            
            // Site haritası üretme
            if (array_key_exists('generate_sitemap', $changedSettings) && $request->has('generate_sitemap')) {
                // Artisan komutu çalıştır (sitemap:generate komutu oluşturmanız gerekir)
                try {
                    Artisan::call('sitemap:generate');
                } catch (\Exception $e) {
                    // Komut bulunamadı/çalıştırılamadı
                }
            }
        }
        
        return redirect()->route('admin.settings.index', ['group' => $group])
            ->with('success', 'Settings updated successfully.');
    }
} 