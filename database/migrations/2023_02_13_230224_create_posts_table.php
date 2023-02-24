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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('news_content');
            
            // author field akan bersinggungan dengan table user
            $table->unsignedBigInteger('author');
            $table->foreign('author')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes(); // agar proses penghapusan tidak permanen dr db
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
