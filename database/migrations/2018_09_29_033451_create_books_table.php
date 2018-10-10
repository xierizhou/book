<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('categories_id');
            $table->string('title');
            $table->string('author');
            $table->string('new_chapter')->comment("最新章节");
            $table->string('desc')->comment("介绍");
            $table->tinyInteger('status');
            $table->integer('reading_volume')->comment("阅读数");
            $table->integer('collect_volume')->comment("收藏数");
            $table->string('cover')->comment("封面");
            $table->integer('sort');  //排序

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
        Schema::dropIfExists('books');
    }
}
