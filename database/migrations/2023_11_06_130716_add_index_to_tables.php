<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->index(['conversable_id', 'conversable_type']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->index(['region_id']);
        });

        Schema::table('invitations', function (Blueprint $table) {
            $table->index(['user_id']);
        });

        Schema::table('message_templates', function (Blueprint $table) {
            $table->index(['user_id']);
        });

        Schema::table('mission_templates', function (Blueprint $table) {
            $table->index(['domaine_id']);
            $table->index(['activity_id']);
            $table->index(['domaine_secondary_id']);
            $table->index(['activity_secondary_id']);
        });

        Schema::table('missions', function (Blueprint $table) {
            $table->index(['structure_id']);
            $table->index(['template_id']);
            $table->index(['domaine_id']);
            $table->index(['activity_id']);
            $table->index(['domaine_secondary_id']);
            $table->index(['activity_secondary_id']);
            $table->index(['state']);
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->index(['parent_id']);
            $table->index(['user_id']);
        });

        Schema::table('notifications_benevoles', function (Blueprint $table) {
            $table->index(['user_id']);
            $table->index(['profile_id']);
            $table->index(['mission_id']);
        });

        Schema::table('notifications_temoignages', function (Blueprint $table) {
            $table->index(['participation_id']);
        });

        Schema::table('participations', function (Blueprint $table) {
            $table->index(['mission_id']);
        });

        Schema::table('social_accounts', function (Blueprint $table) {
            $table->index(['user_id']);
        });

        Schema::table('temoignages', function (Blueprint $table) {
            $table->index(['participation_id']);
        });

        Schema::table('terms', function (Blueprint $table) {
            $table->index(['vocabulary_id']);
            $table->index(['parent_id']);
        });

        Schema::table('territoire_relations', function (Blueprint $table) {
            $table->index(['territoire_id']);
        });

        Schema::table('territoires', function (Blueprint $table) {
            $table->index(['structure_id']);
        });

        Schema::table('thematiques', function (Blueprint $table) {
            $table->index(['domaine_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropIndex(['conversable_id', 'conversable_type']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropIndex(['region_id']);
        });

        Schema::table('invitations', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('message_templates', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('mission_templates', function (Blueprint $table) {
            $table->dropIndex(['domaine_id']);
            $table->dropIndex(['activity_id']);
            $table->dropIndex(['domaine_secondary_id']);
            $table->dropIndex(['activity_secondary_id']);
        });

        Schema::table('missions', function (Blueprint $table) {
            $table->dropIndex(['structure_id']);
            $table->dropIndex(['template_id']);
            $table->dropIndex(['domaine_id']);
            $table->dropIndex(['activity_id']);
            $table->dropIndex(['domaine_secondary_id']);
            $table->dropIndex(['activity_secondary_id']);
            $table->dropIndex(['state']);
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('notifications_benevoles', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['profile_id']);
            $table->dropIndex(['mission_id']);
        });

        Schema::table('notifications_temoignages', function (Blueprint $table) {
            $table->dropIndex(['participation_id']);
        });

        Schema::table('participations', function (Blueprint $table) {
            $table->dropIndex(['mission_id']);
        });

        Schema::table('social_accounts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('temoignages', function (Blueprint $table) {
            $table->dropIndex(['participation_id']);
        });

        Schema::table('terms', function (Blueprint $table) {
            $table->dropIndex(['vocabulary_id']);
            $table->dropIndex(['parent_id']);
        });

        Schema::table('territoire_relations', function (Blueprint $table) {
            $table->dropIndex(['territoire_id']);
        });

        Schema::table('territoires', function (Blueprint $table) {
            $table->dropIndex(['structure_id']);
        });

        Schema::table('thematiques', function (Blueprint $table) {
            $table->dropIndex(['domaine_id']);
        });
    }
};
