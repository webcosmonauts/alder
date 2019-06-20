<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRootsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->text('value')->nullable();
            $table->text('options')->nullable();
            $table->string('input_type')->default('text');
            $table->integer('order')->nullable();
            $table->string('capabilities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('root_type_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('root_type_id')->references('id')->on('root_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roots');
    }
}
