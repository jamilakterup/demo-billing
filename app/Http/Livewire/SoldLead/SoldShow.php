<?php

namespace App\Http\Livewire\SoldLead;

use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SoldShow extends Component
{
    public $routeName;

    public function mount()
    {
        $this->routeName = Route::currentRouteName();
    }

    public function render()
    {
        return view('livewire.sold-lead.sold-show');
    }
}
