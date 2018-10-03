<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatgroupSeatgroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seatgroup_seatgroups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('child_id')->unsigned()->index();
            $table->integer('parent_id')->unsigned()->index();
            $table->foreign('child_id')->references('id')->on('seatgroups')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('seatgroups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seatgroup_seatgroups');
    }
}
