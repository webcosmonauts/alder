<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('field_type');
            $table->string('value');
            $table->integer('tab_id')->unsigned();
            $table->integer('leaf_type_id')->unsigned();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('tab_id')->references('id')->on('setting_tabs');
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
        Schema::dropIfExists('settings');
    }
}
