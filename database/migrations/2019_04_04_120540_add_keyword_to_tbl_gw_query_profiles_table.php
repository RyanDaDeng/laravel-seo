<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeywordToTblGwQueryProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_gw_query_profiles', function (Blueprint $table) {
            //
            $table->integer('keyword')->nullable();
            $table->index('index', 'tbl_gw_query_profiles_index_index');
            $table->index(['page', 'keyword'], 'tbl_gw_query_profiles_page_keyword_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_gw_query_profiles', function (Blueprint $table) {
            //
            $table->dropColumn('keyword');
            $table->dropIndex('tbl_gw_query_profiles_index_index');
            $table->dropIndex('tbl_gw_query_profiles_page_keyword_index');
        });
    }
}
