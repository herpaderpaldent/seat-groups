<?php

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers;

use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Illuminate\Http\Request;
use Seat\Web\Http\Controllers\Controller;

class SeatGroupCorporationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $seatgroup=Seatgroup::find($id);

        $this->validate(request(),[
            'corporations'=>'required'
        ]);

        $corporationarray = $request->get('corporations');
        $seatgroup->corporation()->sync($corporationarray);

        return redirect()->back()->with('success', 'Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($group_id,$corporation_id)
    {
        $seatgroup=Seatgroup::find($group_id);
        $name=$seatgroup->corporation()->find($corporation_id)->get();
        $seatgroup->corporation()->detach($corporation_id);

        return redirect()->back()->with('success', $name->pluck('name'). ' removed');

    }
}
