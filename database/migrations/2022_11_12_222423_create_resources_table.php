<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('url', 255)->unique();
            $table->unsignedInteger('resource_type_id');
            $table->unsignedBigInteger('search_id');
            $table->timestamps();

            $table->foreign('resource_type_id')->references('id')->on('resource_types')->cascadeOnDelete();
            $table->foreign('search_id')->references('id')->on('searches')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resources');
    }
}
