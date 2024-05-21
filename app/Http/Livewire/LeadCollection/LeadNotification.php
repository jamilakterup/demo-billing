<?php

namespace App\Http\Livewire\LeadCollection;

use App\Models\LeadCollection;
use Carbon\Carbon;
use Livewire\Component;

class LeadNotification extends Component
{
    public $beingDeleteItem = null;
    public $leadCollections;

    public function followupDate()
    {
        foreach ($this->leadCollections as $leadCollection) {
            if ($leadCollection->lead_status->isNotEmpty()) {
                $followupDate = $leadCollection->lead_status->last()->date; // Get the last lead status date

                $followupDate = Carbon::parse($followupDate);

                if (Carbon::now()->greaterThanOrEqualTo($followupDate)) {
                    $leadCollection->followup_status = 'true';
                }
            }
        }
    }


    protected $listeners = [
        'refreshComponent' => '$refresh',
        'removalId' => 'deleteConfirmed'
    ];

    public function deleteLead($delete_item_id)
    {
        $this->beingDeleteItem = $delete_item_id;
        $this->dispatchBrowserEvent('is_delete_confirm', ['removalId' => $delete_item_id]);
    }

    public function deleteConfirmed()
    {
        if ($this->beingDeleteItem) {
            $delete_item = LeadCollection::findOrFail($this->beingDeleteItem);
            $delete_item->lead_status()->delete();
            $delete_item->delete();

            $this->emitTo('lead-collection.collection-table', 'refreshComponent');
            $this->emitTo('cp-lead.cp-table', 'refreshComponent');
            $this->emitTo('service-lead.service-table', 'refreshComponent');
            $this->dispatchBrowserEvent('notification', ['type' => 'success', 'msg' => 'Lead has been deleted succesfully.']);
        }
    }

    public function render()
    {
        $this->leadCollections = LeadCollection::where('status', 'pending')
            ->where('type', 'new')
            ->with('lead_status')
            ->get();

        $this->followupDate();

        return view('livewire.lead-collection.lead-notification', ['leadCollections' => $this->leadCollections]);
    }
}
