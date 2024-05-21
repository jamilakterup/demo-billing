<?php

namespace App\Http\Livewire\AssignProject;

use App\Models\Employee;
use App\Models\Project;
use Carbon\Carbon;
use Livewire\Component;

class ProjectNotification extends Component
{
    public $employees;
    public $searchField;
    public $beingDeleteItem = NULL;

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'removalId' => 'deleteConfirmed'
    ];



    public function deleteProject($delete_item_id)
    {
        $this->beingDeleteItem = $delete_item_id;
        $this->dispatchBrowserEvent('is_delete_confirm', ['removalId' => $delete_item_id]);
    }

    public function deleteConfirmed()
    {
        if ($this->beingDeleteItem) {
            $delete_item = Project::findOrFail($this->beingDeleteItem);
            $delete_item->employees()->detach();
            $delete_item->delete();

            $file_path = public_path() . '/storage/' . $delete_item->file;
            if (file_exists($file_path) && !is_null($delete_item->file)) {
                unlink($file_path);
            }

            $this->dispatchBrowserEvent('notification', ['type' => 'success', 'msg' => 'Project has been deleted succesfully.']);
        }
    }


    public function render()
    {
        $assignedProjects = Project::with('employees')
            ->where('title', 'like', '%' . $this->searchField . '%')
            ->orWhereHas('employees', function ($query) {
                $query->where('name', 'like', '%' . $this->searchField . '%');
            })
            ->get();

        // get project which has deadline bellow 7days
        foreach ($assignedProjects as $assignedProject) {

            $end_date = Carbon::parse($assignedProject->end_date);
            $remainingDays = Carbon::now()->diffInDays($end_date);

            if ($end_date < Carbon::now()) {
                $remainingDays *= -1;
            }
            $assignedProject->remaining_days = $remainingDays;

            if ($remainingDays <= 7) {
                $assignedProject->project_alart = 'true';
            }
        }

        $this->employees = Employee::where('status', 1)->get();

        foreach ($assignedProjects as $assignedProject) {
            $assignedProject->duration = Carbon::parse($assignedProject->start_date)->diffInDays($assignedProject->end_date);
        }

        return view('livewire.assign-project.project-notification', compact('assignedProjects'));
    }
}
