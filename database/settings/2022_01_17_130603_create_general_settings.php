<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.maintenance_mode_active', false);
        $this->migrator->add('general.light_mode_active', false);
        $this->migrator->add('general.france_connect_active', true);
    }

    public function down(): void
    {
        $this->migrator->delete('general.maintenance_mode_active');
        $this->migrator->delete('general.light_mode_active');
        $this->migrator->delete('general.france_connect_active');
    }
}
