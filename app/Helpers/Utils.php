<?php

namespace App\Helpers;

class Utils
{
    public static function ucfirst($string = null)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }
}
