<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'number', 'invoice_type_id', 'ref_number', 'date', 'note', 'due_date', 'sub_total', 'discount', 'total'];
    protected $hidden = ['status', 'created_at', 'updated_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault([
            'name' => 'not define'
        ]);
    }
    public function invoice_type()
    {
        return $this->belongsTo(InvoiceType::class)->withDefault([
            'invoice_type_name' => 'None'
        ]);
    }

    public function invoice_details()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    static function get_due($invoice_id)
    {
        $invoiceTotal = Self::find($invoice_id)->total;
        $totalPayment = Payment::where('invoice_id', $invoice_id)->sum('amount');

        $due = $invoiceTotal - $totalPayment;
        // dd($due);
        if ($due >= 1) {
            return number_format((float)$due, 2, '.', '');
        } else {
            return 0.00;
        }
    }
}
