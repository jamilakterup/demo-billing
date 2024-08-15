<?php

namespace App\Http\Livewire\LeadCollection;

use App\Models\Customer;
use App\Models\LeadCollection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConvertToCustomer extends Component
{
    use WithFileUploads;

    public $leadStatusInfos;
    public $leadCollection;
    public $company_logo;
    public $state = [];

    public function mount(LeadCollection $leadCollection)
    {
        $this->leadCollection = $leadCollection;
        $this->state = [
            'name' => $leadCollection->name,
            'phone' => $leadCollection->phone,
            'email' => $leadCollection->email,
            'source' => $leadCollection->source,
            // 'company_name' => '',
            // 'company_email' => '',
            // 'company_phone' => '',
            // 'company_address' => '',
            // 'company_website' => '',
            // 'company_logo' => '',
        ];
    }

    public function leadConvertToCustomer()
    {
        $validatedData = Validator::make($this->state, [
            'name' => 'required|max:255',
            'display_name' => 'required|max:255',
            'phone' => 'required|numeric',
            // 'email' => 'required|email|max:255',
            'company_name' => 'required|max:255',
            // 'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|numeric',
            // 'company_address' => 'nullable|max:255',
            // 'company_website' => 'required|max:255',
            // 'company_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ])->validate();

        $customer = new Customer;
        $customer->name = $this->state['name'];
        $customer->display_name = $this->state['display_name'];
        $customer->email = $this->state['email'];
        $customer->phone = $this->state['phone'];

        $customer->company_name = $this->state['company_name'];
        $customer->company_email = $this->state['company_email'] ?? null;
        $customer->company_website = $this->state['company_website'] ?? null;
        $customer->company_phone = $this->state['company_phone'] ?? null;
        $customer->company_address = $this->state['company_address'] ?? null;


        $customer->save();



        if ($this->company_logo && $this->company_logo->hasFile('company_logo')) {
            $image = $this->company_logo->file('company_logo');
            $ext = $image->getClientOriginalExtension();
            $file_name = 'company_logo_' . $customer->id . '.' . $ext;

            $des = public_path() . '/logo';
            $image->move($des, $file_name);
            $customer->company_logo = $file_name;
            $customer->save();
        }

        $leadInfo =  LeadCollection::findOrFail($this->leadCollection->id);
        $leadInfo->status = 'customer';
        $leadInfo->update();


        $this->emitTo('lead-collection.collection-table', 'refreshComponent');
        $this->emitTo('cp-lead.cp-table', 'refreshComponent');
        $this->emitTo('service-lead.service-table', 'refreshComponent');
        $this->dispatchBrowserEvent('message', [
            'type' => 'success',
            'title' => 'lead has been created successfully'
        ]);
    }

    public function render()
    {
        return view('livewire.lead-collection.convert-to-customer');
    }
}
