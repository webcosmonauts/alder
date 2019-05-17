<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_tabs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('leaf_type_id')->unsigned();
            $table->timestamps();
    
            $table->foreign('parent_id')->references('id')->on('setting_tabs');
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
        Schema::dropIfExists('setting_tabs');
    }
}
