<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Member;
use App\Saving;
use Illuminate\Http\Request;
use Auth;

class profile_withdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       if(Auth::user()->id==$request->user_id){
        $member=Member::where('user_id',$request->user_id)->pluck('id');

        $data['savings']=Saving::where('type','withdrawal')->whereIn('member_id',$member)->orderBy('id','desc')->get();
            return view('backend.profile.profile_withdrawal.index',$data);
       }
       else{
           return redirect('/login');
       }

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(Auth::user()->id==$request->user_id){
            $data['member_array']=$this->member_array($request->user_id);
                return view('backend.profile.profile_withdrawal.create',$data);
           }
           else{
               return redirect('/login');
           }
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
            'member_id'=>'required',
            'amount'=>'required|numeric',
            'date'=>'required|date',
        );

        $this->validate($request,$rules);

        $saving=new Saving;
        $saving->member_id=$request->member_id;
        $saving->type='withdrawal';
        $saving->amount=$request->amount;
        $saving->date=$request->date;
        $saving->user_id=Auth::user()->id;

        $saving->save();
        \Alert::success('Success', 'Savings withdrawal has been saved successfully');

        return redirect()->route('profileWithdrawal.index',['user_id'=>Auth::user()->id]);





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
        
       $member_id=Saving::find($id)->member_id;
       $user_id=Member::find($member_id)->user_id;
        
        if(Auth::user()->id==$user_id){
            $data['saving']=Saving::find($id);
            $data['member_array']=$this->member_array($user_id);
            return view('backend.profile.profile_withdrawal.edit',$data);
        }
           else{
               return redirect('/login');
           }
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
            'member_id'=>'required',
            'amount'=>'required|numeric',
            'date'=>'required|date',
        );

        $this->validate($request,$rules);

        $saving=Saving::find($id);
        $saving->member_id=$request->member_id;
        $saving->type='withdrawal';
        $saving->amount=$request->amount;
        $saving->date=$request->date;
        $saving->user_id=Auth::user()->id;

        $saving->update();
        \Alert::success('Success', 'Withdrawal has been updated successfully');

        return redirect()->route('profileWithdrawal.index',['user_id'=>Auth::user()->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
            Saving::find($request->saving_id)->delete();
        \Alert::success('Success', 'Savings has been deleted successfully');

        return redirect()->route('savings.index');


    }


    public function profile_saving_create2($id)
    {

        $data['member']=Member::find($id);
        return view('backend.profile.profile_withdrawal.create2',$data);
    }


    

    private function member_array($user_id)
    {
        $members = Member::where('user_id',$user_id)->get();
        $member_array = array('' => '--Please Select--');

        foreach($members as $member)
        {
            $member_array[$member->id] =$member->name;
        }
        return $member_array;
    }

    
}
