<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyLinkIndexQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('link_index_queue', function (Blueprint $table) {
            $table->boolean('success')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('link_index_queue', function (Blueprint $table) {
            $table->integer('success')->default(NULL)->change();
        });
    }
}
