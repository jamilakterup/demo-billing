<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Permission as AppPermission;
use App\Models\Module;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;
use App\Rules\Lowercase;

class Permission extends Component
{
    use WithPagination;
    protected $listeners = ['deleteConfirmed' => 'deleteConfirmedItem'];
    public $beingDeleteItem = NULL;


    public $permission;
    public $state = [];
    public $isEdit = false;
    public $currentPage = 1;
    public $searchField;
    public $allModule = [];

    public function delete($delete_item_id)
    {

        $this->beingDeleteItem = $delete_item_id;
        $this->dispatchBrowserEvent('is_delete_confirm', ['removalId' => $delete_item_id]);
    }

    public function deleteConfirmedItem()
    {

        $delete_item = AppPermission::findOrFail($this->beingDeleteItem);
        $delete_item->delete();

        $this->dispatchBrowserEvent('delete_confirm', ['title' => 'Permission has been deleted succesfully.']);
    }

    public function editPermission(AppPermission $permission)
    {
        $this->isEdit = true;
        $this->permission = $permission;
        $this->state = $permission->toArray();
        $permission_name = $this->state['name'];
        $arr = explode('.', $permission_name);
        $this->state['name'] = $arr[1];

        $this->allModule = Module::all()->pluck('name', 'id')->toArray();
        $this->dispatchBrowserEvent('show-modal');
    }

    public function permissionUpdate()
    {
        $validatedData = Validator::make($this->state, [
            'module_id' => 'required|max:255',
            'name' => ['required', 'string', new Lowercase],
        ], [
            'module_id.required' => 'Module name is required',
            'name.required' => 'Permission name is required'
        ])->validate();


        $module_name = Module::findOrfail($this->state['module_id'])->name;




        $this->permission->update(
            [
                'module_id' => $this->state['module_id'],
                'name' => $module_name . '.' . $this->state['name'],
            ]
        );

        $this->dispatchBrowserEvent('permission-store', [
            'type' => 'success',
            'title' => 'Permission has been updated succesfully',
        ]);
    }




    public function addNewPermission()
    {
        $this->isEdit = false;
        $this->state = [];
        $this->allModule = Module::all()->pluck('name', 'id')->toArray();
        $this->dispatchBrowserEvent('show-modal');
    }


    public function permissionStore()
    {
        $validatedData = Validator::make($this->state, [
            'module_id' => 'required|max:255',
            'name' => ['required', 'string', new Lowercase],
        ], [
            'module_id.required' => 'Module name is required',
            'name.required' => 'Permission name is required'
        ])->validate();

        //dd($this->state);
        $module_name = Module::findOrfail($this->state['module_id']);
        AppPermission::create([
            'module_id' => $this->state['module_id'],
            'name' => $this->state['name'] . '.' . $module_name->name,
        ]);


        $this->state = [];

        $this->dispatchBrowserEvent('permission-store', [
            'type' => 'success',
            'title' => 'Permission has been saved succesfully',
        ]);
    }



    public function render()
    {
        $permissions = AppPermission::where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchField . '%');
        })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('livewire.permission.permission', compact('permissions'));
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
