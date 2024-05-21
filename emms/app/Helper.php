<?php

namespace App;


use DB;
use Illuminate\Database\Eloquent\Model;
class Helper extends Model
{
  
    public static function order_status(){
        $data = [
            ''=>'--Select--',
            '0'=>'Draft',
            '1'=>'Orderd (Receive Pending)',
            '2'=>'Order Received Completed',
           
        ];

        return $data;
    }

    public static function draft_status(){
        $data = [
            ''=>'--Select--',
            '0'=>'Draft',
            '1'=>'Kacha(Receive Pending)',
            '2'=>'Kacha Received Completed',
        ];
        return $data;
    }

    public static function instalment_type(){
        $data = [
            '1'=>'1 day',
            '2'=>'7 day',
            '3'=>'15 day',
            '4'=>'1 month',
            '5'=>'4 month',
            '6'=>'6 month',
            '7'=>'1 year',
        ];

        return $data;
    }

        ///not use
    public static function loan_status($loan_id){

        $total_payment=Instalment::where('loan_id',$loan_id)->sum('payment');
        $loan=Loan::find($loan_id);
        $payable_amount=$loan->principal+$loan->interest;
        if($total_payment==$payable_amount){
            return 1;
        }
    }

    public static function instalment_count($loan_id){

        $ins_count=Loan_payment_calender::where('loan_id',$loan_id)->where('status',1)->count();

        return $ins_count;
    }

    public static function total_payment($loan_id){

        $total_payment=Instalment::where('loan_id',$loan_id)->sum('payment');

        return $total_payment;
    }

    public static function withdrawal_amount_check($member_id){

        $savings=Saving::where('member_id',$member_id)->where('type','savings')->sum('amount');
        $withdrawal_amount=Saving::where('member_id',$member_id)->where('type','withdrawal')->sum('amount');
        $available=$savings-$withdrawal_amount;

        return $available;
    }
















































}
