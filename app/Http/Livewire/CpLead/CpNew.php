<?php

namespace App\Http\Livewire\CpLead;

use App\Models\LeadCollection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class CpNew extends Component
{
    public $state = [];

    public function LeadStore()
    {
        $validatedData = Validator::make($this->state, [
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'required',
            'source' => 'required',
        ])->validate();

        $leadInfo = new LeadCollection();

        $leadInfo->name = $validatedData['name'];
        $leadInfo->email = $validatedData['email'] ?? null;
        $leadInfo->phone = $validatedData['phone'];
        $leadInfo->source = $validatedData['source'];
        $leadInfo->type = 'cp';
        $leadInfo->save();

        $this->emitTo('cp-lead.cp-table', 'refreshComponent');
        $this->dispatchBrowserEvent('message', [
            'type' => 'success',
            'title' => 'lead has been created successfully'
        ]);
    }


    public function render()
    {
        return view('livewire.cp-lead.cp-new');
    }
}
