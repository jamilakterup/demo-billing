<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable=['name'];
    protected $hidden=[];

    static function all_unit(){
        $units=Self::all();

        $unit_array=[];

        foreach($units as $unit){
            $unit_array[$unit->id]=$unit->name;
        }

        return $unit_array;
    }
}
