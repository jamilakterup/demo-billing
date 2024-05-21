<?php

namespace App\Http\Livewire\AssignProject;

use Illuminate\Support\Facades\Validator;
use App\Models\Employee;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProjectEdit extends Component
{
    use WithFileUploads;
    public $state = [];
    public $employees;
    public $project;

    public function mount(Project $project)
    {
        $this->employees = Employee::where('status', 1)->get();
        $this->project = $project;

        $this->state['title'] = $project->title;
        $this->state['file_type'] = $project->file_type;
        $this->state['name'] = $project->employees()->pluck('employee_id')->toArray();
        $this->state['start_date'] = $project->start_date;
        $this->state['end_date'] = $project->end_date;
        $this->state['report'] = $project->report;
        $this->state['status'] = $project->status;
    }

    public function projectStore()
    {
        $validatedData = Validator::make($this->state, [
            'title' => 'required',
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'report' => 'nullable',
            'status' => 'nullable',
        ], [
            'title.required' => 'Title field is required.',
            'name.required' => 'Developer name is required.',
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
        ])->validate();




        $newProject = $this->project;

        if (isset($this->state['file_type']) && $this->state['file_type']) {
            $file = $this->state['file_type']->getClientOriginalExtension();
            $fileName = 'file_' . time() . '.' . $file;
            $this->state['file_type']->storeAs('files', $fileName, 'public');
            $newProject->file = 'files/' . $fileName;
        }

        $newProject->title = $validatedData['title'];
        $newProject->start_date = $validatedData['start_date'];
        $newProject->end_date = $validatedData['end_date'];
        $newProject->report = $validatedData['report'];
        $newProject->status = $validatedData['status'];

        $newProject->update();

        $newProject->employees()->sync($this->state['name']);

        $this->emitTo("assign-project.project-table", "refreshComponent");
        $this->dispatchBrowserEvent('message', [
            'type' => 'success',
            'title' => 'Project has been updated succesfully',
        ]);
    }

    public function render()
    {
        return view('livewire.assign-project.project-edit');
    }
}
