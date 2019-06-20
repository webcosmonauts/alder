<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLcmvTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lcmv_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('leaf_custom_modifier_value_id')->unsigned();
            $table->string('locale')->index();
            $table->text('values')->nullable();
    
            $table->unique(['leaf_custom_modifier_value_id','locale']);
            $table->foreign('leaf_custom_modifier_value_id')->references('id')
                ->on('lcmvs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lcmv_translations');
    }
}
