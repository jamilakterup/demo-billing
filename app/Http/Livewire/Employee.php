<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Designation;
use App\Models\Employee as AppEmployee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class Employee extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $listeners = ['deleteConfirmed' => 'deleteConfirmedItem'];
    public $beingDeleteItem = NULL;
    public $designation_array = [];
    public $employee;
    public $signature;
    public $state = [];
    public $isEdit = false;
    public $searchField;
    public $currentPage = 1;

    public function delete($delete_item_id)
    {

        $this->beingDeleteItem = $delete_item_id;
        $this->dispatchBrowserEvent('is_delete_confirm', ['removalId' => $delete_item_id]);
    }

    public function deleteConfirmedItem()
    {
        $delete_item = AppEmployee::findOrFail($this->beingDeleteItem);
        $delete_item->delete();
        $this->dispatchBrowserEvent('delete_confirm', ['title' => 'Employee has been deleted succesfully.']);
    }

    public function editEmployee(AppEmployee $employee)
    {
        $this->isEdit = true;
        $this->employee = $employee;

        $this->state['name'] = $employee->name;
        $this->state['email'] = $employee->email;
        $this->state['phone'] = $employee->phone;
        $this->state['designation_id'] = $employee->designation_id;
        $this->designation_array = Designation::all_designation();
        $this->dispatchBrowserEvent('show-modal');
    }

    public function employeeUpdate()
    {



        $validatedData = Validator::make($this->state, [
            'name' => 'required|max:255',
            'designation_id' => 'required|integer',
            'email' => 'nullable|email|unique:employees,email,' . $this->employee->id,
            'phone' => 'nullable|numeric|min:11|unique:employees,phone,' . $this->employee->id,
            'signature' => 'nullable|mimes:jpg,png,jpeg|max:400', //|dimensions:width=300,height=80
        ])->validate();

        if (isset($this->state['signature']) && !is_null($this->state['signature'])) {
            //$image = $this->state['signature'];
            //$this->state['signature'] = $this->signature;
            $image_path = public_path() . '/' . $this->employee->signature;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            $validatedData['signature'] = Storage::disk("real_public")->put('signatures', $this->state['signature']);
        }




        $this->employee->update($validatedData);

        $this->dispatchBrowserEvent('employee-store', [
            'type' => 'success',
            'title' => 'Employee has been updated succesfully',
        ]);
    }




    public function addNewEmployee()
    {
        $this->isEdit = false;
        $this->designation_array = Designation::all_designation();
        $this->state = [];

        $this->dispatchBrowserEvent('show-modal');
    }


    public function employeeStore()
    {
        // if (!is_null($this->signature)) {
        //     $this->state['signature'] = $this->signature;
        // }

        $validatedData = Validator::make(
            $this->state,
            [
                'name' => 'required|max:255',
                'designation_id' => 'required|integer',
                'email' => 'required|email|unique:employees',
                'phone' => 'required|unique:employees|digits:11',
                'signature' => 'required|mimes:jpg,png,jpeg|max:400',
            ],
            [
                'designation_id.required' => 'The designation is required',
            ]
        )->validate();

        if (isset($this->state['signature'])) {
            $validatedData['signature'] = Storage::disk("real_public")->put('signatures', $this->state['signature']);
        }

        //AppEmployee::create($validatedData);

        $emp = new AppEmployee;
        $emp->name = $validatedData['name'];
        $emp->email = $validatedData['email'];
        $emp->phone = $validatedData['phone'];
        $emp->designation_id = $validatedData['designation_id'];
        $emp->signature = $validatedData['signature'];
        $emp->save();

        $this->state = [];
        $this->dispatchBrowserEvent('employee-store', [
            'type' => 'success',
            'title' => 'Employee has been saved succesfully',
        ]);
    }



    public function render()
    {
        $employees = AppEmployee::with('designation')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchField . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.employee.employee', compact('employees'));
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
