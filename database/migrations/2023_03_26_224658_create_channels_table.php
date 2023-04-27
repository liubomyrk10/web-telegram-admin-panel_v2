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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('telegram_id')->unsigned()->unique();
            $table->foreignId('bot_id')->constrained();
            $table->string('title', 255);
            $table->string('username', 32)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('photo', 2048)->nullable();
            $table->integer('member_count')->nullable();
            $table->boolean('is_public')->default(true);
            $table->string('invite_link', 2048)->nullable();
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
        Schema::dropIfExists('channels');
    }
};
