<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_configurations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id');
            $table->string('query_search_variable')->nullable();
            $table->string('query_separator')->nullable();
            $table->string('tag_resource_link')->nullable();
            $table->string('tag_resource_title')->nullable();
            $table->string('tag_resource_description')->nullable();
            $table->string('tag_resource_next_page')->nullable();
            $table->string('tag_resource_list_posts')->nullable();
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
        Schema::dropIfExists('website_configurations');
    }
}
