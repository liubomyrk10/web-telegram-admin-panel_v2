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
        Schema::create('bot_subreddit', function (Blueprint $table) {
            $table->foreignId('bot_id')->constrained()
                ->onDelete("cascade");
            $table->foreignId('subreddit_id')->constrained()
                ->onDelete("cascade");
            $table->primary(['bot_id', 'subreddit_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_subreddit');
    }
};
