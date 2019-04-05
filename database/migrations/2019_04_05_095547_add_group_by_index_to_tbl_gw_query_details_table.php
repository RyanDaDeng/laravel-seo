<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupByIndexToTblGwQueryDetailsTable extends Migration
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
            $table->dropIndex('tbl_gw_query_details_index');
            $table->dropIndex('keyword_page_date_index');
            $table->dropIndex('date_index');
            $table->dropIndex('page_keyword_index');
            $table->dropIndex('covering_index');

            $table->index(['index', 'page', 'keyword'], 'group_by_index_keyword_page_index');
            $table->index(['index', 'page', 'keyword', 'date', 'device'], 'tbl_gw_query_details_index');
            $table->index(['index', 'page', 'keyword', 'date'], 'keyword_page_date_index');
            $table->index(['date'], 'date_index');
            $table->index(['index', 'page', 'keyword'], 'page_keyword_index');
            $table->index(['index', 'page', 'keyword', 'date', 'clicks', 'impressions', 'position', 'average_weight_ranking'], 'covering_index');

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
//            $table->dropIndex('group_by_index_keyword_page_index');
//            $table->dropIndex('tbl_gw_query_details_index');
//            $table->dropIndex('keyword_page_date_index');
//            $table->dropIndex('date_index');
//            $table->dropIndex('page_keyword_index');
//            $table->dropIndex('covering_index');
        });
    }
}
