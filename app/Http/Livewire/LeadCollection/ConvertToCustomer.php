<?php

namespace App\Http\Livewire\LeadCollection;

use App\Models\LeadCollection;
use Livewire\Component;

class ConvertToCustomer extends Component
{
    public $leadStatusInfos;
    public $leadCollection;
    public $state = [];

    public function mount(LeadCollection $leadCollection)
    {
        $this->leadCollection = $leadCollection;
        $this->state = [
            'name' => $leadCollection->name,
            'phone' => $leadCollection->phone,
            'email' => $leadCollection->email,
            'source' => $leadCollection->source,
        ];
    }

    public function render()
    {
        return view('livewire.lead-collection.convert-to-customer');
    }
}
