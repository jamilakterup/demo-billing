<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Designation as AppDesignation;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;

class Designation extends Component
{
    use WithPagination;
    protected $listeners = ['deleteConfirmed' => 'deleteConfirmedItem'];
    public $beingDeleteItem = NULL;


    public $designation;
    public $state = [];
    public $isEdit = false;
    public $currentPage = 1;
    public $searchField;

    public function delete($delete_item_id)
    {

        $this->beingDeleteItem = $delete_item_id;
        $this->dispatchBrowserEvent('is_delete_confirm', ['removalId' => $delete_item_id]);
    }

    public function deleteConfirmedItem()
    {
        $delete_item = AppDesignation::findOrFail($this->beingDeleteItem);
        $delete_item->delete();
        $this->dispatchBrowserEvent('delete_confirm', ['title' => 'Designation has been deleted succesfully.']);
    }

    public function editDesignation(AppDesignation $designation)
    {
        $this->isEdit = true;
        $this->designation = $designation;
        $this->state = $designation->toArray();
        $this->dispatchBrowserEvent('show-modal');
    }

    public function designationUpdate()
    {
        $validatedData = Validator::make($this->state, [
            'name' => 'required|max:255',
        ])->validate();

        $this->designation->update($validatedData);

        $this->dispatchBrowserEvent('designation-store', [
            'type' => 'success',
            'title' => 'Designation has been updated succesfully',
        ]);
    }




    public function addNewDesignation()
    {
        $this->isEdit = false;
        $this->state = [];
        $this->dispatchBrowserEvent('show-modal');
    }


    public function designationStore()
    {

        $validatedData = Validator::make($this->state, [
            'name' => 'required|max:255'
        ])->validate();



        AppDesignation::create($validatedData);
        $this->state = [];

        $this->dispatchBrowserEvent('designation-store', [
            'type' => 'success',
            'title' => 'Designation has been saved succesfully',
        ]);
    }



    public function render()
    {
        $designations = AppDesignation::where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchField . '%');
        })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('livewire.designation.designation', compact('designations'));
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
