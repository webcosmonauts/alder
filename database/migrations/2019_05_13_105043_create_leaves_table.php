<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 255)->unique();
            $table->text('title');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('type_id')->unsigned()->nullable();
            $table->integer('status_id')->unsigned()->default(1);
            $table->integer('revision')->default(0);
            $table->text('fields')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
    
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('type_id')->references('id')->on('leaf_types');
            $table->foreign('status_id')->references('id')->on('leaf_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leafs');
    }
}
