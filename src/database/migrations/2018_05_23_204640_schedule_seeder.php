<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Seat\Services\Models\Schedule;

class ScheduleSeeder extends Migration
{

    protected $schedule = [
        [
            'command'    => 'seat-groups:users:update',
            'expression' => '*/15 * * * *',
            'allow_overlap'     => false,
            'allow_maintenance' => false,
            'ping_before'       => null,
            'ping_after'        => null,
        ],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        foreach ($this->schedule as $job) {
            $existing = Schedule::where('command', $job['command'])
                ->first();
            if ($existing) {
                $existing->update([
                    'expression' => $job['expression'],
                ]);
            }
            if (! $existing) {
                DB::table('schedules')->insert($job);
            }
        }

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
