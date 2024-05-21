<div>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Sold Lead</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Sold -lead</li>
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
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="btn-group mb-2" role="group" aria-label="Basic example">
                                        <button wire:click.prevent='exportCSV' type="button"
                                            class="btn btn-secondary">Export CSV</button>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" wire:model="searchField" class="form-control w-100"
                                            placeholder="Search by name, phone, date.." />
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>SL.</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Source</th>
                                                <th>Comment</th>
                                                <th>Status</th>
                                                <th>Followup No.</th>
                                                <th>Next Followup</th>
                                                <th style="width:128px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leadCollections as $leadCollection)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$leadCollection->name}}</td>
                                                <td>{{$leadCollection->phone}}</td>
                                                <td>{{$leadCollection->email}}</td>
                                                <td>{{$leadCollection->source}}</td>
                                                <td>
                                                    {{
                                                    optional($leadCollection->lead_status()->latest()->first())->comment
                                                    ?
                                                    Str::limit($leadCollection->lead_status()->latest()->first()->comment,
                                                    35, '...') : '' }}

                                                </td>
                                                <td>
                                                    {{$leadCollection->lead_status()->latest() ->first()->status??""}}
                                                </td>
                                                <td>
                                                    {{$leadCollection->lead_status()->latest()->first()->followup??""}}
                                                </td>
                                                <td>
                                                    {{ optional($leadCollection->lead_status()->latest()->first())->date
                                                    ?
                                                    \Carbon\Carbon::parse($leadCollection->lead_status()->latest()->first()->date)->format('d-m-Y')
                                                    : "" }}

                                                </td>
                                                <td>
                                                    <a class="btn btn-outline-primary btn-sm fas fa-eye"
                                                        onclick='_openModal("Show Lead Details", "lead-collection.collection-show",{{json_encode(["leadCollection"=>"$leadCollection->id","routeName"=>\Route::currentRouteName()])}},"xl")'></a>

                                                    <a class="btn btn-outline-primary btn-sm"
                                                        onclick='_openModal("Edit Lead", "lead-collection.collection-edit",{{json_encode(["leadCollection"=>"$leadCollection->id"])}},"lg")'><i
                                                            class="fas fa-edit"></i></a>

                                                    <a class="btn btn-outline-danger btn-sm"
                                                        wire:click.prevent="deleteLead({{$leadCollection->id}})"><i
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