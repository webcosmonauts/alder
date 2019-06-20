<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRootTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('root_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('root_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title')->nullable();
    
            $table->unique(['root_id','locale']);
            $table->foreign('root_id')->references('id')
                ->on('roots')->onDelete('cascade');
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
