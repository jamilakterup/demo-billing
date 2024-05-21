<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;
    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault([
            'name' => 'not define'
        ]);
    }
    public function agreement_type()
    {
        return $this->belongsTo(AgreementType::class)->withDefault([
            'agreement_type_name' => 'None'
        ]);
    }

    public function agreement_details()
    {
        return $this->hasMany(AgreementDetail::class);
    }


    public function status()
    {
        return $this->belongsTo(Status::class)->withDefault([
            'name' => 'not found'
        ]);
    }
}
