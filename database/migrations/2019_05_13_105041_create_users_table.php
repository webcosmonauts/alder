<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(false);
            $table->integer('LCM_id')->unsigned()->nullable();
            $table->integer('LCMV_id')->unsigned()->nullable();
            $table->rememberToken();
            $table->timestamps();
    
            $table->foreign('LCM_id')->references('id')->on('leaf_custom_modifiers');
            $table->foreign('LCMV_id')->references('id')->on('leaf_custom_modifier_values');
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
