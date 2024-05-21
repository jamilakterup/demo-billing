<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Member;
use App\Helper;
use App\Loan;
use App\Loan_payment_calender;
use App\User;
use App\Instalment;
use App\Saving;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       if(Auth::user()->id==$request->user_id){
        $loan=Loan::where('user_id',$request->user_id)->pluck('id');
        $member=Member::where('user_id',$request->user_id)->pluck('id');
        $data['user']=User::find($request->user_id);

        $data['runningLoan']=Loan::where('paid',0)->where('user_id',$request->user_id)->count();
        $data['completeLoan']=Loan::where('paid',1)->where('user_id',$request->user_id)->count();
        
        $data['totalLoan']=Loan::where('user_id',$request->user_id)->count();
        $data['totalPrincipal']=Loan::where('user_id',$request->user_id)->sum('principal');
        $data['totalInterest']=Loan::where('user_id',$request->user_id)->sum('interest');
        $data['totalReceived']=Instalment::whereIn('loan_id',$loan)->sum('payment');
        $data['totalSavings']=Saving::whereIn('member_id',$member)->where('type','savings')->sum('amount');
        $data['totalWithdrawal']=Saving::whereIn('member_id',$member)->where('type','withdrawal')->sum('amount');
        $data['paymentDates']=Loan_payment_calender::where('payment_date',date('Y-m-d'))->where('status',0)->whereIn('loan_id',$loan)->get();
        $data['totalMember']=Member::where('user_id',$request->user_id)->count();

        ///current date info by user
        $data['receivable_current_date']=Loan_payment_calender::where('payment_date',date('Y-m-d'))->whereIn('loan_id',$loan)->sum('amount');
        $data['received_current_date']=Instalment::whereIn('loan_id',$loan)->where('date',date('Y-m-d'))->sum('payment');
        $data['savings_current_date']=Saving::whereIn('member_id',$member)->where('type','savings')->whereDate('created_at',date('Y-m-d'))->sum('amount');
        $data['withdrawal_current_date']=Saving::whereIn('member_id',$member)->where('type','withdrawal')->whereDate('created_at',date('Y-m-d'))->sum('amount');
       
       
        ///current loans
        $data['loans']=Loan::where('user_id',$request->user_id)->where('paid',0)->orderBy('id', 'DESC')->get();


        
        $data['paymentDates']=Loan_payment_calender::where('payment_date',date('Y-m-d'))->where('status',0)->whereIn('loan_id',$loan)->get();
            return view('backend.profile.index',$data);
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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


    public function profile_member_index(Request $request)
    {
       if(Auth::user()->id==$request->user_id){

        $data['members']=Member::where('user_id',$request->user_id)->get();
            return view('backend.profile.member_index',$data);
       }
       else{
           return redirect('/login');
       }

    }

    public function profile_member_create(Request $request)
    {
       if(Auth::user()->id==$request->user_id){

        $data['user']=User::find($request->user_id);
        
            return view('backend.profile.member_create',$data);
       }
       else{
           return redirect('/login');
       }

    }

    public function profile_member_store(Request $request)
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

       return redirect()->route('profile.member.index',['user_id'=>Auth::user()->id]);





    }

    public function profile_member_show($member_id){
        $data['member']=Member::find($member_id);
        $data['loans']=Loan::where('member_id',$member_id)->get();
        $data['savings']=Saving::where('member_id',$member_id)->get();
        $data['total_paid']=Instalment::where('loan_id',$member_id)->sum('payment');
        $data['paid_instalment']=Helper::instalment_count($member_id);
        return view('backend.profile.member_show',$data);
    }

    public function profile_member_edit($member_id){
        $data['member']=Member::find($member_id);
        $data['agent_array']=User::options();
        return view('backend.profile.member_edit',$data);
    }
    

    public function profile_member_update(Request $request, $id)
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




   


    ///ajax
    public function receivable_amount_member(Request $request){
       
        $user_id=$request->user_id;

        $data['receivable_amount_users']=Loan_payment_calender::join('loans','loans.id','=','loan_payment_calenders.loan_id')
        ->select(
            'loans.user_id',
            'loans.member_id',
            'loan_payment_calenders.loan_id',
            'loan_payment_calenders.amount',
            'loan_payment_calenders.payment_date',
            'loan_payment_calenders.status'
            )
        
        ->where('loans.user_id',$user_id)
        ->where('payment_date',date('Y-m-d'))
        ->get();
        $returnHTML = view('backend.pop.receivable_amount_member',$data);
        return $returnHTML;

    }


    public function received_amount_member(Request $request){
        $user_id=$request->user_id;

        
        $data['received_amount_users']=Instalment::join('loans','loans.id','=','instalments.loan_id')
        ->select(
            'loans.user_id',
            'loans.member_id',
            'instalments.loan_id',
            'instalments.payment',
            'instalments.payment_date'
            )
        
        ->where('loans.user_id',$user_id)
        ->where('instalments.date',date('Y-m-d'))
        ->get();
        $returnHTML = view('backend.pop.received_amount_member',$data);
        return $returnHTML;

    }
    

    public function savings_amount_member(Request $request){
        $user_id=$request->user_id;

        $data['savings_amount_users']=Saving::join('members','members.id','=','savings.member_id')
        ->select(
            'members.user_id',
            'members.name',
            'members.photo',
            'savings.amount',
            'savings.type',
            'savings.date'
            )
        ->where('members.user_id',$user_id)
        ->where('type','savings')
        ->whereDate('savings.created_at',date('Y-m-d'))
        ->get();
        $returnHTML = view('backend.pop.savings_amount_member',$data);
        return $returnHTML;
    }

    
    public function withdrawal_amount_member(Request $request){
        $user_id=$request->user_id;

        $data['withdrawal_amount_users']=Saving::join('members','members.id','=','savings.member_id')
        ->select(
            'members.user_id',
            'members.name',
            'members.photo',
            'savings.amount',
            'savings.type',
            'savings.date'
            )
        ->where('members.user_id',$user_id)
        ->where('type','withdrawal')
        ->whereDate('savings.created_at',date('Y-m-d'))
        ->get();
        $returnHTML = view('backend.pop.withdrawal_amount_member',$data);
        return $returnHTML;
    }


    

    
    


    




}
