
@extends('backend.layout')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Members</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">All members</div>
                            <div class="card-tools">
                                <a  href="{{route('member.create')}}" title="add new member" class="btn-sm btn-primary" data-card-widge="collapse">
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                            $i=1;
                            @endphp

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Photo</th>
                                        <th>User</th>
                                        <th>Loan</th>
                                        <th>Savings</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($members as $member)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$member->name}}</td>
                                        <td>{{$member->phone}}</td>
                                        <td>
                                            <div class="img-container">
                                                <img class="big-img img-thumbnail" src="{{asset('img/members/'.$member->photo)}}" width="100px">
                                                <img class="img-thumbnail" src="{{asset('img/members/'.$member->photo)}}" width="30px">
                                            </div>
                                        </td>
                                        <td>{{$member->agentName->name}}</td>
                                        <td><a href="{{route('loan.create',['member_id'=>$member->id])}}" title="Loan" class="btn-sm btn-primary"><i class="fas fa-dollar-sign"></i></a></td>
                                        <td><a href="{{route('savings.create2',['member_id'=>$member->id])}}" title="Loan" class="btn-sm btn-primary"><i class="fas fa-sack-dollar"></i></a></td>

                                        <td>
                                            <a href="{{route('member.show',$member->id)}}" title="show details" class="btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                            <a href="{{route('member.edit',$member->id)}}" title="edit" class="btn-sm btn-success "><i class="fas fa-edit"></i></a>
                                            <a href="" title="delete" class="btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>


{{--                                            {{Form::open(['route'=>['member.destroy',$member->id,],'method'=>'delete','class'=>'form form-inline'])}}--}}
{{--                                                {{Form::submit('Yes',['class'=>'btn btn-danger'])}}--}}
{{--                                                <button type="submit" class="btn-sm btn-secondary"><i class="fas fa-trash-alt"></i></button>--}}
{{--                                            {{Form::close()}}--}}
{{--                                                <a href="{{route('member.destroy',$member->id)}}" title="delete" class="btn btn-secondary"><i class="fas fa-trash-alt"></i></a>--}}

                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->


            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-8">
                    <!-- MAP & BOX PANE -->

                    <!-- /.card -->

                    <!-- /.row -->

                    <!-- TABLE: LATEST ORDERS -->

                    <!-- /.card -->
                </div>
                <!-- /.col -->


                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>

@stop