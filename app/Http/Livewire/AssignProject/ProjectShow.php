<?php

namespace App\Http\Livewire\AssignProject;

use App\Models\Project;
use Livewire\Component;

class ProjectShow extends Component
{
    public $showProject;
    public $pdf;

    public function mount(Project $project)
    {
        $newpdf = public_path() . '/storage/' . $project->file;
        $this->pdf = $newpdf;
        $this->showProject = $project;
    }

    public function render()
    {
        return view('livewire.assign-project.project-show');
    }
}
