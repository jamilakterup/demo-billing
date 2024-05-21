<?php

namespace App\Http\Controllers;

use App\Instalment;
use App\Member;
use App\Loan;
use App\Saving;
use App\User;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    ///instalment create
    public function daily_report_create(){
        $data['loan_array']=Loan::loan_array();
        $data['user_array']=User::options();
        $data['member_array']=Member::member_array();

        return view('backend.report.daily.create',$data);
    }

    ///instalment search
    public function daily_report_search(Request $request){
        $user_id=$request->user_id;
        $member_id=$request->member_id;
        $loan_id=$request->loan_id;
        $from_date=$request->from_date;
        $to_date=$request->to_date;


        ///for download
        $data['user_id']=$user_id;
        $data['member_id']=$member_id;

        if(isset($member_id)){

            $data['member_name']=Member::find($member_id)->name;
        }

        $data['loan_id']=$loan_id;
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;


        if($user_id=='' ||$user_id==0) {
            $user_id = '%%';
        }

        if($member_id=='' || $member_id==0) {
            $member_id = '%%';
        }
        if($loan_id=='' ||$loan_id==0) {
            $loan_id = '%%';
        }



        $query_instalment=Instalment::join('loans','instalments.loan_id','=','loans.id')
            ->where('loans.member_id','like',$member_id)->where('instalments.loan_id','like',$loan_id);

        if($from_date!='' &&  $to_date!=''){
            $query_instalment=$query_instalment->whereBetween('instalments.date',[$from_date,$to_date]);
        }

        $query_instalment=$query_instalment->get();


        $data['daily_reports']=$query_instalment;

        return view('backend.report.daily.search',$data);

    }



    ///loan report create
    public function loan_report_create(){
        $data['loan_array']=Loan::loan_array();
        $data['user_array']=User::options();
        $data['member_array']=Member::member_array();

        return view('backend.report.loan.create',$data);
    }

    ///loan report search

    public function loan_report_search(Request $request){
        $user_id=$request->user_id;
        $member_id=$request->member_id;
        $loan_id=$request->loan_id;
        $from_date=$request->from_date;
        $to_date=$request->to_date;


        ///for download
        $data['user_id']=$user_id;
        $data['member_id']=$member_id;

        if(isset($member_id)){

            $data['member_name']=Member::find($member_id)->name;
        }

        $data['loan_id']=$loan_id;
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;


        if($user_id=='' ||$user_id==0) {
            $user_id = '%%';
        }

        if($member_id=='' || $member_id==0) {
            $member_id = '%%';
        }
        if($loan_id=='' ||$loan_id==0) {
            $loan_id = '%%';
        }


        $query_loan=Loan::where('member_id','like',$member_id)->where('id','like',$loan_id);

        if($from_date!='' &&  $to_date!=''){
            $query_loan=$query_loan->whereBetween('instalment_start_date',[$from_date,$to_date]);
        }



//        if($member_id!='' || $member_id!=0){
//            $query_loan=Loan::where('member_id',$member_id);
//
//        }
//
//        if($loan_id!='' || $loan_id!=0){
//
//
//            $query_loan=Loan::where('loans.id','like',$loan_id);
//        }
//


//        if($from_date!='' &&  $to_date!=''){
//            $query_loan=Loan::where('loans.instalment_start_date','>=',$from_date)
//                ->where('loans.instalment_start_date','<=',$to_date);
//        }
        $query_loan=$query_loan->get();
//        $query_loan=$query_loan->selectRaw(
//            '
//            loans.id,
//            loans.member_id,
//            loans.principal,
//            loans.interest,
//            loans.instalment_start_date
//
//            '
//        )->get();

        $data['loan_reports']=$query_loan;

        return view('backend.report.loan.search',$data);



//        if($from_date!='' &&  $to_date!='')
//        {
//            $query_report_loan=$query_customer_postings->where('customer_postings.date','>=',$form_date)
//                ->where('customer_postings.date','<=',$to_date);
//        }




    }


    ///loan report download
    public function loan_report_download(Request $request){
        $user_id=$request->user_id;
        $member_id=$request->member_id;
        $loan_id=$request->loan_id;
        $from_date=$request->from_date;
        $to_date=$request->to_date;

        if(isset($member_id)){

            $data['member_name']=Member::find($member_id)->name;
        }

        $data['from_date']=$from_date;
        $data['to_date']=$to_date;

        if($user_id=='' ||$user_id==0) {
            $user_id = '%%';
        }

        if($member_id=='' || $member_id==0) {
            $member_id = '%%';
        }
        if($loan_id=='' ||$loan_id==0) {
            $loan_id = '%%';
        }


        $query_loan=Loan::where('member_id','like',$member_id)->where('id','like',$loan_id);

        if($from_date!='' &&  $to_date!=''){
            $query_loan=$query_loan->whereBetween('instalment_start_date',[$from_date,$to_date]);
        }
        $query_loan=$query_loan->get();
//        $query_loan=$query_loan->selectRaw(
//            '
//            loans.id,
//            loans.member_id,
//            loans.principal,
//            loans.interest,
//            loans.instalment_start_date
//
//            '
//        )->get();

        $data['loan_reports']=$query_loan;


        $html = view('backend.report.loan.download',$data);
        $mpdf = new Mpdf();
        $mpdf->SetTitle('loan report');
        $mpdf->SetHTMLFooter('<table width="100%"><tr><td width="95%" align="center" style="color:blue">Powered by: raj IT Solutions Ltd. (An ISO 9001:2015 Certified Company).</td><td width="5%">{PAGENO}/{nbpg}</td></tr></table>');
        $mpdf->AddPage('A4-P');
        $mpdf->writeHTML($html);
        $mpdf->Output('loan_report.pdf','D');

    }



    ///instalment savings
    public function savings_report_create(){
        $data['loan_array']=Loan::loan_array();
        $data['user_array']=User::options();
        $data['member_array']=Member::member_array();

        return view('backend.report.savings.create',$data);
    }

    ///savings search
    public function savings_report_search(Request $request){
        $user_id=$request->user_id;
        $member_id=$request->member_id;
        $type=$request->type;
        $from_date=$request->from_date;
        $to_date=$request->to_date;


        if($type==='' || $type===0 || $type==='all') {
            $type = '%%';
        }


        ///for download
        $data['user_id']=$user_id;
        $data['member_id']=$member_id;
        $data['type']=$type;

        if(isset($member_id)){

            $data['member_name']=Member::find($member_id)->name;
        }

        $data['from_date']=$from_date;
        $data['to_date']=$to_date;






        if($user_id=='' ||$user_id==0) {
            $user_id = '%%';
        }

        if($member_id=='' || $member_id==0) {
            $member_id = '%%';
        }



        $query_savings=Saving::where('member_id','like',$member_id)->where('type','like',$type);

        if($from_date!='' &&  $to_date!=''){
            $query_savings=$query_savings->whereDate('date','>=',$from_date)->whereDate('date','<=',$to_date);
        }

        $query_savings=$query_savings
            ->selectRaw('       
        savings.member_id,
        savings.type,
        savings.date,
        savings.amount
        ')->get();


        $data['savings_reports']=$query_savings;

        return view('backend.report.savings.search',$data);

    }



    ///loan filter
    public function loan_filter($id)
    {


        $loans=Loan::where('member_id',$id)->get();

        $response = array(
            'status' => 1,
            'loans' => $loans,
        );
        return response()->json($response, 200);
    }


}
