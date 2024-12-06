<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class Thumbnail
{
    const dir = 'thumbnails';

    public static function path($id)
    {
        return self::dir . '/' . $id . '.jpg';
    }

    public static function fullPath($id)
    {
        return Storage::disk('public')->path(self::path($id));
    }

    public static function getBlob($id)
    {
        $path = self::get($id);
        return Storage::disk('public')->get($path);
    }

    public static function get($id)
    {
        if (Storage::disk('public')->exists(self::path($id))) {
            return self::path($id);
        } else {
            return self::save($id);
        }
    }

    public static function save($id)
    {
        $saved = Storage::disk('public')->put(self::path($id), self::fetch($id));
        return self::path($id);
    }

    public static function fetch($id)
    {
        $url = 'https://i.ytimg.com/vi/' . $id . '/hqdefault.jpg';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
}
