<?php

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers;

use Herpaderpaldent\Seat\SeatGroups\Actions\Managers\AddManagerAction;
use Herpaderpaldent\Seat\SeatGroups\Actions\Managers\RemoveManagerAction;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\Manager\AddManagerRequest;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\Manager\RemoveManagerRequest;
use Herpaderpaldent\Seat\SeatGroups\Jobs\GroupSync;
use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Illuminate\Http\Request;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

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

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function acceptApplication(Request $request, $seat_group_id) //TODO: refactor this to Application Manager
    {
        switch($request->input('action')){
            case 'accept':
                $seatgroup = Seatgroup::find($seat_group_id);

                $seatgroup->group()->updateExistingPivot($request->input('group_id'), [
                    'on_waitlist' => 0,
                ]);

                return redirect()->back()
                    ->with([
                        'success' => 'User accepted',
                        'activeTab' => 'managed_group',
                        'ModalSeATGroup' => $seat_group_id,
                    ]);
                break;
            case 'deny':
                $seatgroup = Seatgroup::find($seat_group_id);

                $seatgroup->group()->detach($request->input('group_id'));

                return redirect()
                    ->back()
                    ->with([
                        'success' => 'User removed',
                        'activeTab' => 'managed_group',
                        'ModalSeATGroup' => $seat_group_id,
                    ]);

        }

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
     *
     * @param \Herpaderpaldent\Seat\SeatGroups\Http\Validation\Manager\RemoveManagerRequest $request
     * @param \Herpaderpaldent\Seat\SeatGroups\Actions\Managers\RemoveManagerAction         $action
     *
     * @return \Illuminate\Http\Response
     */
    public function removeManager(RemoveManagerRequest $request, RemoveManagerAction $action)
    {

        return $action->execute($request->all());

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Herpaderpaldent\Seat\SeatGroups\Http\Validation\Manager\AddManagerRequest $request
     * @param \Herpaderpaldent\Seat\SeatGroups\Actions\Managers\AddManagerAction         $action
     *
     * @return \Illuminate\Http\Response
     */
    public function addManager(AddManagerRequest $request, AddManagerAction $action)
    {
        return $action->execute($request->all());

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

                return redirect()->back()->with([
                    'success' => 'Updated',
                    'activeTab' => 'open_group',
                ]);
            }

            return redirect()->back()->with([
                'error' => 'You are not allowed to opt-in into this group',
                'activeTab' => 'open_group',
            ]);
        }

        //Handle managed group
        if ($seatgroup->type == 'managed') {
            //First check if the user is allowed to opt-in into a group
            if ($seatgroup->isAllowedToSeeSeatGroup()) {
                //Secound attach user group to SeAT-group
                $seatgroup->group()->attach(auth()->user()->group->id, [
                    'on_waitlist' => 1,
                ]);

                return redirect()->back()->with([
                    'info' => 'you successfully applied to ' . $seatgroup->name,
                    'activeTab' => 'managed_group',
                ]);
            }

            return redirect()->back()->with([
                'error' => 'You are not allowed to apply for this group',
                'activeTab' => 'managed_group',
            ]);
        }

        //Handle hidden group
        if ($seatgroup->type == 'hidden') {
            if(auth()->user()->hasRole('seatgroups.create')) {
                $this->validate(request(), [
                    'groups'=>'required',
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
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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
                return redirect()->back()->with('success', 'Application removed');
            }
        }

        dispatch(new GroupSync(auth()->user()->group));

        return redirect()->back()->with('success', 'You are no longer member of ' . $seatgroup->name);
    }

    public function getMembersTable($id)
    {
        $seatgroup_members = Seatgroup::find($id)->group;

        return Datatables::of($seatgroup_members)
            ->addColumn('name', function ($row) {
                return view('seatgroups::partials.modal-partials.modal-name', compact('row'))->render();
            })
            ->addColumn('actions', function ($row) {
                return view('seatgroups::partials.modal-partials.modal-actions', compact('row'))->render();
            })
            ->make(true);
    }
}
