<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettingsSnuMig extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.snu_mig_active', true);
    }

    public function down(): void
    {
        $this->migrator->delete('general.snu_mig_active');
    }
}
