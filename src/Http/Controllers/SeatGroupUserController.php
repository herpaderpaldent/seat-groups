<?php

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers;

use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\User;

class SeatGroupUserController extends Controller
{
    use AccessManager;
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
    public function update($id)
    {
        //
        $seatgroup=Seatgroup::find($id);

        if($seatgroup->type == 'open'){

            if($seatgroup->isAllowedToSeeSeatGroup()){
                $seatgroup->group()->attach(Auth::user()->group->id);

                // Add Role to UserGroup
                foreach ($seatgroup->role as $role){
                    $this->giveGroupRole(Auth::user()->group->id,$role->id);
                }

            } else {
                return redirect()->back()->with('error', 'You are not allowed to opt-in into this group');
            }

        }

        return redirect()->back()->with('success', 'Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $seatgroup=Seatgroup::find($id);

        if($seatgroup->type == 'open'){
            $seatgroup->group()->detach(Auth::user()->group->id);
            // Remove Role from UserGroup
            foreach ($seatgroup->role as $role){
                $this->removeGroupFromRole(Auth::user()->group->id,$role->id);
            }
        }


        return redirect()->back()->with('success', ' removed');

    }
}
