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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('keywords');
            $table->string('author');
            $table->enum('published',['0','1'])->default('0');
            $table->enum('commented',['0','1'])->default('0');
            $table->enum('view_count_stats',['0','1'])->default('0');
            $table->enum('rating_count_stats',['0','1'])->default('0');
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('dislike_count')->default(0);
            $table->unsignedDouble('rating_count', $precision = 2, $scale = 1)->default(0.0);
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
        Schema::dropIfExists('pages');
    }
};
