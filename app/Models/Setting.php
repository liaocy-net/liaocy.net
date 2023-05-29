<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public static function getInt($key, $default = 0)
    {
        $setting = self::where("key", $key)->first();
        if ($setting) {
            return intval($setting->value);
        } else {
            $setting = new Setting();
            $setting->key = $key;
            $setting->value = strval($default);
            $setting->save();
        }
        return $default;
    }

    public static function setInt($key, $value)
    {
        $setting = self::where("key", $key)->first();
        if ($setting) {
            $setting->value = strval($value);
            $setting->save();
        } else {
            $setting = new Setting();
            $setting->key = $key;
            $setting->value = strval($value);
            $setting->save();
        }
    }
}
