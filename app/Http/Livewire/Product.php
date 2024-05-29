<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\Product as AppProduct;
use App\Models\ProductType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NuberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Product extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $listeners = [
        'removalId' => 'deleteConfirmed'
    ];
    public $beingDeleteItem = NULL;
    public $product_type_array = [];
    public $unit_array = [];
    // public $products;
    public $product;
    public $state = [];
    public $isEdit = false;
    public $searchField;
    public $currentPage = 1;

    public function delete($delete_item_id)
    {
        $this->beingDeleteItem = $delete_item_id;
        $this->dispatchBrowserEvent('is_delete_confirm', ['removalId' => $delete_item_id]);
    }

    public function deleteConfirmed()
    {
        $delete_item = AppProduct::findOrFail($this->beingDeleteItem);

        $file_path = public_path() . '/storage/' . $delete_item['image'];
        if (file_exists($file_path) && !is_null($delete_item['image'])) {
            unlink($file_path);
        }

        $delete_item->delete();
        $this->dispatchBrowserEvent('delete_confirm', ['title' => 'Product has been deleted succesfully.']);
    }

    public function editProduct(AppProduct $product)
    {
        $this->isEdit = true;
        $this->product = $product;
        $this->state = $product->toArray();
        $this->product_type_array = ProductType::all_product_type();
        $this->unit_array = Unit::all_unit();
        $this->dispatchBrowserEvent('show-modal');
    }
    public function productUpdate()
    {
        $validatedData = Validator::make($this->state, [
            'name' => 'required|max:255',
            'image' => 'nullable',
            'description' => 'nullable',
            'unit_id' => 'required|integer',
            'product_type_id' => 'required|integer',
            'price' => 'required|numeric',
        ])->validate();


        if (isset($this->state['image']) && is_object($this->state['image'])) {
            $file_path = public_path() . '/storage/' . $this->product['image'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            $file = $this->state['image'];
            $extension = $file->getClientOriginalExtension();
            $fileName = 'product/' . 'product_' . uniqid() . '.' . $extension; //set to db and local
            $file->storeAs('public', $fileName);
            $validatedData['image'] = $fileName;
        }



        $this->product->update($validatedData);

        $this->dispatchBrowserEvent('product-store', [
            'type' => 'success',
            'title' => 'Product has been updated succesfully',
        ]);
    }




    public function addNewProduct()
    {
        $this->isEdit = false;
        $this->product_type_array = ProductType::all_product_type();
        $this->unit_array = Unit::all_unit();
        $this->state = [];

        $this->dispatchBrowserEvent('show-modal');
    }


    public function productStore()
    {
        $validatedData = Validator::make($this->state, [
            'name' => 'required|max:255',
            'image' => 'nullable|file|mimes:jpg,jpeg,png',
            'description' => 'nullable',
            'unit_id' => 'required|integer',
            'product_type_id' => 'required|integer',
            'price' => 'required|integer',
        ])->validate();

        if (isset($this->state['image'])) {
            $file = $this->state['image'];
            $extension = $file->getClientOriginalExtension();
            $fileName = 'product/' . 'product_' . uniqid() . '.' . $extension; //set to db and local
            $file->storeAs('public', $fileName);
            $validatedData['image'] = $fileName;
        }
        AppProduct::create($validatedData);

        $this->dispatchBrowserEvent('product-store', [
            'type' => 'success',
            'title' => 'Product has been saved succesfully',
        ]);
    }



    public function render()
    {
        $products = AppProduct::with('productType')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchField . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.product.product', compact('products'));
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
