<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateEditoSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('edito.missions_prioritaires', []);
    }

    public function down(): void
    {
        $this->migrator->delete('edito.missions_prioritaires');
    }
}
