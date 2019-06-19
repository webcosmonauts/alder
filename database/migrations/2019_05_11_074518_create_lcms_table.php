<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLcmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lcms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('group_slug')->nullable();
            $table->text('modifiers')->nullable();
            $table->integer('leaf_type_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('leaf_type_id')->references('id')->on('leaf_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lcms');
    }
}
