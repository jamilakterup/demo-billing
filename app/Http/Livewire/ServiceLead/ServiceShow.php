<?php

namespace App\Http\Livewire\ServiceLead;

use App\Models\ServiceLead;
use App\Models\ServiceStatus;
use Livewire\Component;

class ServiceShow extends Component
{
    public $serviceStatusInfos;

    public function mount(ServiceLead $serviceCollection)
    {
        $this->serviceStatusInfos = ServiceStatus::where('service_lead_id', $serviceCollection->id)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.service-lead.service-show');
    }
}
