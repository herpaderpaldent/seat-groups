<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetupCorrectForeignKeysForCorporationTitleSeatgroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corporation_title_seatgroups', function (Blueprint $table) {
            $table->dropForeign(['corporation_id']);
            $table->dropForeign(['title_id']);

            $table->foreign(['corporation_id', 'title_id'])
                ->references(['corporation_id', 'title_id'])
                ->on('corporation_titles')
                ->onDelete('cascade');

            $table->primary(['corporation_id', 'title_id', 'seatgroup_id'], 'corporation_title_seatgroups_primary');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('corporation_title_seatgroups', function (Blueprint $table) {

            $table->dropPrimary(['corporation_id', 'title_id', 'seatgroup_id']);
            $table->dropForeign(['corporation_id', 'title_id']);
            $table->foreign('corporation_id')->references('corporation_id')->on('corporation_titles')->onDelete('cascade');
            $table->foreign('title_id')->references('title_id')->on('corporation_titles')->onDelete('cascade');
        });
    }
}
