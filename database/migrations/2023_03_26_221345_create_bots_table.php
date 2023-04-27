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
        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('telegram_id')->unique();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade');
            $table->string('username', 32);
            $table->string('name', 64);
            $table->string('about', 120);
            $table->string('description', 512);
            $table->string('avatar', 2048);
            $table->string('token')->unique();
            $table->boolean('is_active')->default(true);
            $table->string('welcome_message_text', 4096)->nullable();
            $table->string('help_message_text', 4096)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bots');
    }
};
