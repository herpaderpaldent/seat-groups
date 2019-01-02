<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatGroupNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('herpaderp_seat_group_notifications', function (Blueprint $table) {
            $table->string('channel_id');
            $table->string('via');
            $table->timestamps();

            $table->primary('channel_id', 'herpaderp_seatgroup_notification_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('herpaderp_seat_group_notifications');
    }
}
