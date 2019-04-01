<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_gw_keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword', 255);
            $table->string('md5', 32);
            $table->timestamps();
            $table->index(['md5'], 'tbl_gw_keywords_md5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_gw_keywords');
    }
}
