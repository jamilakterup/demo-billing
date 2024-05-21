<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Instalment;
use App\Instalment_type;
use App\Loan;
use App\Loan_payment_calender;
use App\Member;
use App\Saving;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['loans']=Loan::orderBy('id', 'DESC')->get();
//        $data['paid_count']
        return view('backend.loan.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['member']=Member::find($request->member_id);
        $data['instalment_type_array']=Instalment_type::instalment_type();

        return view('backend.loan.create',$data);
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
            'principal'=>'required|integer|digits_between:3,6',
            'savings'=>'required|integer|digits_between:2,6',
            'paid'=>'required|integer|digits_between:3,6',
            'number_of_instalment'=>'required|integer',
            'instalment_type'=>'required',
            'instalment_start_date'=>'required'
        );

        $this->validate($request,$rules);

        $principal=$request->principal;
        $savings=$request->savings;

        $ten_percent=($principal*10)/100;
        if($savings<$ten_percent){
            \Alert::error('Inavalid savings amount', 'Savings should be at least 10% of principal');
            return redirect()->back();
        }

   



        $loan=new Loan;
        $loan->user_id=$request->user_id;
        $loan->member_id=$request->member_id;
        $loan->principal=$request->principal;
        $loan->interest=$request->paid-$request->principal;
        $loan->number_of_instalment=$request->number_of_instalment;
        $loan->instalment_type=$request->instalment_type;
        $loan->instalment_start_date=$request->instalment_start_date;
        $loan->date=date('Y:m:d H:i:s');


        $loan->save();

        ///savings

        
        $savings_entry=new Saving;
        $savings_entry->member_id=$request->member_id;
        $savings_entry->type='savings';
        $savings_entry->amount=$request->savings;
        $savings_entry->date=date('Y:m:d H:i:s');
        $savings_entry->user_id=Auth::user()->id;
        $savings_entry->save();


        ///loan payment calender


        $total_instalment=$loan->number_of_instalment;

        $total_payment_amount=$loan->interest+$loan->principal;
        $start_date=$loan->instalment_start_date;
        $instalment_amount=$total_payment_amount/$total_instalment;


        $interval=Helper::instalment_type()[$loan->instalment_type];

//        $date = strtotime($start_date);
        $date=date('Y-m-d',(strtotime ( '-'.$interval.'' , strtotime ( $start_date) ) ));
        $date2=strtotime($date);






        for($i=1; $i<=$total_instalment; $i++){
            $loan_payment_calender=new Loan_payment_calender;
             $newDate = date("Y-m-d", strtotime($interval, $date2));
             $loan_payment_calender->payment_date=$newDate;
             $loan_payment_calender->loan_id=$loan->id;
             $loan_payment_calender->number=$i;
             $loan_payment_calender->amount=$instalment_amount;
             $loan_payment_calender->save();
             $date2=strtotime($newDate);
        }

        \Alert::success('Success', 'Loan has been saved successfully');

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
        $data['loan']=Loan::find($id);
        $data['total_paid']=Instalment::where('loan_id',$id)->sum('payment');
        $data['paid_instalment']=Helper::instalment_count($id);
        $data['payment_calenders']=Loan_payment_calender::where('loan_id',$id)->paginate(10);
        return view('backend.loan.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ins_count=Instalment::where('loan_id',$id)->count();
        $loan_paid=Loan::find($id)->paid;
        if($ins_count>0 && $loan_paid==0){
            \Alert::error('Instalment is running', 'You can not edit this loan.');
            return redirect()->route('loan.index');
        }
        elseif ($loan_paid==1){
            \Alert::error('Instalment is Completed', 'You can not edit this loan.');
            return redirect()->route('loan.index');
        }
        else{
            $data['loan']=Loan::find($id);
            $data['instalment_type_array']=Instalment_type::instalment_type();

            return view('backend.loan.edit',$data);
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
            'principal'=>'required|integer|digits_between:3,6',
            'paid'=>'required|integer|digits_between:3,6',
            'number_of_instalment'=>'required|integer',
            'instalment_type'=>'required',
            'instalment_start_date'=>'required'
        );

        $this->validate($request,$rules);


        ///delete payment calender
        Loan_payment_calender::where('loan_id',$id)->delete();

        ///update loan
        $loan=Loan::find($id);
        $loan->user_id=$request->user_id;
        $loan->member_id=$request->member_id;
        $loan->principal=$request->principal;
        $loan->interest=$request->paid-$request->principal;
        $loan->number_of_instalment=$request->number_of_instalment;
        $loan->instalment_type=$request->instalment_type;
        $loan->instalment_start_date=$request->instalment_start_date;
        $loan->date=date('Y:m:d H:i:s');


        $loan->update();

        ///loan payment calender


        $total_instalment=$loan->number_of_instalment;

        $total_payment_amount=$loan->interest+$loan->principal;
        $start_date=$loan->instalment_start_date;
        $instalment_amount=$total_payment_amount/$total_instalment;


        $interval=Helper::instalment_type()[$loan->instalment_type];

//        $date = strtotime($start_date);
        $date=date('Y-m-d',(strtotime ( '-'.$interval.'' , strtotime ( $start_date) ) ));
        $date2=strtotime($date);






        for($i=1; $i<=$total_instalment; $i++){
            $loan_payment_calender=new Loan_payment_calender;
            $newDate = date("Y-m-d", strtotime($interval, $date2));
            $loan_payment_calender->payment_date=$newDate;
            $loan_payment_calender->loan_id=$loan->id;
            $loan_payment_calender->number=$i;
            $loan_payment_calender->amount=$instalment_amount;
            $loan_payment_calender->save();
            $date2=strtotime($newDate);
        }










        \Alert::success('Success', 'Loan has been updated successfully');

        return redirect()->route('loan.index');
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
