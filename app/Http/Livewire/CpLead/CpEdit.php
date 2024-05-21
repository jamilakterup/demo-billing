<?php

namespace App\Http\Livewire\CpLead;

use App\Models\CpLead;
use App\Models\LeadStatus;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class CpEdit extends Component
{
    public $state = [];
    public $cpLeadInfo;


    public function mount(CpLead $cpCollection)
    {
        $this->cpLeadInfo = $cpCollection;
    }

    public function cpUpdate()
    {
        $validatedData = Validator::make($this->state, [
            'status' => 'required',
            'consultant' => 'required',
            'date' => 'required',
            'comment' => 'required',
        ])->validate();

        $cpStatusInfo = new LeadStatus;

        $cpStatusInfo->cp_lead_id = $this->cpLeadInfo->id;
        $latestSesrviceStatus = LeadStatus::where('cp_lead_id', $this->cpLeadInfo->id)->latest()->first();

        if ($latestSesrviceStatus) {
            $cpStatusInfo->followup = $latestSesrviceStatus->followup + 1;
        } else {
            $cpStatusInfo->followup = 1;
        }


        $cpStatusInfo->consultant = $validatedData['consultant'];
        $cpStatusInfo->comment = $validatedData['comment'];
        $cpStatusInfo->status = $validatedData['status'];
        $cpStatusInfo->date = $validatedData['date'];
        $cpStatusInfo->save();

        $this->emitTo('cp-lead.cp-table', 'refreshComponent');
        $this->dispatchBrowserEvent('message', [
            'type' => 'success',
            'title' => 'lead status has been updated successfully'
        ]);
    }


    public function render()
    {
        return view('livewire.cp-lead.cp-edit');
    }
}
