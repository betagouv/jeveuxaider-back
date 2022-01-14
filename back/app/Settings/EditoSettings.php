<?php
namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EditoSettings extends Settings
{
    public array $missions_prioritaires;

    public static function group(): string
    {
        return 'edito';
    }
}