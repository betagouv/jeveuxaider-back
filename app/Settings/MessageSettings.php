<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MessageSettings extends Settings
{
    public string $title;

    public string $benevole;

    public string $admin;

    public string $responsable_organisation;

    public string $responsable_territoire;

    public string $referent_departemental;

    public string $referent_regional;

    public string $responsable_reseau;

    public string $analyste;

    public static function group(): string
    {
        return 'message';
    }
}
