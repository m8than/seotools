<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('root_links', function (Blueprint $table) {
            $table->id();
            $table->string('scheme');
            $table->string('domain');
            $table->string('url_string');
            $table->bigInteger('user_id');
            $table->bigInteger('category_id');
            $table->timestamps();
        });

        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('scheme');
            $table->string('domain');
            $table->string('url_string');
            $table->string('anchor_text');
            $table->tinyInteger('outdated')->default(0);
            $table->bigInteger('root_link_id');
            $table->timestamps();
        });

        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->string('user_agent');
            $table->bigInteger('root_link_id');
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
        Schema::dropIfExists('root_links');
        Schema::dropIfExists('links');
        Schema::dropIfExists('views');
    }
}
