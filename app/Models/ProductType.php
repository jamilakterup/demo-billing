<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;
    protected $fillable=['name'];
    protected $hidden=[];


    static function all_product_type(){
        $types=Self::all();

        $product_type_array=[];

        foreach($types as $type){
            $product_type_array[$type->id]=$type->name;
        }

        return $product_type_array;
    }
}
