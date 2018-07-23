<?php

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers;

use Herpaderpaldent\Seat\SeatGroups\Actions\Groups\SyncGroup;
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
    public function acceptApplication($seat_group_id, $group_id)
    {
        $seatgroup = Seatgroup::find($seat_group_id);

        $seatgroup->group()->updateExistingPivot($group_id, [
            'on_waitlist' => 0,
        ]);

        return redirect()->back()->with('success', 'User accepted');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeMember($seat_group_id, $group_id)
    {
        $seatgroup = Seatgroup::find($seat_group_id);

        $seatgroup->group()->detach($group_id);

        return redirect()->back()->with('success', 'User removed');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeManager($seat_group_id, $group_id)
    {
        $seatgroup = Seatgroup::find($seat_group_id);

        $seatgroup->group()->updateExistingPivot($group_id, [
            'is_manager' => 0,
        ]);

        return redirect()->back()->with('success', 'Manager removed');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addManager(Request $request, $id)
    {
        //
        $seatgroup = Seatgroup::find($id);

        $this->validate(request(),[
            'groups' => 'required'
        ]);

        $groups = $request->get('groups');
        foreach ($groups as $group) {
            if (in_array($group, $seatgroup->waitlist->map(function($group) { return $group->id; })->toArray())) {
                redirect()->back()->with('warning', 'User must be first member before made manager');
            }
            elseif (in_array($group, $seatgroup->member->map(function($group) { return $group->id; })->toArray())) {
                $seatgroup->group()->updateExistingPivot($group, [
                    'is_manager' => 1,
                ]);
            } else {
                $seatgroup->group()->attach($group, [
                    'is_manager' => 1,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Updated');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        $seatgroup = Seatgroup::find($id);

        //Handle open group
        if ($seatgroup->type == 'open') {
            //First check if the user is allowed to opt-in into a group
            if ($seatgroup->isAllowedToSeeSeatGroup()) {
                //Secound attach user group to SeAT-group
                $seatgroup->group()->attach(auth()->user()->group->id);
                // Add Role to user group
                foreach ($seatgroup->role as $role) {
                    $this->giveGroupRole(auth()->user()->group->id, $role->id);
                }

                return redirect()->back()->with('success', 'Updated');
            }

            return redirect()->back()->with('error', 'You are not allowed to opt-in into this group');
        }

        //Handle managed group
        if ($seatgroup->type == 'managed') {
            //First check if the user is allowed to opt-in into a group
            if ($seatgroup->isAllowedToSeeSeatGroup()) {
                //Secound attach user group to SeAT-group
                $seatgroup->group()->attach(auth()->user()->group->id, [
                    'on_waitlist' => 1,
                ]);

                return redirect()->back()->with('info', 'you sucessfully applied to ' . $seatgroup->name);
            }

            return redirect()->back()->with('error', 'You are not allowed to apply for this group');
        }

        //Handle hidden group
        if ($seatgroup->type == 'hidden') {
            if(auth()->user()->hasRole('seatgroups.edit')) {
                $this->validate(request(),[
                    'groups'=>'required'
                ]);
                $groups = $request->get('groups');
                foreach ($groups as $group) {
                    $seatgroup->group()->attach($group);
                    return redirect()->back()->with('success', 'Updated');
                }
            }
        }

        return redirect()->back()->with('warning', 'ups something went wrong');
    }

    public function removeGroupFromSeatGroup($seat_group_id, $group_id)
    {
        Seatgroup::find($seat_group_id)->group()->detach($group_id);
        return redirect()->back()->with('success', ' removed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int                                                      $id
     * @param \Herpaderpaldent\Seat\SeatGroups\Actions\Groups\SyncGroup $action
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, SyncGroup $action)
    {
        $seatgroup = Seatgroup::find($id);

        if ($seatgroup->type == 'open') {
            $seatgroup->group()->detach(auth()->user()->group->id);
            // Remove Role from UserGroup
            foreach ($seatgroup->role as $role) {
                $this->removeGroupFromRole(auth()->user()->group->id, $role->id);
            }
        }
        if ($seatgroup->type == 'managed') {
            $seatgroup->group()->detach(auth()->user()->group->id);
            if ($seatgroup->onWaitlist()) {
                return redirect()->back()->with('success', ' removed');
            }
        }

        $action->execute(auth()->user()->group); //ToDo: Refactor this and start using Events

        return redirect()->back()->with('success', ' removed');
    }
}
