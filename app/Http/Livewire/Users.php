<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;
use Response;

class Users extends Component
{
    public $users;
    public $name;
    public $email;
    public $password;
    public $currentPage=1;

    use WithPagination; 

    public function render()
    {
        $this->users=User::all();
        return view('livewire.user.users');
    }

    public function createUser(){
        //$createView=view('livewire.user.userCreate')->render();

        $this->dispatchBrowserEvent('create-form');
    }

    public function userStore(){

        $validatedData = $this->validate([
            'name' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        User::create($validatedData);

        $this->dispatchBrowserEvent('user-store',[
            'type'=>'success',
            'title'=>'User has been saved succesfully',
        ]);
    }
    public function setPage($url){
        $this->currentPage=explode('page=',$url)[1];
        Paginator::currentPageResolver(function(){
            return $this->currentPage;
        });
    }
}
