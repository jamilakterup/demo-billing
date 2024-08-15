<?php

namespace App\Http\Livewire\LeadCollection;

use App\Models\LeadCollection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class CollectionUserEdit extends Component
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

    public function LeadUserUpdate()
    {
        $validatedData = Validator::make($this->state, [
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'required',
            'source' => 'required',
        ])->validate();

        $leadInfo =  LeadCollection::findOrFail($this->leadCollection->id);

        $leadInfo->name = $validatedData['name'];
        $leadInfo->email = $validatedData['email'] ?? null;
        $leadInfo->phone = $validatedData['phone'];
        $leadInfo->source = $validatedData['source'];
        $leadInfo->source = $validatedData['source'];
        $leadInfo->type = 'new';
        $leadInfo->update();

        $this->emitTo('lead-collection.collection-table', 'refreshComponent');
        $this->emitTo('cp-lead.cp-table', 'refreshComponent');
        $this->emitTo('service-lead.service-table', 'refreshComponent');
        $this->dispatchBrowserEvent('message', [
            'type' => 'success',
            'title' => 'lead has been created successfully'
        ]);
    }

    public function render()
    {
        return view('livewire.lead-collection.collection-user-edit');
    }
}
