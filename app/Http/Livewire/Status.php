<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Status as AppStatus;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;

class Status extends Component
{
    use WithPagination;
    protected $listeners = ['deleteConfirmed' => 'deleteConfirmedItem'];
    public $beingDeleteItem = NULL;


    public $status;
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
        $delete_item = AppStatus::findOrFail($this->beingDeleteItem);
        $delete_item->delete();
        $this->dispatchBrowserEvent('delete_confirm', ['title' => 'Status type has been deleted succesfully.']);
    }

    public function editStatus(AppStatus $status)
    {
        $this->isEdit = true;
        $this->status = $status;
        $this->state = $status->toArray();
        $this->dispatchBrowserEvent('show-modal');
    }

    public function statusUpdate()
    {
        $validatedData = Validator::make($this->state, [
            'type' => 'required|max:255',
            'name' => 'required|max:255',
            'color' => 'required|min:6|max:6'
        ])->validate();

        $this->status->update($validatedData);

        $this->dispatchBrowserEvent('status-store', [
            'type' => 'success',
            'title' => 'Status has been updated succesfully',
        ]);
    }




    public function addNewStatus()
    {
        $this->isEdit = false;
        $this->state = [];
        $this->dispatchBrowserEvent('show-modal');
    }


    public function statusStore()
    {
        $validatedData = Validator::make($this->state, [
            'type' => 'required|max:255',
            'name' => 'required|max:255',
            'color' => 'required|min:6'
        ])->validate();

        AppStatus::create($validatedData);
        $this->state = [];

        $this->dispatchBrowserEvent('status-store', [
            'type' => 'success',
            'title' => 'Status has been saved succesfully',
        ]);
    }



    public function render()
    {
        $statuses = AppStatus::where(function ($query) {
            $query->where('type', 'like', '%' . $this->searchField . '%');
        })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('livewire.status.status', compact('statuses'));
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
