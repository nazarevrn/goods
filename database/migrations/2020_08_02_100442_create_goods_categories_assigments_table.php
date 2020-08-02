<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsCategoriesAssigmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_categories_assignments', function (Blueprint $table) {
            $table->bigInteger('goods_id', false, true)->index();
            $table->bigInteger('categories_id', false, true)->index();
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->foreign('categories_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goods_categories_assignments', function (Blueprint $table) {
            $table->dropForeign(['goods_id']);
            $table->dropForeign(['categories_id']);
        });
        Schema::dropIfExists('goods_categories_assignments');
    }
}
