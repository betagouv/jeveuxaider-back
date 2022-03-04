<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsAvisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications_avis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('token', 32)->unique();
            $table->integer('participation_id')->references('id')->on('participations')->onDelete('cascade');
            $table->integer('reminders_sent')->unsigned();
            $table->timestamp('last_sent_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications_avis');
    }
}
