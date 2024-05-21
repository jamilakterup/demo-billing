<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Member;
use App\Saving;
use App\Savings_withdrawal;
use Illuminate\Http\Request;
use Auth;

class Savings_withdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['savings_withdrawals']=Saving::orderBy('id', 'DESC')->where('type','withdrawal')->get();
        return view('backend.savings_withdrawal.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['member_array']=Member::member_array();
        return view('backend.savings_withdrawal.create',$data);
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

       $available=Helper::withdrawal_amount_check($request->member_id);

        if($available<$request->amount){

            \Alert::error('Not available', $request->amount.' '.'taka is not available. '. $available.' taka is available.');
            return redirect()->route('savings_withdrawal.index');
        }

        $saving=new Saving;
        $saving->member_id=$request->member_id;
        $saving->type='withdrawal';
        $saving->amount=$request->amount;
        $saving->date=$request->date;
        $saving->user_id=Auth::user()->id;

        $saving->save();
        \Alert::success('Success', 'Savings withdrawal has been saved successfully');

        return redirect()->route('savings_withdrawal.index');
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
        $data['savings_withdrawal']=Saving::find($id);
        $data['member_array']=Member::member_array();
        return view('backend.savings_withdrawal.edit',$data);
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

        $available=Helper::withdrawal_amount_check($request->member_id)+$saving->amount;

        if($available<$request->amount){

            \Alert::error('Not available', $request->amount.' '.'taka is not available. '. $available.' taka is available.');
            return redirect()->route('savings_withdrawal.index');
        }


        $saving->member_id=$request->member_id;
        $saving->type='withdrawal';
        $saving->amount=$request->amount;
        $saving->date=$request->date;
        $saving->user_id=Auth::user()->id;

        $saving->update();
        \Alert::success('Success', 'Savings withdrawal has been updated successfully');

        return redirect()->route('savings_withdrawal.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
