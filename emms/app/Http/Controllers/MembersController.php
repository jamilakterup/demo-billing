<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Instalment;
use App\Loan;
use App\Saving;
use App\User;
use Illuminate\Http\Request;
use App\Member;
use RealRashid\SweetAlert\Facades\Alert;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['members']=Member::orderBy('id', 'DESC')->get();
        return view('backend.members.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['agent_array']=User::options();
        return view('backend.members.create',$data);
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
            'user_id'=>'required',
            'name'=>'required',
            'father_name'=>'required',
            'mother_name'=>'required',
            'spouse_name'=>'required',
            'occupation'=>'required',
            'monthly_income'=>'required',
            'education'=>'required',
            'dob'=>'required',
            'gender'=>'required',
            'phone'=>'required|min:11|unique:members,phone',
            'nid'=>'required|unique:members,nid',
            'vill'=>'required',
            'post'=>'required',
            'ps'=>'required',
            'dist'=>'required',
            'photo'=>'required|image|mimes:jpeg,png,jpg|max:1024|dimensions:width=300,height=300',
        );

        $this->validate($request,$rules);

        $member=new Member;
        $member->user_id=$request->user_id;
        $member->name=$request->name;
        $member->father_name=$request->father_name;
        $member->mother_name=$request->mother_name;
        $member->spouse_name=$request->spouse_name;
        $member->occupation=$request->occupation;
        $member->monthly_income=$request->monthly_income;
        $member->education=$request->education;
        $member->dob=$request->dob;
        $member->gender=$request->gender;
        $member->phone=$request->phone;
        $member->email=$request->email;
        $member->nid=$request->nid;
        $member->vill=$request->vill;
        $member->post=$request->post;
        $member->ps=$request->ps;
        $member->dist=$request->dist;

        if($request->hasFile('photo')){
            $image=$request->file('photo');
            $extension=$image->getClientOriginalExtension();
            $filename="member_image_".uniqid().".".$extension;
            $destination=public_path().'/img/members';
            $image->move($destination,$filename);
            $member->photo=$filename;
        }

        $member->save();
       \Alert::success('Success', 'Member has been saved successfully');

       return redirect()->route('member.index');





    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['member']=Member::find($id);
        $data['loans']=Loan::where('member_id',$id)->get();
        $data['savings']=Saving::where('member_id',$id)->get();
        $data['total_paid']=Instalment::where('loan_id',$id)->sum('payment');
        $data['paid_instalment']=Helper::instalment_count($id);



        return view('backend.members.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['member']=Member::find($id);
        $data['agent_array']=User::options();
        return view('backend.members.edit',$data);
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
            'user_id'=>'required',
            'name'=>'required',
            'father_name'=>'required',
            'mother_name'=>'required',
            'spouse_name'=>'required',
            'occupation'=>'required',
            'monthly_income'=>'required',
            'education'=>'required',
            'dob'=>'required',
            'gender'=>'required',
            'phone'=>'required|min:11',
            'nid'=>'required',
            'vill'=>'required',
            'post'=>'required',
            'ps'=>'required',
            'dist'=>'required',

        );

        $this->validate($request,$rules);

        $member=Member::find($id);
        $member->user_id=$request->user_id;
        $member->name=$request->name;
        $member->father_name=$request->father_name;
        $member->mother_name=$request->mother_name;
        $member->spouse_name=$request->spouse_name;
        $member->occupation=$request->occupation;
        $member->monthly_income=$request->monthly_income;
        $member->education=$request->education;
        $member->dob=$request->dob;
        $member->gender=$request->gender;
        $member->phone=$request->phone;
        $member->email=$request->email;
        $member->nid=$request->nid;
        $member->vill=$request->vill;
        $member->post=$request->post;
        $member->ps=$request->ps;
        $member->dist=$request->dist;

        if($request->hasFile('photo')){
            $image=$request->file('photo');
            $extension=$image->getClientOriginalExtension();
            $filename="member_image_".uniqid().".".$extension;
            $destination=public_path().'/img/members';
            $image->move($destination,$filename);
            $member->photo=$filename;
        }

        $member->update();
        \Alert::success('Success', 'Member has been updated successfully');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            Member::find($id)->delete();
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            \Alert::success('Error', $e->errorInfo[2]);
        }
    }

}
