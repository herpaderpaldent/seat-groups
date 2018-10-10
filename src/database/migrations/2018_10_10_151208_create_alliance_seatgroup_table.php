<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllianceSeatgroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alliance_seatgroup', function (Blueprint $table) {
            $table->integer('seatgroup_id')->unsigned()->index();
            $table->foreign('seatgroup_id')->references('id')->on('seatgroups')->onDelete('cascade');
            $table->integer('alliance_id')->index();
            $table->foreign('alliance_id')->references('alliance_id')->on('alliances')->onDelete('cascade');
            $table->primary(['seatgroup_id','alliance_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alliance_seatgroup');
    }
}
