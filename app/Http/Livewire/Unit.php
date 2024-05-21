<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Unit as ProductUnit;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;

class Unit extends Component
{
    use WithPagination;
    protected $listeners = ['deleteConfirmed' => 'deleteConfirmedItem'];
    public $beingDeleteItem = NULL;


    public $unit;
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
        $delete_item = ProductUnit::findOrFail($this->beingDeleteItem);
        $delete_item->delete();
        $this->dispatchBrowserEvent('delete_confirm', ['title' => 'Unit has been deleted succesfully.']);
    }

    public function editUnit(ProductUnit $unit)
    {
        $this->isEdit = true;
        $this->unit = $unit;
        $this->state = $unit->toArray();
        $this->dispatchBrowserEvent('show-modal');
    }

    public function unitUpdate()
    {
        $validatedData = Validator::make($this->state, [
            'name' => 'required|max:255',
        ])->validate();

        $this->unit->update($validatedData);

        $this->dispatchBrowserEvent('unit-store', [
            'type' => 'success',
            'title' => 'Unit has been updated succesfully',
        ]);
    }




    public function addNewUnit()
    {
        $this->isEdit = false;
        $this->state = [];
        $this->dispatchBrowserEvent('show-modal');
    }


    public function unitStore()
    {
        $validatedData = Validator::make($this->state, [
            'name' => 'required|max:255'
        ])->validate();

        ProductUnit::create($validatedData);
        $this->state = [];

        $this->dispatchBrowserEvent('unit-store', [
            'type' => 'success',
            'title' => 'Unit has been saved succesfully',
        ]);
    }



    public function render()
    {
        $units = ProductUnit::where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchField . '%');
        })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('livewire.unit.unit', compact('units'));
    }
    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
