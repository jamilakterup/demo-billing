<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'description', 'unit_id', 'product_type_id', 'price'];
    protected $hidden = [];

    static function all_product()
    {
        $products = Self::all();

        $product_array = [];

        foreach ($products as $product) {
            $product_array[$product->id] = $product->name;
        }

        return $product_array;
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class)->withDefault([
            'name' => 'not define'
        ]);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class)->withDefault([
            'name' => 'None'
        ]);
    }
}
