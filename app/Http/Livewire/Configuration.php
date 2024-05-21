<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Configuration as ConfigurationNew;

class Configuration extends Component
{

    public $configuration;


    public function mount(){
        $this->configuration=ConfigurationNew::find(1);
    }


    public function render()
    {
        return view('livewire.configuration.configuration');
    }
}
