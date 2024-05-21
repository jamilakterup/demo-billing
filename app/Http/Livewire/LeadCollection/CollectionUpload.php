<?php

namespace App\Http\Livewire\LeadCollection;

use App\Models\LeadCollection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class CollectionUpload extends Component
{
    use WithFileUploads;
    public $fileInfo = null;

    public function LeadStoreFile()
    {
        $validatedData = Validator::make(['fileInfo' => $this->fileInfo->get()], [
            'fileInfo' => 'required',
        ])->validate();

        $rows = array_map('str_getcsv', explode("\n", $validatedData['fileInfo']));

        $header = array_shift($rows);

        foreach ($rows as $row) {
            if (count($row) == 0) {
                continue;
            }

            if (count($row) !== count($header)) {
                continue;
            }

            $leadInfos = array_combine($header, $row);

            $newLead = new LeadCollection;

            foreach ($leadInfos as $leadKey => $leadInfo) {
                $newLead->$leadKey = $leadInfo;
            }

            $newLead->save();
        }

        $this->emitTo('lead-collection.collection-table', 'refreshComponent');
        $this->emitTo('cp-lead.cp-table', 'refreshComponent');
        $this->emitTo('service-lead.service-table', 'refreshComponent');
        $this->dispatchBrowserEvent('message', [
            'type' => 'success',
            'title' => 'Lead has been uploaded successfully'
        ]);
    }


    public function render()
    {
        return view('livewire.lead-collection.collection-upload');
    }
}
