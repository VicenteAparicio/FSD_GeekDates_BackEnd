<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nick')->unique();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->unique();
            $table->integer('age')->nullable();
            $table->integer('phone')->unique();
            $table->text('description')->nullable();
            $table->string('urlpic')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->integer('cp')->nullable();
            $table->string('gender')->nullable();
            $table->string('sexuality')->nullable();
            $table->string('lookingfor')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('isAdmin')->default(false);
            $table->boolean('isPremium')->default(false);
            $table->boolean('isActive')->default(true);
            $table->boolean('isComplete')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
