<?php

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers;



use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\AddCorpAffiliation;
use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\RemoveCorpAffiliation;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\AddAffiliation;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\RemoveAffiliation;
use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $seatgroups = SeatGroup::all();

        return view('seatgroups::index', compact('seatgroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::all();

        return view('seatgroups::create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $seat_group = $this->validate(request(), [
            'name'        => 'required|min:5',
            'description' => 'required|min:10',
            'type'        => 'required',
            'role_id'     => 'numeric'
        ]);

        $group = Seatgroup::create($seat_group);

        $role_ids = $request->get('roles');

        $group->role()->sync($role_ids);


        //ToDo: if logic implementation for failed validation + forward to view
        return redirect()->route('seatgroups.edit', $group->id)
            ->with('success', 'SeAT-Group has been added');
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
        $all_corporations = $this->getAllCorporations();

        // ToDo: show selected roles on Edit blade
        $roles        = Role::all();
        $seatgroup    = Seatgroup::find($id);
        $all_groups   = Group::all();
        $corporations = $seatgroup->corporation;

        return view('seatgroups::edit', compact('seatgroup','id', 'all_corporations', 'roles', 'corporations', 'all_groups'));
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
        $this->validate(request(), [
            'name'        => 'required|min:5',
            'description' => 'required|min:10',
            'type'        => 'required',
            'role_id'     => 'numeric'
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($group_id)
    {
        if (auth()->user()->hasSuperUser()) {
            $seatgroup = Seatgroup::find($group_id);
            $seatgroup->delete();

            return redirect()->route('seatgroups.index')
                ->with('success', 'SeAT-Group has been deleted');
        }

        return redirect()->back()
            ->with('error', 'illegal delete request. You must be superuser');
    }

    public function addAffilliation(AddAffiliation $request, AddCorpAffiliation $action){

        if($action->execute($request->all()))
        {
            return redirect()->back()->with('success', 'Updated');
        }

        return redirect()->back()->with('warning', 'Ups something went wrong');

    }

    /**
     * Remove corp affiliations from SeAT Group
     *
     * @param \Herpaderpaldent\Seat\SeatGroups\Http\Validation\RemoveAffiliation          $request
     * @param \Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\RemoveCorpAffiliation $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeAffiliation(RemoveAffiliation $request, RemoveCorpAffiliation $action){

        $name = $action->execute($request->all());

        return redirect()->back()->with('success', $name . ' removed');
    }
}
