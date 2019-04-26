<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_gw_query_details', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable();
            $table->integer('page')->nullable();
            $table->integer('keyword')->nullable();
            $table->tinyInteger('device')->nullable();
            $table->integer('clicks')->nullable();
            $table->float('ctr', 12, 6)->nullable();
            $table->float('impressions', 12, 6)->nullable();
            $table->float('position', 12, 6)->nullable();
            $table->index(['page', 'keyword'], 'page_keyword_index');
            $table->index(['keyword'], 'keyword_index');
            $table->index(['date', 'page', 'keyword'], 'date_page_keyword_index');
            $table->index(['page', 'keyword', 'device'], 'page_keyword_device_index');
            $table->index(['date', 'page', 'keyword','device'], 'date_full_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_gw_query_details');
    }
}
