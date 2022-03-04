<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettingsBlogs extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.blog_active', true);
    }

    public function down(): void
    {
        $this->migrator->delete('general.blog_active');
    }
}
