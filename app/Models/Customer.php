<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    static function all_customer(){
        $customers=Self::all();

        $customer_array=[];

        foreach($customers as $customer){
            $customer_array[$customer->id]=$customer->name;
        }

        return $customer_array;
    }

    static function customer_due($customer_id){
        $customer_invoice_ids=Invoice::where('customer_id',$customer_id)->pluck('id')->toArray();
        $customerTotal=Invoice::where('customer_id',$customer_id)->sum('total');
        $customerpaymentTotal=Payment::whereIn('invoice_id',$customer_invoice_ids)->sum('amount');
        $customerDue=$customerTotal-$customerpaymentTotal;
        return $customerDue;
    }

    public function invoice(){
        return $this->hasMany(Invoice::class);
    }
}
