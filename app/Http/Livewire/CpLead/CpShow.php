<?php

namespace App\Http\Livewire\CpLead;

use App\Models\CpLead;
use App\Models\CpStatus;
use Livewire\Component;

class CpShow extends Component
{
    public $cpStatusInfos;

    public function mount(CpLead $cpCollection)
    {
        $this->cpStatusInfos = CpStatus::where('cp_lead_id', $cpCollection->id)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.cp-lead.cp-show');
    }
}
