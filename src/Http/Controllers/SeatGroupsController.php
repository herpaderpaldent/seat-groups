<?php

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers;

use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Illuminate\Http\Request;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\Acl\Role;

class SeatGroupsController extends Controller
{
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

        Seatgroup::create($seatgroup);
        //ToDo: if logic implementation for failed validation + forward to view
        return back()->with('success', 'SeAT-Group has been added');
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
        /*$Roles=Role::pluck('title','id');

        if(Seatgroup::find($id)->isManager(auth()->user(),$id) || auth()->user()->has('Superuser')){
            return view('seatgroups::edit')
                ->with('group',SeatGroup::find($id))
                ->with('roles',$Roles);
        }*/
        $seatgroup = Seatgroup::find($id);
        $Roles=Role::pluck('title','id');

        return view('seatgroups::edit', compact('seatgroup','id'))
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
        $seatgroup = Seatgroup::find($id);
        $seatgroup->delete();
        return redirect()->route('seatgroups.index')
            ->with('success', 'SeAT-Group has been deleted');
    }
}
