<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'options'
    ];

    protected $casts = [
        'options' => 'array', // JSON olan options'ı array olarak göster
    ];

    /**
     * Belirli bir grup için tüm ayarları getirir ve key=>value formatında döndürür.
     *
     * @param string $group
     * @return \Illuminate\Support\Collection
     */
    public static function getAllByGroup(string $group = 'general')
    {
        return self::where('group', $group)
            ->pluck('value', 'key');
    }

    /**
     * Bir ayarın değerini key'e göre döndürür.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Belirtilen keye sahip ayarı günceller veya oluşturur.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param string $type
     * @return bool
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'text')
    {
        $setting = self::firstOrNew(['key' => $key]);
        $setting->value = $value;
        $setting->group = $group;
        $setting->type = $type;
        return $setting->save();
    }
} 