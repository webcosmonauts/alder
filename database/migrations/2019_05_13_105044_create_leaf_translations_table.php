<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeafTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaf_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('leaf_id')->unsigned();
            $table->string('locale')->index();
            $table->text('title')->nullable();
            $table->string('slug', 255)->nullable();
            $table->text('content')->nullable();
    
            $table->unique(['leaf_id','locale']);
            $table->unique(['slug','locale']);
            $table->foreign('leaf_id')->references('id')
                ->on('leaves')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaf_translations');
    }
}
