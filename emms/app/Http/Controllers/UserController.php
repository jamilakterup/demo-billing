<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Loan;
use App\Member;
use App\Saving;
use App\Instalment;
use App\Loan_payment_calender;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['users']=User::orderBy('id', 'DESC')->get();
        return view('backend.users.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.users.create');
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
            'name'=>'required',
            'email'=>'required|unique:users',
            'mobile'=>'required|unique:users|min:11',
            'password'=>'required|min:8',
            'con_pass'=>'required|required_with:password|same:password|min:8',
            'photo'=>'required|image|mimes:jpeg,png,jpg|max:1024|dimensions:width=300,height=300',

        );

        $this->validate($request,$rules);

        $user=new User;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->mobile=$request->mobile;
        $user->password=Hash::make($request->password);

        if($request->hasFile('photo')){
            $image=$request->file('photo');
            $extension=$image->getClientOriginalExtension();
            $filename="user_image_".uniqid().".".$extension;
            $destination=public_path().'/img/users';
            $image->move($destination,$filename);
            $user->photo=$filename;
        }



        $user->save();
        \Alert::success('Success', 'User has been saved successfully');

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


        $loan=Loan::where('user_id',$id)->pluck('id');
        $member=Member::where('user_id',$id)->pluck('id');


        $data['user']=User::find($id);
        $data['runningLoan']=Loan::where('paid',0)->where('user_id',$id)->count();
        $data['completeLoan']=Loan::where('paid',1)->where('user_id',$id)->count();
        
        $data['totalLoan']=Loan::where('user_id',$id)->count();
        $data['totalPrincipal']=Loan::where('user_id',$id)->sum('principal');
        $data['totalInterest']=Loan::where('user_id',$id)->sum('interest');
        $data['totalReceived']=Instalment::whereIn('loan_id',$loan)->sum('payment');
        
        $data['totalSavings']=Saving::whereIn('member_id',$member)->where('type','savings')->sum('amount');
        $data['totalWithdrawal']=Saving::whereIn('member_id',$member)->where('type','withdrawal')->sum('amount');
        
        $data['paymentDates']=Loan_payment_calender::where('payment_date',date('Y-m-d'))->where('status',0)->whereIn('loan_id',$loan)->get();
        $data['totalMember']=Member::where('user_id',$id)->count();

        ///current date by user
        $data['receivable_current_date']=Loan_payment_calender::where('payment_date',date('Y-m-d'))->whereIn('loan_id',$loan)->sum('amount');
        $data['received_current_date']=Instalment::whereIn('loan_id',$loan)->where('date',date('Y-m-d'))->sum('payment');
        $data['savings_current_date']=Saving::whereIn('member_id',$member)->where('type','savings')->whereDate('created_at',date('Y-m-d'))->sum('amount');
        $data['withdrawal_current_date']=Saving::whereIn('member_id',$member)->where('type','withdrawal')->whereDate('created_at',date('Y-m-d'))->sum('amount');
        
        ///running loan by user
        $data['loans']=Loan::where('user_id',$id)->where('paid',0)->orderBy('id', 'DESC')->get();
        ///all members
        $data['members']=Member::where('user_id',$id)->orderBy('id', 'DESC')->get();
        return view('backend.users.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['user']=User::find($id);

        return view('backend.users.edit',$data);
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
            'name'=>'required',
            'mobile'=>'required|min:11',
            'email'=>'required',
        );

        $this->validate($request,$rules);

        $user=User::find($id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->mobile=$request->mobile;
        if(isset($request->password)){
            $user->password=Hash::make($request->password);
        }

        if($request->hasFile('photo')){
            $image=$request->file('photo');
            $extension=$image->getClientOriginalExtension();
            $filename="user_image_".uniqid().".".$extension;
            $destination=public_path().'/img/users';
            $image->move($destination,$filename);
            $user->photo=$filename;
        }

        $user->update();
        \Alert::success('Success', 'User has been updated successfully');

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
        //
    }

    public function receivable_amount_user(){
        $data['receivable_amount_users']=Loan_payment_calender::join('loans','loans.id','=','loan_payment_calenders.loan_id')
        ->select(
            'loans.user_id',
            'loan_payment_calenders.loan_id',
            'loan_payment_calenders.amount',
            'loan_payment_calenders.payment_date',
            'loan_payment_calenders.status'
            )
        ->where('payment_date',date('Y-m-d'))
        ->get();
        $returnHTML = view('backend.pop.receivable_amount_user',$data);
        return $returnHTML;
        // return response()->json([
        //     'success' => true,
        //     'html'=>$returnHTML
        // ]);

    }


    public function received_amount_user(){
        $data['received_amount_users']=Instalment::join('loans','loans.id','=','instalments.loan_id')
        ->select(
            'loans.user_id',
            'instalments.loan_id',
            'instalments.payment',
            'instalments.payment_date'
            )
        ->where('instalments.date',date('Y-m-d'))
        ->get();
        $returnHTML = view('backend.pop.received_amount_user',$data);
        return $returnHTML;

    }

    public function total_savings_amount_user(){
        $data['savings_amount_users']=Saving::join('members','members.id','=','savings.member_id')
        ->select(
            'members.user_id',
            'savings.amount',
            'savings.type',
            'savings.date'
            )
        ->where('type','savings')
        ->whereDate('savings.created_at',date('Y-m-d'))
        ->get();
        $returnHTML = view('backend.pop.total_savings_amount_user',$data);
        return $returnHTML;
    }
    public function total_withdrawal_amount_user(){
        $data['withdrawal_amount_users']=Saving::join('members','members.id','=','savings.member_id')
        ->select(
            'members.user_id',
            'savings.amount',
            'savings.type',
            'savings.date'
            )
        ->where('type','withdrawal')
        ->whereDate('savings.created_at',date('Y-m-d'))
        ->get();
        $returnHTML = view('backend.pop.total_withdrawal_amount_user',$data);
        return $returnHTML;
    }
    
}
