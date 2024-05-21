<?php

namespace App\Http\Livewire\ServiceLead;

use App\Models\LeadCollection;
use App\Models\ServiceLead;
use Carbon\Carbon;
use Livewire\Component;

class ServiceNotification extends Component
{
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'removalId' => 'deleteConfirmed'
    ];


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

    public function deleteLead($delete_item_id)
    {
        $this->beingDeleteItem = $delete_item_id;
        $this->dispatchBrowserEvent('is_delete_confirm', ['removalId' => $delete_item_id]);
    }

    public function deleteConfirmed()
    {
        if ($this->beingDeleteItem) {
            $delete_item = ServiceLead::findOrFail($this->beingDeleteItem);
            $delete_item->service_status()->delete();
            $delete_item->delete();

            $this->emitTo('service-lead.service-table', 'refreshComponent');
            $this->dispatchBrowserEvent('notification', ['type' => 'success', 'msg' => 'Lead has been deleted succesfully.']);
        }
    }

    public function render()
    {
        $this->leadCollections = LeadCollection::where('status', 'pending')
            ->where('type', 'service')
            ->with('lead_status')
            ->get();

        $this->followupDate();

        return view('livewire.service-lead.service-notification', ['leadCollections' => $this->leadCollections]);
    }
}
