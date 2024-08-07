<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Carbon\Carbon;

class Utils
{
    public static function ucfirst($string = null)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }

    public static function slug($value)
    {
        // Remove useless words
        $exclude_words = [' à ', ' des ', ' de ', ' du ', ' les ', ' le ', ' la ', ' par ', ' ou '];
        $value = str_replace($exclude_words, ' ', $value);

        return Str::slug($value);
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
            case '2_days':
                $hours = 14;
                break;
            case '3_days':
                $hours = 21;
                break;
            case '4_days':
                $hours = 28;
                break;
            case '5_days':
                $hours = 35;
                break;
            case 'more_5_days':
                $hours = 42;
                break;
            default:
                break;
        }

        $multiplier = 1;
        switch ($time_period) {
            case 'day':
                $multiplier = 365;
                break;
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

    public static function getCommitmentLabel($duration, $time_period = null)
    {
        if(!$time_period) {
            switch ($duration) {
                case '1_hour':
                case '2_hours':
                case 'half_day':
                    return 'few_hours';
                default:
                    return 'few_days';
            }
        }

        switch ($time_period) {
            // No more day, only week and month
            case 'week':
                if($duration == '1_hour' || $duration == '2_hours' || $duration == 'half_day') {
                    return 'few_hours_a_week';
                }
                return 'few_days_a_week';
            case 'month':
                if($duration == '1_hour' || $duration == '2_hours' || $duration == 'half_day') {
                    return 'few_hours_a_month';
                }
                return 'few_days_a_month';
        }

        return null;
    }

    public static function formatDate($date, $format = 'd F Y', $fromFormat = 'Y-m-d H:i:s')
    {
        return Carbon::createFromFormat($fromFormat, $date)->translatedFormat($format);
    }

    public static function labelFromValue($value, $taxonomy)
    {
        $terms = config("taxonomies.$taxonomy.terms");
        return $terms && isset($terms[$value]) ? $terms[$value] : $value;
    }

    // Citycode, not postcode.
    public static function getDepartmentFromCitycode($citycode)
    {
        $pattern = '/^(971|972|973|974|976|987|988)/';
        if (preg_match($pattern, $citycode)) {
            return substr($citycode, 0, 3);
        }

        return substr($citycode, 0, 2);
    }
}
