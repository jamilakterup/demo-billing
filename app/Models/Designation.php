<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;
    protected $hidden=[];
    protected $fillable=['name'];
    
    static function all_designation(){
        $designations=Self::all();

        $designation_array=[];

        foreach($designations as $designation){
            $designation_array[$designation->id]=$designation->name;
        }

        return $designation_array;
    }
}
