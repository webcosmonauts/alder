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
            $table->text('title');
            $table->string('slug', 255);
            $table->text('content')->nullable();
            $table->boolean('is_accessible')->default(true);
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('leaf_type_id')->unsigned();
            $table->integer('status_id')->unsigned()->default(1);
            $table->integer('LCMV_id')->unsigned()->nullable();
            $table->integer('revision')->default(0);
            $table->timestamps();
            
            $table->unique(['slug', 'leaf_type_id']);
    
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('leaf_type_id')->references('id')->on('leaf_types');
            $table->foreign('status_id')->references('id')->on('leaf_statuses');
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
        Schema::dropIfExists('leafs');
    }
}
