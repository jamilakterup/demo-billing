<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public function agentName(){
       return $this->belongsTo('App\User','user_id');
    }
    public function userName(){
        return $this->belongsTo('App\User','user_id');
    }

    public static function member_array()
    {
        $members = Self::all();
        $options = array('' => '--Please Select--');
        foreach($members as $member)
        {
            $options[$member->id] = $member->id.'-'.$member->name;
        }
        return $options;
    }
}

