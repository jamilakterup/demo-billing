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

class Profile_instalmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       if(Auth::user()->id==$request->user_id){

        $data['instalments']=Instalment::where('user_id',$request->user_id)->orderBy('id','desc')->get();
            return view('backend.profile.profile_instalment.index',$data);
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
       
        $data['loan_array']=$this->loan_array($request->user_id);
        $data['user']=User::find($request->user_id);

        return view('backend.profile.profile_instalment.create',$data);
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
            return redirect()->back();
        }

        ///end check over payment


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

        ///new instalment

        $instalment=new Instalment;
        $instalment->loan_id=$request->loan_id;
        $instalment->user_id=Auth::user()->id;
        $instalment->saving_id=$savings_entry->id;
        $instalment->payment=$request->payment;
        $instalment->payment_date=$request->payment_date;
        $instalment->date=date('Y-m-d');
        $instalment->save();
        
        

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
                $calender->instalment_id=$instalment->id;
                $calender->status=1;
                $calender->update();
            }
        }

        ///after first instalment

        else{
            for($i=1; $i<=$status_update_count; $i++){
                $calender=Loan_payment_calender::where('loan_id',$instalment->loan_id)->where('status',0)->first();
                $calender->instalment_id=$instalment->id;
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
        return redirect()->route('profileInstalment.index',['user_id'=>$loan->user_id]);
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
        $saving_id=Instalment::find($id)->saving_id;
        
        $data['saving']=Saving::find($saving_id);
        $data['instalment']=Instalment::find($id);
        
        return view('backend.profile.profile_instalment.edit',$data);
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
            'loan_id'=>'required',
            'payment'=>'required|integer',
            'savings'=>'required|integer',
            'payment_date'=>'required|date',
        );
        $this->validate($request,$rules);


        $count_paid_calender=Loan_payment_calender::where('instalment_id',$id)->count();
        if($count_paid_calender>0){
            $paid_calenders=Loan_payment_calender::where('instalment_id',$id)->get();
            foreach($paid_calenders as $paid_calender){
                $update_paid_calender=Loan_payment_calender::find($paid_calender->id);
                $update_paid_calender->status=0;
                $update_paid_calender->update();
            }
        }

        

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
            return redirect()->back();
        }

        ///end check over payment


        ///savings
        if($request->savings!=''){
            $savings_entry=Saving::find($request->saving_id);
            $savings_entry->amount=$request->savings;
            $savings_entry->user_id=Auth::user()->id;
            $savings_entry->update();
            }

        ///new instalment

        $instalment=Instalment::find($id);
        $instalment->loan_id=$request->loan_id;
        $instalment->user_id=Auth::user()->id;
        $instalment->saving_id=$savings_entry->id;
        $instalment->payment=$request->payment;
        $instalment->payment_date=$request->payment_date;
        $instalment->date=date('Y-m-d');
        $instalment->update();
        
        

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
                $calender->instalment_id=$instalment->id;
                $calender->status=1;
                $calender->update();
            }
        }

        ///after first instalment

        else{
            for($i=1; $i<=$status_update_count; $i++){
                $calender=Loan_payment_calender::where('loan_id',$instalment->loan_id)->where('status',0)->first();
                $calender->instalment_id=$instalment->id;
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
        return redirect()->route('profileInstalment.index',['user_id'=>$loan->user_id]);
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

    public function profile_instalment_create2($loan_id){
        $data['loan']=Loan::find($loan_id);
        return view('backend.profile.profile_instalment.create2',$data);
    }


    private function loan_array($user_id)
    {
        $loans = Loan::where('user_id',$user_id)->get();
        $loan_array = array('' => '--Please Select--');

        foreach($loans as $loan)
        {
            $loan_array[$loan->id] ='LID- '.$loan->id.' ('.$loan->principal.')'.$loan->memberName->name;
        }
        return $loan_array;
    }

   


}
