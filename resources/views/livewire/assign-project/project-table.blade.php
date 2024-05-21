<div>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">All Projects</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">All-projects</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">All Projects</div>
                                <div class="card-tools d-flex">
                                    <button class="btn btn-primary btn-sm w-100 mx-1" type="button"
                                        onclick='_openModal("Assing New Project", "assign-project.project-new","[]","lg")'>
                                        <i class="fa fa-plus"></i>Assign New Project</button>

                                    <button type="button" style="width:40px"
                                        class="btn btn-primary position-relative mr-2"
                                        onclick='_openModal("Project Notification", "assign-project.project-notification",[], "xl")'>
                                        <i class="fa fa-bell text-lg" aria-hidden="true"></i>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ collect($assignedProjects)->where('project_alart', 'true')->count()
                                            }}
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <input type="text" wire:model="searchField" class="form-control w-25  ml-auto"
                                        placeholder="Search with title or developer.." />
                                </div>
                                <div class="table-responsive">

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>SL.</th>
                                                <th>Project Title</th>
                                                <th>Developer</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Estimated Time</th>
                                                <th>Remaining Days</th>
                                                <th>Status</th>
                                                <th width='128px'>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($assignedProjects as $assignedProject)
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
                                                    $assignedProject->duration }} {{$assignedProject->duration <= 1
                                                        ? 'Day' : 'Days' }}</td>
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
                                                    <a class="btn btn-outline-primary btn-sm fas fa-eye"
                                                        onclick='_openModal("Show project", "assign-project.project-show",{{ json_encode(["project" => $assignedProject->id]) }},"xl")'></a>


                                                    <a class="btn btn-outline-primary btn-sm fas fa-edit"
                                                        onclick='_openModal("Edit project", "assign-project.project-edit",{{ json_encode(["project" => $assignedProject->id]) }},"lg")'></a>

                                                    <a class="btn btn-outline-danger btn-sm"
                                                        wire:click.prevent="deleteProject({{$assignedProject->id}})"><i
                                                            class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>

@push('scripts')
<script>
    document.addEventListener("livewire:load", function (event) {
    window.livewire.hook('message.processed', () => {
        
        $(document).ready(function() {
            $('.tom').each(function() {
                new TomSelect(this);
            });                
        });

    });
});
</script>
@endpush