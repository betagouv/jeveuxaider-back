<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateMessageSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('message.title', 'Le petit mot de Giulietta');
        $this->migrator->add('message.admin', "Bienvenue dans votre espace d'administration en tant qu'admin !");
        $this->migrator->add('message.benevole', 'Bienvenue dans votre espace bénévole !');
        $this->migrator->add('message.responsable_organisation', "Bienvenue dans votre espace d'administration en tant que responsable d'organisation !");
        $this->migrator->add('message.responsable_territoire', "Bienvenue dans votre espace d'administration en tant que responsable de collectivité !");
        $this->migrator->add('message.referent_departemental', "Bienvenue dans votre espace d'administration en tant que référent départemental !");
        $this->migrator->add('message.referent_regional', "Bienvenue dans votre espace d'administration en tant que référent régional !");
        $this->migrator->add('message.responsable_reseau', "Bienvenue dans votre espace d'administration en tant que responsable de réseau !");
        $this->migrator->add('message.analyste', "Bienvenue dans votre espace d'administration en tant qu'analyste !");
    }

    public function down(): void
    {
        $this->migrator->delete('message.title');
        $this->migrator->delete('message.admin');
        $this->migrator->delete('message.benevole');
        $this->migrator->delete('message.responsable_organisation');
        $this->migrator->delete('message.responsable_territoire');
        $this->migrator->delete('message.referent_departemental');
        $this->migrator->delete('message.referent_regional');
        $this->migrator->delete('message.responsable_reseau');
        $this->migrator->delete('message.analyste');
    }
}
