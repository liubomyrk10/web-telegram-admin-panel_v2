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
        /*Schema::create('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->unsignedBigInteger('telegram_id')->unsigned()->unique();
            $table->string('first_name', 64);
            $table->string('last_name', 64)->nullable();
            $table->string('username', 32)->nullable();
            $table->string('photo_url', 2048)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });*/
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('telegram_id')->unsigned()->nullable()->after('permissions');
            $table->string('first_name', 64)->nullable()->after('telegram_id');
            $table->string('last_name', 64)->nullable()->after('first_name');
            $table->string('username', 32)->nullable()->after('last_name');
            $table->string('photo_url', 2048)->nullable()->after('username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::dropIfExists('admins');*/
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('telegram_id');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('username');
            $table->dropColumn('photo_url');
        });
    }
};
