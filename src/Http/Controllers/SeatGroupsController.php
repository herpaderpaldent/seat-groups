<?php

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers;

use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\GetCorporationListAction;
use Herpaderpaldent\Seat\SeatGroups\Actions\SeatGroups\CreateSeatGroup;
use Herpaderpaldent\Seat\SeatGroups\Actions\SeatGroups\DeleteSeatGroup;
use Herpaderpaldent\Seat\SeatGroups\Actions\SeatGroups\GetChangelog;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\CreateSeatGroupRequest;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\DeleteSeatGroupRequest;
use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Illuminate\Http\Request;
use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Group;

class SeatGroupsController extends Controller
{
    use Character, Corporation;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $seatgroups = SeatGroup::all()->filter(function ($seatgroup) {
            return $seatgroup->isAllowedToSeeSeatGroup();
        });

        return view('seatgroups::index', compact('seatgroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $roles = Role::all();

        return view('seatgroups::create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Herpaderpaldent\Seat\SeatGroups\Http\Validation\CreateSeatGroupRequest $request
     * @param \Herpaderpaldent\Seat\SeatGroups\Actions\SeatGroups\CreateSeatGroup     $action
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSeatGroupRequest $request, CreateSeatGroup $action)
    {

        $seat_group = $action->execute($request->all());

        return redirect()->route('seatgroups.edit', $seat_group->id)
            ->with('success', 'SeAT-Group has been added');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int                                                                           $id
     *
     * @param \Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\GetCorporationListAction $action
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id, GetCorporationListAction $action)
    {
        $all_corporations = $action->execute([
            'seatgroup_id' =>$id,
            'origin' => 'SeatGroupsController',
        ]);
        $all_corporations_for_title = $action->execute([
            'seatgroup_id' =>$id,
            'origin' => 'corporation-tile-form',
        ]);
        $roles = Role::all();
        $seatgroup = Seatgroup::find($id);
        $available_seatgroups = Seatgroup::whereNotIn('id', $seatgroup->children->pluck('id')->push($id)->toArray())->get();
        $all_groups = Group::all();

        return view('seatgroups::edit', compact('seatgroup', 'id', 'all_corporations', 'roles', 'corporations', 'all_groups', 'all_corporations_for_title', 'available_seatgroups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate(request(), [
            'name'        => 'required|min:5',
            'description' => 'required|min:10',
            'type'        => 'required',
            'role_id'     => 'numeric',
        ]);

        $seat_group = Seatgroup::find($id);

        $seat_group->fill([
            'name'        => $request->get('name'),
            'description' => $request->get('description'),
            'type'        => $request->get('type'),
        ])->save();

        $role_ids = $request->get('roles');
        $seat_group->role()->sync($role_ids);

        return redirect()->back()
            ->with('success', 'SeAT-Group has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteSeatGroupRequest $request, DeleteSeatGroup $action)
    {

        if ($action->execute($request->all())) {
            return redirect()->route('seatgroups.index')
                ->with('success', 'SeAT-Group has been deleted');
        }

        return redirect()->back()
            ->with('error', 'illegal delete request. You must be superuser');
    }

    public function about(GetChangelog $action)
    {
        $changelog = $action->execute();

        return view('seatgroups::about', compact('changelog'));
    }
}
