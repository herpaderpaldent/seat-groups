<?php

use Illuminate\Database\Migrations\Migration;

class ReorganizeOrderOfColumnAllCorporations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE seatgroups MODIFY COLUMN all_corporations BOOLEAN default 0 not null AFTER type');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
