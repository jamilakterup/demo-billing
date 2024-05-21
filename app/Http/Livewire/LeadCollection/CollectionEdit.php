<?php

namespace App\Http\Livewire\LeadCollection;

use App\Models\LeadCollection;
use App\Models\LeadStatus;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class CollectionEdit extends Component
{
    public $state = [];
    public $collectionInfo;


    public function mount(LeadCollection $leadCollection)
    {
        $this->collectionInfo = $leadCollection;
    }

    public function Leadupdate()
    {
        $validatedData = Validator::make($this->state, [
            'status' => 'required',
            'consultant' => 'required',
            'date' => 'required',
            'comment' => 'required',
        ])->validate();

        $leadStatusInfo = new LeadStatus;

        $leadStatusInfo->lead_collection_id = $this->collectionInfo->id;

        $latestLeadStatus = LeadStatus::where('lead_collection_id', $this->collectionInfo->id)->latest()->first();

        if ($latestLeadStatus) {
            $leadStatusInfo->followup = $latestLeadStatus->followup + 1;
        } else {
            $leadStatusInfo->followup = 1;
        }


        $leadStatusInfo->consultant = $validatedData['consultant'];
        $leadStatusInfo->comment = $validatedData['comment'];
        $leadStatusInfo->status = $validatedData['status'];
        $leadStatusInfo->date = $validatedData['date'];
        $leadStatusInfo->save();

        $this->emitTo('lead-collection.collection-table', 'refreshComponent');
        $this->emitTo('cp-lead.cp-table', 'refreshComponent');
        $this->emitTo('service-lead.service-table', 'refreshComponent');
        $this->dispatchBrowserEvent('message', [
            'type' => 'success',
            'title' => 'lead status has been updated successfully'
        ]);
    }

    public function render()
    {
        return view('livewire.lead-collection.collection-edit');
    }
}
