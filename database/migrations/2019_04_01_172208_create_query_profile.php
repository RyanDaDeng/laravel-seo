<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueryProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_gw_query_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->float('ctr_benchmark')->default(0);
            $table->integer('click_potential')->default(0);
            $table->string('index',45)->nullable();
            $table->boolean('is_primary')->nullable();
            $table->integer('page')->nullable();
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
        Schema::dropIfExists('tbl_gw_query_profiles');
    }
}
