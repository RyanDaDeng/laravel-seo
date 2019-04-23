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
            $table->boolean('is_primary')->default(0);
            $table->integer('page')->nullable();
            $table->integer('keyword')->nullable();
            $table->integer('initial_impressions')->default(0);
            $table->float('initial_ctr_value')->default(0);
            $table->float('initial_avg_position')->default(0);
            $table->timestamps();

            $table->index(['click_potential'], 'click_potential_index');
            $table->index(['ctr_benchmark'], 'ctr_benchmark_index');
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
