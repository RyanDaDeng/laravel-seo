<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('path')->default('');
            $table->string('hash')->default('');
            $table->text('current_data')->default('{}');
            $table->text('draft_data')->default('{}');
            $table->dateTime('draft_at')->nullable();
            $table->dateTime('last_approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seo_metas');
    }
}
