<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Member;
use App\Saving;
use Illuminate\Http\Request;
use Auth;

class SavingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['savings']=Saving::orderBy('id', 'DESC')->where('type','savings')->get();
        return view('backend.saving.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['member_array']=Member::member_array();
        return view('backend.saving.create',$data);
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
        $saving->type='savings';
        $saving->amount=$request->amount;
        $saving->date=$request->date;
        $saving->user_id=Auth::user()->id;

        $saving->save();
        \Alert::success('Success', 'Savings has been saved successfully');

        return redirect()->route('savings.index');





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
        $data['saving']=Saving::find($id);
        $data['member_array']=Member::member_array();
        return view('backend.saving.edit',$data);
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
        $saving->type='savings';
        $saving->amount=$request->amount;
        $saving->date=$request->date;
        $saving->user_id=Auth::user()->id;

        $saving->update();
        \Alert::success('Success', 'Savings has been updated successfully');

        return redirect()->route('savings.index');
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


    public function create2($id)
    {

        $data['member']=Member::find($id);
        return view('backend.saving.create2',$data);
    }
}
