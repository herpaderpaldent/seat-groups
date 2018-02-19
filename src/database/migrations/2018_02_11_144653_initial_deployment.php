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

        Schema::create("seatgroupmanagers",function ($table){
            $table->integer('group_id')->unsigned()->index();
            $table->foreign('group_id')->references('id')->on('seatgroups');
            $table->bigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create("seatgroupalliances",function ($table){
            $table->integer('group_id')->unsigned()->index();
            $table->foreign('group_id')->references('id')->on('seatgroups');
            $table->integer('alliance_id')->index();
            $table->foreign('alliance_id')->references('alliance_id')->on('corporation_infos');
        });

        Schema::create("seatgroupcorporations",function ($table){
            $table->integer('group_id')->unsigned()->index();
            $table->foreign('group_id')->references('id')->on('seatgroups');
            $table->bigInteger('corporation_id')->index();
            $table->foreign('corporation_id')->references('corporation_id')->on('corporation_infos');
        });

        Schema::create("seatgroupusers",function ($table){
            $table->integer('group_id')->unsigned()->index();
            $table->foreign('group_id')->references('id')->on('seatgroups');
            $table->bigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create("seatgroupwaitlists",function ($table){
            $table->integer('group_id')->unsigned()->index();
            $table->foreign('group_id')->references('id')->on('seatgroups');
            $table->bigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create("seatgroupmanager_users",function ($table){
            $table->integer('group_id')->unsigned()->index();
            $table->foreign('group_id')->references('id')->on('seatgroups');
            $table->bigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seatgroupmanager_users');
        Schema::drop("seatgroupwaitlist");
        Schema::drop("seatgroupmanagers");
        Schema::drop("seatgroupalliances");
        Schema::drop("seatgroupcorporations");
        Schema::drop("seatgroupusers");
        Schema::drop("seatgroups");
    }
}
