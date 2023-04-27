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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')->constrained()
                ->onUpdate('cascade');
            $table->enum('chat_type', ['private', 'group', 'supergroup', 'channel']);
            $table->string('command', 32)->nullable();
            $table->text('message')->nullable();
            $table->integer('send_time_taken');
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
        Schema::dropIfExists('logs');
    }
};
