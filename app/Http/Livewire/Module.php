<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Module as AppModule;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;
use App\Rules\Lowercase;

class Module extends Component
{
    use WithPagination;
    protected $listeners = ['deleteConfirmed' => 'deleteConfirmedItem'];
    public $beingDeleteItem = NULL;


    public $module;
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
        $delete_item = AppModule::findOrFail($this->beingDeleteItem);
        $delete_item->delete();

        $this->dispatchBrowserEvent('delete_confirm', ['title' => 'Module has been deleted succesfully.']);
    }

    public function editModule(AppModule $module)
    {
        $this->isEdit = true;
        $this->module = $module;
        $this->state = $module->toArray();
        $this->dispatchBrowserEvent('show-modal');
    }

    public function moduleUpdate()
    {

        $validatedData = Validator::make($this->state, [
            'name' => ['required', 'unique:modules,name,' . $this->state['id'] . '', 'string', 'max:255', new Lowercase],

        ], [
            'name.required' => 'Module name is required'
        ])->validate();

        $this->module->update($validatedData);

        $this->dispatchBrowserEvent('module-store', [
            'type' => 'success',
            'title' => 'module has been updated succesfully',
        ]);
    }




    public function addNewModule()
    {
        $this->isEdit = false;
        $this->state = [];
        $this->dispatchBrowserEvent('show-modal');
    }


    public function moduleStore()
    {
        $validatedData = Validator::make($this->state, [
            'name' => ['required', 'unique:modules,name', 'string', 'max:255', new Lowercase],

        ], [
            'name.required' => 'Module name is required'
        ])->validate();

        AppModule::create($validatedData);
        $this->state = [];

        $this->dispatchBrowserEvent('module-store', [
            'type' => 'success',
            'title' => 'Module has been saved succesfully',
        ]);
    }



    public function render()
    {
        $modules = AppModule::where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchField . '%');
        })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('livewire.module.module', compact('modules'));
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
