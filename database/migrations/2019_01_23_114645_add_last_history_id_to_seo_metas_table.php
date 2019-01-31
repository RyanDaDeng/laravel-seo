<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastHistoryIdToSeoMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seo_metas', function (Blueprint $table) {
            //
            $table->unsignedInteger('last_history_id')->nullable();
            $table->tinyInteger('last_history_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seo_metas', function (Blueprint $table) {
            //
            $table->dropColumn('last_history_id');
            $table->dropColumn('last_history_status');
        });
    }
}
