<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_gw_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('md5', 32);
            $table->string('path_md5', 32)->nullable();
            $table->text('shortcut_path')->nullable();
            $table->text('url');
            $table->timestamps();
            $table->index(['md5'], 'tbl_gw_pages_md5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_gw_pages');
    }
}
