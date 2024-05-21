<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Savings_withdrawal extends Model
{
    public function memberName(){
        return $this->belongsTo('App\Member','member_id');
    }
    public function userName(){
        return $this->belongsTo('App\User','user_id');
    }
}
