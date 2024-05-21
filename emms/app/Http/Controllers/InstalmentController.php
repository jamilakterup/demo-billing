<?php

namespace App\Http\Controllers;

use App\Instalment;
use App\Loan;
use App\Loan_payment_calender;
use App\Member;
use App\User;
use App\Saving;
use Auth;
use Illuminate\Http\Request;

class InstalmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['instalments']=Instalment::orderBy('id', 'DESC')->get();
        return view('backend.instalment.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['loan_array']=Loan::loan_array();
        $data['user_array']=User::options();

        return view('backend.instalment.create',$data);
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
            'loan_id'=>'required',
            'payment'=>'required|integer',
            'savings'=>'required|integer',
            'payment_date'=>'required|date',
        );

        $this->validate($request,$rules);

        /// check over payment

        $total_instalment_payment=Instalment::where('loan_id',$request->loan_id)->sum('payment');
        $total_current_payment=$total_instalment_payment+$request->payment;
        ///find loan
        $loan=Loan::find($request->loan_id);

        ///total payment amount
        $total_payment_amount=$loan->principal+$loan->interest;


        if($total_current_payment>$total_payment_amount){

            $payable_amount=$total_payment_amount-$total_instalment_payment;

            \Alert::warning('Payment invalid', 'Please input valid payment amount. valid payment is '. $payable_amount .'');
            return redirect()->route('instalment.create');
        }

        ///end check over payment









        ///new instalment

        $instalment=new Instalment;
        $instalment->loan_id=$request->loan_id;
        $instalment->user_id=Auth::user()->id;
        $instalment->payment=$request->payment;
        $instalment->payment_date=$request->payment_date;
        $instalment->date=date('Y-m-d');
        $instalment->save();
        
        ///savings
        if($request->savings!=''){
        $savings_entry=new Saving;
        $savings_entry->member_id=$request->member_id;
        $savings_entry->type='savings';
        $savings_entry->amount=$request->savings;
        $savings_entry->date=date('Y:m:d H:i:s');
        $savings_entry->user_id=Auth::user()->id;
        $savings_entry->save();
        
    }




        $paid_count=Loan_payment_calender::where('loan_id',$request->loan_id)->where('status',1)->count();


        $per_instalment_payment=$total_payment_amount/$loan->number_of_instalment;

        $total_paid=$per_instalment_payment*$paid_count;

        $total_instalment_payment_2=Instalment::where('loan_id',$request->loan_id)->sum('payment');

        $calender_status_count=($total_instalment_payment_2-$total_paid)/$per_instalment_payment;
        $status_update_count=floor($calender_status_count);








        ///first instalment

        $calender_status_count_first=$request->payment/$per_instalment_payment;
        $status_update_count_first=floor($calender_status_count_first);



        if($paid_count==0 && $status_update_count_first>0){
            for($i=1; $i<=$status_update_count_first; $i++){
                $calender=Loan_payment_calender::where('loan_id',$request->loan_id)->where('status',0)->first();
                $calender->status=1;
                $calender->update();
            }
        }

        ///after first instalment

        else{
            for($i=1; $i<=$status_update_count; $i++){
                $calender=Loan_payment_calender::where('loan_id',$instalment->loan_id)->where('status',0)->first();
                $calender->status=1;
                $calender->update();
            }
        }

        if($total_payment_amount==$total_instalment_payment_2){

            $loan_paid=Loan::find($request->loan_id);
            $loan_paid->paid=1;
            $loan_paid->update();

        }

        \Alert::success('Success', 'Instalment has been saved successfully');
        return redirect()->route('instalment.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
        //
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

    public function member_filter($id)
    {


        $members=Member::where('user_id',$id)->get();

        $response = array(
            'status' => 1,
            'members' => $members,

        );
        return response()->json($response, 200);
    }



//    public function member_filter($id)
//    {
//
//
//        $members=Loan::where('user_id',$id)->get();
//
//        $response = array(
//            'status' => 1,
//            'members' => $members,
//
//        );
//        return response()->json($response, 200);
//    }


    public function loan_select($id)
    {


        $loan=Loan::find($id);

        $payment=($loan->principal+$loan->interest)/$loan->number_of_instalment;

        $response = array(
            'status' => 1,
            'payment_interval' => $loan->inst_type->name,
            'payment' =>$payment,
            'member' =>$loan->memberName->name,
            'member_id' =>$loan->memberName->id,
        );
        return response()->json($response, 200);
    }




    public function instalment_create($loan_id)
    {
        $data['loan']=Loan::find($loan_id);
        return view('backend.instalment.instalment_create',$data);

    }


}
