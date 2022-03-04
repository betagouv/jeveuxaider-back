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

    public static function getDepartmentFromZip($zip)
    {
        $department = substr($zip, 0, 2);
        switch ($department) {
            case '97':
            case '98':
                $department = substr($zip, 0, 3);
                break;
        }
        return $department;
    }

    public static function calculateCommitmentTotal($duration, $time_period = null)
    {
        $hours = 1;
        switch ($duration) {
            case '1_hour':
                $hours = 1;
                break;
            case '2_hours':
                $hours = 2;
                break;
            case 'half_day':
                $hours = 4;
                break;
            case 'day':
                $hours = 7;
                break;
            case '3_days':
                $hours = 21;
                break;
            case '5_days':
                $hours = 48;
                break;
            default:
                break;
        }

        $multiplier = 1;
        switch ($time_period) {
            case 'week':
                $multiplier = 52;
                break;
            case 'month':
                $multiplier = 12;
                break;
            default:
                break;
        }
        return $hours * $multiplier;
    }
}
