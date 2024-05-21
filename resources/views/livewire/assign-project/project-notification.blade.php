<x-livewiremodal-modal>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SL.</th>
                    <th>Project Title</th>
                    <th>Developer</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th class="text-center">Estimated Time</th>
                    <th class="text-center">Remaining Days</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($assignedProjects->where('project_alart',true))
                @foreach ($assignedProjects->where('project_alart',true) as $assignedProject)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$assignedProject->title}}</td>
                    <td>
                        @php
                        // $employeesName = $employees->sync()
                        $employeesName =
                        $assignedProject->employees->pluck('name')->implode(', ')
                        @endphp
                        {{$employeesName}}
                    </td>
                    <td>{{\Carbon\Carbon::parse($assignedProject->start_date)->format('d-m-Y')}}
                    </td>
                    <td>{{\Carbon\Carbon::parse($assignedProject->end_date)->format('d-m-Y')}}
                    </td>
                    <td style="font-weight: bold" class="text-center">{{
                        $assignedProject->duration }} {{$assignedProject->duration <= 1 ? 'Day' : 'Days' }}</td>
                    </td>
                    <td style="font-weight: bold" class="text-center">
                        {{$assignedProject->remaining_days}}
                        {{$assignedProject->remaining_days <= 1 && $assignedProject->
                            remaining_days > 0 ? 'Day' : 'Days' }}

                    </td>
                    <td>
                        @if ($assignedProject->status == 0)
                        <small class="badge bg-danger">TO DO</small>
                        @elseif($assignedProject->status == 1)
                        <small class="badge bg-warning">Running</small>
                        @else
                        <small class="badge bg-success">Completed</small>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-outline-primary btn-sm fas fa-eye mx-1"
                            onclick='_openModal("Show project", "assign-project.project-show",{{ json_encode(["project" => $assignedProject->id]) }},"xl")'></a>


                        <a class="btn btn-outline-primary btn-sm fas fa-edit mx-1"
                            onclick='_openModal("Edit project", "assign-project.project-edit",{{ json_encode(["project" => $assignedProject->id]) }},"lg")'></a>

                        <a class="btn btn-outline-danger btn-sm"
                            wire:click.prevent="deleteProject({{$assignedProject->id}})"><i
                                class="fas fa-trash"></i></a>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</x-livewiremodal-modal>