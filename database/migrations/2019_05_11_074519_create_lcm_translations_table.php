<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLcmTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lcm_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('leaf_custom_modifier_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->string('group_title')->nullable();
    
            $table->unique(['leaf_custom_modifier_id', 'locale']);
            $table->foreign('leaf_custom_modifier_id')->references('id')
                ->on('lcms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lcm_translations');
    }
}
