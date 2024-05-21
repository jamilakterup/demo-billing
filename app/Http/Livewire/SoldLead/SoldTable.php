<?php

namespace App\Http\Livewire\SoldLead;

use App\Models\LeadCollection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SoldTable extends Component
{
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'removalId' => 'deleteConfirmed'
    ];

    public $beingDeleteItem = null;
    public $leadCollections;
    public $searchField;
    public $routeName;

    public function mount()
    {
        $this->routeName = Route::currentRouteName();
    }

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

            $this->dispatchBrowserEvent('notification', ['type' => 'success', 'msg' => 'Lead has been deleted succesfully.']);
        }
    }

    public function exportCSV()
    {
        $leadCollections = LeadCollection::where('status', 'sold')
            ->where(function ($q) {
                if ($this->searchField) {
                    $q->where('name', 'like', '%' . $this->searchField . '%')
                        ->orWhere('phone', 'like', '%' . $this->searchField . '%')
                        ->orWhereHas('lead_status', function ($query) {
                            if ($this->searchField) {
                                $query->where('date', 'like', '%' . $this->searchField . '%');
                            }
                        });
                }
            })
            ->with('lead_status')
            ->get();


        $data[] = ['Name', 'Email', 'Phone', 'Source'];

        foreach ($leadCollections as $val) {
            $data[] = [
                $val->name,
                $val->email,
                $val->phone,
                $val->source,
            ];
        }
        $filename = 'sold_lead' . '.csv';
        $filePath = public_path() . '/temp/' . $filename;

        // Open file handler with error handling
        $file = fopen($filePath, 'w');
        if (!$file) {
            return "Failed to open file: $filePath";
        }

        // Write data to CSV file
        foreach ($data as $row) {
            fputcsv($file, (array) $row);
        }
        fclose($file);

        // Check if file exists before downloading
        if (file_exists($filePath)) {
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return response()->download($filePath, $filename, $headers);
        } else {
            return "File not found: $filePath";
        }
    }

    public function render()
    {
        $this->leadCollections = LeadCollection::where('status', 'sold')
            ->where(function ($q) {
                if ($this->searchField) {
                    $q->where('name', 'like', '%' . $this->searchField . '%')
                        ->orWhere('phone', 'like', '%' . $this->searchField . '%')
                        ->orWhereHas('lead_status', function ($query) {
                            if ($this->searchField) {
                                $query->where('date', 'like', '%' . $this->searchField . '%');
                            }
                        });
                }
            })
            ->with('lead_status')
            ->get();

        return view('livewire.sold-lead.sold-table');
    }
}
