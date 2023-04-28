<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category')->comment('human-friendly name in the URL');
            $table->unsignedInteger('page_id')->nullable()->comment('id in pages table');
            $table->unsignedInteger('parent_id');
            $table->enum('published',['0','1'])->default('1');
            $table->enum('only_auth',['0','1'])->default('0');
            $table->enum('is_nav_item',['0','1'])->default('1')->comment('is this a menu item');
            $table->string('nav_name');
            $table->smallInteger('nav-order')->nullable()->default(null)->comment('sequence number in the menu section');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
