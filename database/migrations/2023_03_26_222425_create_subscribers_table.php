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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('telegram_id')->unsigned()->unique();
            $table->foreignId('bot_id')->constrained();
            $table->string('first_name', 64);
            $table->string('last_name', 64)->nullable();
            $table->string('username', 32)->nullable();
            $table->string('lang')->nullable();
            $table->boolean('is_blocked')->default(false);
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
        Schema::dropIfExists('subscibers');
    }
};
