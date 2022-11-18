<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('searches', function (Blueprint $table) {
            $table->id();
            $table->string('query');
            $table->unsignedBigInteger('website_id');
            $table->timestamps();

            $table->foreign('website_id')->references('id')->on('websites')->cascadeOnDelete();
        });

        Schema::create('search_keywords', function(Blueprint $table){
            $table->unsignedBigInteger('search_id');
            $table->unsignedBigInteger('keyword_id');

            $table->foreign('search_id')->references('id')->on('searches')->cascadeOnDelete();
            $table->foreign('keyword_id')->references('id')->on('keywords')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_keywords');
        Schema::dropIfExists('searches');
    }
}
