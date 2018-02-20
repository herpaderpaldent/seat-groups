<?php

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers;

use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroupmanager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Configuration\UserRespository;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\Acl\Role;

class SeatGroupsController extends Controller
{
    use AccessManager, UserRespository, Character, Corporation;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('seatgroups::index')
            ->with('autogroups',SeatGroup::all()->where('type','=','auto'))
            ->with('opengroups', SeatGroup::all()->where('type','=','open'))
            ->with('managedgroups',SeatGroup::all()->where('type','=','managed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $Roles=Role::pluck('title','id');

        return view('seatgroups::create')
            ->with('roles',$Roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $seatgroup = $this->validate(request(), [
            'name'=>'required|min:5',
            'description'=>'required|min:10',
            'type' => 'required',
            'role_id' => 'numeric'
        ]);

        $group=Seatgroup::create($seatgroup);
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

        $all_users = $this->getAllUsers()
            ->pluck('name')
            ->toArray();

        //$all_characters = $this->getAllCharacters();


        //$all_corporations = $this->getAllCorporations();
        $all_corporations = $this->getAllCharacters(); // TODO: Remove this line


        $seatgroup = Seatgroup::find($id);
        $Roles=Role::pluck('title','id');

        return view('seatgroups::edit', compact('seatgroup','id','all_corporations'))
            ->with('roles',$Roles);

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

        $this->validate(request(), [
            'name'=>'required|min:5',
            'description'=>'required|min:10',
            'type' => 'required',
            'role_id' => 'numeric'
        ]);
        $seatgroup->name = $request->get('name');
        $seatgroup->description = $request->get('description');
        $seatgroup->type = $request->get('type');
        $seatgroup->role_id = $request->get('role_id');
        $seatgroup->save();
        return redirect()->route('seatgroups.index')
            ->with('success', 'SeAT-Group has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::user()->hasRole('Superuser')){
            $seatgroupmanager = Seatgroupmanager::with('group_id','=',$id);
            $seatgroupmanager->delete();
            $seatgroup = Seatgroup::find($id);
            $seatgroup->delete();
        return redirect()->route('seatgroups.index')
            ->with('success', 'SeAT-Group has been deleted');
        } else return redirect()->back()->with('error', 'illegal delete request. You must be superuser');
    }
}
