<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialDeployment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("seatgroups",function ($table){
           $table->increments('id')->index();
           $table->string('name');
           $table->text('description');
           $table->string('type');
           $table->integer('role_id')->unsigned()->index();
           $table->timestamps();
        });

        Schema::create('seatgroup_user', function (Blueprint $table) {
            $table->integer('seatgroup_id')->unsigned()->index();
            $table->foreign('seatgroup_id')->references('id')->on('seatgroups')->onDelete('cascade');
            $table->bigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_manager')->default(false);
            $table->boolean('on_waitlist')->default(false);
            $table->primary(['seatgroup_id', 'user_id']);
        });

        Schema::create('corporation_info_seatgroup', function (Blueprint $table) {
            $table->bigInteger('corporation_id')->index();
            $table->foreign('corporation_id')->references('corporation_id')->on('corporation_infos')->onDelete('cascade');
            $table->integer('seatgroup_id')->unsigned()->index();
            $table->foreign('seatgroup_id')->references('id')->on('seatgroups')->onDelete('cascade');
            $table->primary(['corporation_id', 'seatgroup_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('corporation_info_seatgroup');
        Schema::drop('seatgroup_user');
        Schema::drop("seatgroups");
    }
}
