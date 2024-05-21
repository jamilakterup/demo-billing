<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;
    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault([
            'name' => 'not define'
        ]);
    }
    public function quotation_type()
    {
        return $this->belongsTo(QuotationType::class)->withDefault([
            'quotation_type_name' => 'None'
        ]);
    }

    public function estimate_details()
    {
        return $this->hasMany(EstimateDetail::class);
    }

    
    public function status()
    {
        return $this->belongsTo(Status::class)->withDefault([
            'name' => 'not found'
        ]);
    }
}
