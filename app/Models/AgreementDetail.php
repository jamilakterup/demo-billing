<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementDetail extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault([
            'name' => 'not define'
        ]);
    }
}
