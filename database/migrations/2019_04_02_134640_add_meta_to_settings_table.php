<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetaToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            //
            $table->text('meta')->nullable();
        });

        \App\Modules\Setting\Models\GoogleSetting::query()->where('id', \App\Modules\Setting\Models\GoogleSetting::ID)->delete();

        $obj = new \App\Modules\Setting\Models\GoogleSetting();
        $obj->id = \App\Modules\Setting\Models\GoogleSetting::ID;
        $obj->name = 'Google Console Setting';
        $obj->last_updated = '2000-01-01 00:00:00';
        $obj->timezone = 'Australia/Sydney';
        $obj->description = 'The last updated date that admin site has pushed the latest Google Search Console data to agent server.';
        $obj->meta = ["keywords" => 100, "pages" => 200, "query_details" => 4486804];
        $obj->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            //
            $table->dropColumn('meta');
        });
    }
}
