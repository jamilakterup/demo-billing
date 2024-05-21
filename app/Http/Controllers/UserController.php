<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users=User::all();
        return view('user.user-index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles=Role::all()->pluck('name','id')->toArray();
        return view('user.user-create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules=array(
            'name'=>'required|max:255',
            'email'=>'required|email|max:255|unique:users,email',
            'mobile'=>'required|numeric|min:11',
            'password'=>'required|min:8|max:255',
            'confirmed_password'=>'required_with:password|same:password|min:8',
            'photo'=>'sometimes|image|mimes:jpeg,png,jpg|max:1024'
        );

        $this->validate($request,$rules);
        $user=new User;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->mobile=$request->mobile;
        $user->password=bcrypt($request->password);
        $user->save();

        if($request->hasFile('photo')){
            $image=$request->file('photo');
            $ext=$image->getClientOriginalExtension();
            $file_name='profile_pic_'.$user->id.'.'.$ext;

            $des=public_path().'/photo';
            $image->move($des,$file_name);
            $user->profile_photo_path=$file_name;
            $user->save();
        }

        if(count($request->roles)>0){
            $user->assignRole($request->roles);
        }

        Alert::success('Success', 'User has been saved successfully.');
        return redirect()->route('user.index');

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
        $user=User::find($id);
        $roles=Role::all()->pluck('name','id')->toArray();
        return view('user.user-edit',compact('user','roles'));
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
        $rules=array(
            'name'=>'required|max:255',
            'email'=>'required|email|max:255|unique:users,email,'.$id.'',
            'mobile'=>'required|numeric|min:11',
            'password'=>'nullable|min:8|max:255',
            'confirmed_password'=>'nullable|required_with:password|same:password|min:8',
            'photo'=>'sometimes|image|mimes:jpeg,png,jpg|max:1024'
        );






        $this->validate($request,$rules);

        $user=User::find($id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->mobile=$request->mobile;
        if(isset($request->password)){
            $user->password=bcrypt($request->password);
        }
        $user->update();

        if($request->hasFile('photo')){
            $image=$request->file('photo');
            $ext=$image->getClientOriginalExtension();
            $file_name='profile_pic_'.$user->id.'.'.$ext;

            $des=public_path().'/photo';
            $image->move($des,$file_name);
            $user->profile_photo_path=$file_name;
            $user->update();
        }

        $user->roles()->detach();
        if($request->roles){
            $user->assignRole($request->roles);
        }

        Alert::success('Success', 'User has been update successfully.');
        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        Alert::success('Success', 'User has been deleted successfully.');
        return redirect()->route('user.index');
    }
}
