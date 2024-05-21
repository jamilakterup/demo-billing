<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instalment_type extends Model
{
    public static function instalment_type()
    {
        $instalment_types = Self::all();
        $options = array('' => '--Please Select--');
        foreach($instalment_types as $instalment_type)
        {
            $options[$instalment_type->id] = $instalment_type->name;
        }
        return $options;
    }
}
