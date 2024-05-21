<?php

namespace App\Http\Livewire\AssignProject;

use App\Models\Employee;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProjectNew extends Component
{
    use WithFileUploads;
    public $state = [];
    public $employees;

    public function mount()
    {
        $this->employees = Employee::where('status', 1)->get();
    }

    public function projectStore()
    {
        $validatedData = Validator::make($this->state, [
            'title' => 'required',
            'name' => 'required',
            'file_type' => 'required|file|mimes:pdf',
            'start_date' => 'required',
            'end_date' => 'required',
        ], [
            'title.required' => 'Title field is required.',
            'file_type.required' => 'PDF file is required.',
            'name.required' => 'Developer name is required.',
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
        ])->validate();


        $newProject = new Project();

        if (isset($this->state['file_type']) && $this->state['file_type']) {
            $file = $this->state['file_type']->getClientOriginalExtension();
            $fileName = 'file_' . time() . '.' . $file;
            $this->state['file_type']->storeAs('files', $fileName, 'public');
            $newProject->file = 'files/' . $fileName;
        }

        $newProject->title = $validatedData['title'];
        $newProject->start_date = $validatedData['start_date'];
        $newProject->end_date = $validatedData['end_date'];

        $newProject->save();

        $newProject->employees()->sync($this->state['name']);

        $this->emitTo("assign-project.project-table", "refreshComponent");
        $this->dispatchBrowserEvent('message', [
            'type' => 'success',
            'title' => 'Project has been created succesfully',
        ]);
    }


    public function render()
    {
        return view('livewire.assign-project.project-new');
    }
}
