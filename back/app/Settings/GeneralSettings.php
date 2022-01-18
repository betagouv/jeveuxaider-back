<?php
namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public array $maintenance_mode_active;
    public array $light_mode_active;
    public array $france_connect_active;

    public static function group(): string
    {
        return 'general';
    }
}
