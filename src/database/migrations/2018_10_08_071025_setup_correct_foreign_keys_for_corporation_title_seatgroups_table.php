<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

            // Now add the composite foreign-key:
            DB::statement('
                ALTER TABLE corporation_title_seatgroups 
                ADD CONSTRAINT corporation_title_seatgroups_corporation_id_title_id_foreign
                FOREIGN KEY (corporation_id,title_id) REFERENCES corporation_titles(corporation_id,title_id)
                ON DELETE CASCADE;'
            );

            $table->primary(['corporation_id','title_id','seatgroup_id'],'corporation_title_seatgroups_primary');

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

            $table->dropPrimary(['corporation_id','title_id','seatgroup_id']);
            DB::statement('ALTER TABLE corporation_title_seatgroups DROP FOREIGN KEY corporation_title_seatgroups_corporation_id_title_id_foreign;');
            $table->foreign('corporation_id')->references('corporation_id')->on('corporation_titles')->onDelete('cascade');
            $table->foreign('title_id')->references('title_id')->on('corporation_titles')->onDelete('cascade');
        });
    }
}
