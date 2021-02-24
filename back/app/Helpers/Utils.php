<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Utils
{
    public static function ucfirst($string = null)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }

    public static function slug($value)
    {
        // Remove useless words
        $exclude_words = [' à ',' des ',' de ',' du ',' les ',' le ',' la ',' par ',' ou '];
        $value = str_replace($exclude_words, ' ', $value);
        return Str::slug($value);
    }
}
