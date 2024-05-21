<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    public function memberName(){
        return $this->belongsTo('App\Member','member_id');
    }
    public function inst_type(){
        return $this->belongsTo('App\Instalment_type','instalment_type');
    }

    public function userName(){
        return $this->belongsTo('App\User','user_id');
    }


    public static function loan_array()
    {
        $loans = Self::where('paid',0)->get();
        $options = array('' => '--Please Select--');
        foreach($loans as $loan)
        {
            $options[$loan->id] = $loan->id.'-'.$loan->memberName->name.' ('.$loan->principal.')';
        }
        return $options;
    }
}
