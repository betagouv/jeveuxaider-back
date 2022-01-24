<?php
namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public bool $maintenance_mode_active;
    public bool $light_mode_active;
    public bool $france_connect_active;
    public bool $blog_active;

    public static function group(): string
    {
        return 'general';
    }
}
