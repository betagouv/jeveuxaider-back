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
        Schema::table('profiles', function (Blueprint $table) {
            $table->boolean('ft')->default(false);
            $table->timestamp('ft_updated_at')->nullable();
            $table->string('ft_email_adviser')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('ft');
            $table->dropColumn('ft_updated_at');
            $table->dropColumn('ft_email_adviser');
        });
    }
};
