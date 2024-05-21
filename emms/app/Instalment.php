<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instalment extends Model
{
    public function loanID(){
        return $this->belongsTo('App\Loan','loan_id');
    }

    public function userName(){
        return $this->belongsTo('App\User','user_id');
    }
    public function memberName(){
        return $this->belongsTo('App\Member','member_id');
    }
}
