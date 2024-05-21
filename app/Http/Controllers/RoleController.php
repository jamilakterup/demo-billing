<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Module;
use RealRashid\SweetAlert\Facades\Alert;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles=Role::all();
        return view('role.role-index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modules=Module::all();
        return view('role.role-create',compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $roleName= $request->name;
        $permissions= $request->permission;

        $request->validate([
            'name'=>'required|unique:roles,name'
        ],[
            'name.required'=>'Please enter role name',
            'name.unique'=>'This role name is already exist'
        ]);

        $role=new Role;
        $role->name=$roleName;
        $role->guard_name='web';
        $role->save();

        if(!empty($permissions)){
            $role->syncPermissions($permissions);
        }

        Alert::success('Success', 'Role has been saved successfully.');
        return redirect()->route('role.index');

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
        $role=Role::find($id);
        $modules=Module::all();
        return view('role.role-edit',compact('role','modules'));
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
        $roleName= $request->name;
        $permissions= $request->permission;

        $request->validate([
            'name'=>'required|unique:roles,name,'.$id.'',
        ],[
            'name.required'=>'Please enter role name',
            'name.unique'=>'This role name is already exist'
        ]);

        $role=Role::find($id);
        $role->name=$roleName;
        $role->guard_name='web';
        $role->update();

        if(!empty($permissions)){
            $role->syncPermissions($permissions);
        }

        Alert::success('Success', 'Role has been update successfully.');
        return redirect()->route('role.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //return $id;
        $role=Role::find($id);
        if(!is_null($role)){
            $role->delete();
        }
        Alert::success('Success', 'Role has been deleted successfully.');
        return redirect()->route('role.index');
    }
}
