<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ProductType;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;

class Type extends Component
{
    use WithPagination;
    protected $listeners = ['deleteConfirmed' => 'deleteConfirmedItem'];
    public $beingDeleteItem = NULL;


    public $type;
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
        $delete_item = ProductType::findOrFail($this->beingDeleteItem);
        $delete_item->delete();
        $this->dispatchBrowserEvent('delete_confirm', ['title' => 'Product type has been deleted succesfully.']);
    }

    public function editProductType(ProductType $type)
    {
        $this->isEdit = true;
        $this->type = $type;
        $this->state = $type->toArray();
        $this->dispatchBrowserEvent('show-modal');
    }

    public function productTypeUpdate()
    {
        $validatedData = Validator::make($this->state, [
            'name' => 'required|max:255',
        ])->validate();

        $this->type->update($validatedData);

        $this->dispatchBrowserEvent('product-type-store', [
            'type' => 'success',
            'title' => 'Product type has been updated succesfully',
        ]);
    }




    public function addNewProductType()
    {
        $this->isEdit = false;
        $this->state = [];
        $this->dispatchBrowserEvent('show-modal');
    }


    public function productTypeStore()
    {
        $validatedData = Validator::make($this->state, [
            'name' => 'required|max:255'
        ])->validate();

        ProductType::create($validatedData);
        $this->state = [];

        $this->dispatchBrowserEvent('product-type-store', [
            'type' => 'success',
            'title' => 'Product type has been saved succesfully',
        ]);
    }



    public function render()
    {
        $types = ProductType::where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchField . '%');
        })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('livewire.product-type.type', compact('types'));
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
