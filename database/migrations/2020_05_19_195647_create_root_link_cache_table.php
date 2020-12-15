<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRootLinkCacheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('root_link_caches', function (Blueprint $table) {
            $table->id();
            $table->timestamp('crawled_date')->nullable();
            $table->timestamp('indexed_date')->nullable();
            $table->float('indexes_per_day');
            $table->float('views_per_day');
            $table->float('crawls_per_day');
            $table->float('backlinks_count');
            $table->float('rating');
            $table->integer('root_link_id');
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
        Schema::dropIfExists('root_link_caches');
    }
}
