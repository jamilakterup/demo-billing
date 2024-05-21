<?php

namespace App\Http\Livewire;
use App\Models\Organization as Company;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class Organization extends Component
{

    use WithFileUploads;

    public $state=[];
    public $organization;
    public $logo;


    public function organizationUpdate(){
        $organization=Company::find(1);
        $this->organization=$organization;
        //$this->state=$company->toArray();
        $validatedData = Validator::make($this->state,[
            'name' => 'required|max:255',
            'email' => 'required|max:225',
            'phone' => 'required|max:20',
            'mobile' => 'required|min:11|max:11',
            'address' => 'required|max:225',
        ])->validate();

        //dd($this->logo);
        if($this->logo){
            $validatedData['logo']=$this->logo->store('/','logo');
        }

        $this->organization->update($validatedData);



        $this->dispatchBrowserEvent('update',[
            'type'=>'success',
            'title'=>'Organization has been updated succesfully',
        ]);
    }

    public function render()
    {
        $organiza=Company::find(1);
        $this->state=$organiza->toArray();
        return view('livewire.organization.organization',compact('organiza'));
    }
}
