<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 04.09.2018
 * Time: 11:30.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Logs;

use Herpaderpaldent\Seat\SeatGroups\Models\SeatgroupLog;
use Seat\Web\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class SeatGroupLogsController extends Controller
{
    public function getSeatGroupDeleteButton()
    {
        $logCount = SeatgroupLog::count();

        return view('seatgroups::logs.partials.delete-button', compact('logCount'))->render();

    }

    public function getSeatGroupLogs()
    {
        $logs = SeatgroupLog::query();

        return DataTables::of($logs)
            ->editColumn('created_at', function ($row) {
                return view('seatgroups::logs.partials.date', compact('row'));
            })
            ->editColumn('event', function ($row) {
                return view('seatgroups::logs.partials.event', compact('row'));
            })
            ->rawColumns(['created_at', 'event'])
            ->make(true);

    }

    public function truncateSeatGroupLogs()
    {
        SeatgroupLog::query()->truncate();

        return redirect()->back()
            ->with('success', 'The logs has been truncated');
    }
}
