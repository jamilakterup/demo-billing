<?php

namespace App\Http\Livewire\AssignProject;

use App\Mail\ProjectMail;
use App\Models\SendMail;
use App\Models\Project;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;

class ProjectSendMail extends Component
{
    public function send_email($id)
    {
        $project = Project::findOrFail($id);
        $employees = $project->employees()->get()->toArray();

        foreach ($employees as $employee) {
            // Mail::to($employee['email'])->send(new ProjectMail($project));
            try {
                Mail::to($employee['email'])->send(new ProjectMail($project));
            } catch (\Exception $e) {
                dd($e);
            }
        }

        // Save to the database after sending emails
        $sent_mail = new SendMail;
        $sent_mail->project_id = $project->id;
        $sent_mail->title = $project->title; // Assuming title is a property of the Project model
        $sent_mail->save();

        Alert::success('Success', 'Invoice has been mailed successfully.');
        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.assign-project.project-send-mail');
    }
}
