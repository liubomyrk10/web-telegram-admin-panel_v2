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
        Schema::create('bot_post', function (Blueprint $table) {
            $table->foreignId('bot_id')->constrained()
                ->onDelete("cascade");
            $table->foreignId('post_id')->constrained()
                ->onDelete("cascade");
            $table->primary(['bot_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_post');
    }
};
