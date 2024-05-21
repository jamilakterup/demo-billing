<?php

namespace App\Http\Livewire\LeadCollection;

use App\Models\LeadCollection;
use App\Models\LeadStatus;
use App\Models\SoldLead;
use App\Models\SoldStatus;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CollectionShow extends Component
{
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'soldLead' => 'soldConfirmed',
        'inactiveLead' => 'inactiveConfirmed'
    ];

    public $leadStatusInfos;
    public $leadCollection;
    public $routeName;

    public function mount(LeadCollection $leadCollection)
    {
        $this->leadCollection = $leadCollection;
        $this->leadStatusInfos = LeadStatus::where('lead_collection_id', $leadCollection->id)->get()->toArray();
    }


    // convert to sold
    public function convertToSold($leadCollection)
    {
        $this->dispatchBrowserEvent('is_sold_confirm', ['soldLead' => $leadCollection]);
    }

    public function soldConfirmed()
    {
        $soldleadInfo = LeadCollection::findOrFail($this->leadCollection['id']);
        $soldleadInfo->status = 'sold';
        $soldleadInfo->update();

        $this->emitTo('lead-collection.collection-table', 'refreshComponent');
        $this->emitTo('cp-lead.cp-table', 'refreshComponent');
        $this->emitTo('service-lead.service-table', 'refreshComponent');
        $this->emitTo('inactive-lead.inactive-table', 'refreshComponent');

        $this->dispatchBrowserEvent('message', [
            'type' => 'success',
            'title' => 'lead has been converted successfully'
        ]);
    }


    // convert to inactive
    public function convertToInactive($leadCollection)
    {
        $this->dispatchBrowserEvent('is_inactive_confirm', ['inactiveLead' => $leadCollection]);
    }

    public function inactiveConfirmed()
    {
        $soldleadInfo = LeadCollection::findOrFail($this->leadCollection['id']);
        $soldleadInfo->status = 'inactive';
        $soldleadInfo->update();

        $this->emitTo('lead-collection.collection-table', 'refreshComponent');
        $this->emitTo('cp-lead.cp-table', 'refreshComponent');
        $this->emitTo('service-lead.service-table', 'refreshComponent');
        $this->emitTo('sold-lead.sold-table', 'refreshComponent');

        $this->dispatchBrowserEvent('message', [
            'type' => 'success',
            'title' => 'lead has been converted successfully'
        ]);
    }

    public function render()
    {
        return view('livewire.lead-collection.collection-show');
    }
}
