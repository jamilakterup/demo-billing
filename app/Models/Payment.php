<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'payment_type_id', 'certificate_file', 'vat_file', 'tax_file', 'date', 'comment', 'amount'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class)->withDefault([
            'name' => 'not define'
        ]);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class)->withDefault([
            'name' => 'not define'
        ]);;
    }
}
