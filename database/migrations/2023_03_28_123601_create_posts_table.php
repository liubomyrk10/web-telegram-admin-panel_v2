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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('reddit_id', 7)->unique();
            $table->foreignId('subreddit_id')->constrained();
            //$table->foreignId('bot_id')->constrained();
            $table->enum('type', ['text', 'image', 'video', 'youtube', 'gif']);
            $table->string('title', 300)->nullable();
            $table->text('description')->nullable();
            $table->string('url', 2048)->nullable();
            $table->string('permalink', 2048)->nullable();
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->boolean('is_nsfw')->default(false);
            $table->boolean('is_spoiler')->default(false);
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
        Schema::dropIfExists('posts');
    }
};
