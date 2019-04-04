<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAverageWeightRankingToTblGwQueryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_gw_query_details', function (Blueprint $table) {
            //
            $table->double('average_weight_ranking', 6)->nullable();
            $table->string('index')->nullable();
            $table->index('index', 'tbl_gw_query_details_single_index_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_gw_query_details', function (Blueprint $table) {
            //
            $table->dropColumn('average_weight_ranking');
            $table->dropColumn('index');
        });
    }
}
