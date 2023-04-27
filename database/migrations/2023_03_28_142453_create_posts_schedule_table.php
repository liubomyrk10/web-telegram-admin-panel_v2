<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('post_id')->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->dateTime('post_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_schedules');
    }
};
