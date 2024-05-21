<?php

namespace App\Http\Livewire\Payment;

use Livewire\Component;
use App\Models\Payment;

class PaymentShow extends Component
{
    public function render()
    {
        return view('livewire.payment.payment-show');
    }
}
