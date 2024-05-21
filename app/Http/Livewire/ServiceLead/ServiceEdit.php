<?php

namespace App\Http\Livewire\ServiceLead;

use App\Models\LeadStatus;
use App\Models\ServiceLead;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class ServiceEdit extends Component
{
    public $state = [];
    public $serviceLeadInfo;


    public function mount(ServiceLead $serviceCollection)
    {
        $this->serviceLeadInfo = $serviceCollection;
    }

    public function Leadupdate()
    {
        $validatedData = Validator::make($this->state, [
            'status' => 'required',
            'consultant' => 'required',
            'date' => 'required',
            'comment' => 'required',
        ])->validate();

        $ServiceStatusInfo = new LeadStatus;

        $ServiceStatusInfo->service_lead_id = $this->serviceLeadInfo->id;
        $latestSesrviceStatus = LeadStatus::where('service_lead_id', $this->serviceLeadInfo->id)->latest()->first();

        if ($latestSesrviceStatus) {
            $ServiceStatusInfo->followup = $latestSesrviceStatus->followup + 1;
        } else {
            $ServiceStatusInfo->followup = 1;
        }


        $ServiceStatusInfo->consultant = $validatedData['consultant'];
        $ServiceStatusInfo->comment = $validatedData['comment'];
        $ServiceStatusInfo->status = $validatedData['status'];
        $ServiceStatusInfo->date = $validatedData['date'];
        $ServiceStatusInfo->save();

        $this->emitTo('service-lead.service-table', 'refreshComponent');
        $this->dispatchBrowserEvent('message', [
            'type' => 'success',
            'title' => 'lead status has been updated successfully'
        ]);
    }

    public function render()
    {
        return view('livewire.service-lead.service-edit');
    }
}
