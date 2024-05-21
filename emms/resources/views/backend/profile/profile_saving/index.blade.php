
@extends('backend.layout')
@section('content')
    @include('backend.inc.delete')


    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Savings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Saving</a></li>
                        <li class="breadcrumb-item active">Index</li>
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
                            <div class="card-title">All savings</div>
                            <div class="card-tools">
                                <a  href="{{route('profileSavings.create',['user_id'=>Auth::user()->id])}}" title="add new member" class="btn-sm btn-primary" data-card-widge="collapse">
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
                                    <th>Member</th>
                                    <th>Mobile</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($savings as $saving)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>
                                            <img class="img-circle mr-2 img-size-32" src="{{asset('img/members/'.$saving->memberName->photo)}}" width="30px">


                                            {{$saving->memberName->name}}
                                        </td>
                                        <td>{{$saving->memberName->phone}}</td>


                                        <td>{{$saving->amount}}</td>
                                        <td>{{$saving->date}}</td>
                                        <td>{{$saving->userName->name}}</td>

                                        <td>
                                            <a href="{{route('profileSavings.edit',$saving->id)}}" title="edit" class="btn-sm btn-success "><i class="fas fa-edit"></i></a>
                                            <span style="cursor: pointer" saving_id="{{$saving->id}}" data-toggle="modal" data-target="#mymodal" title="delete" class="btn-sm btn-danger delete"><i class="fas fa-trash-alt"></i></span>
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


    @push('scripts')
        <script>



            $(document).ready(function(){

                $(".delete").click(function (){
                    var saving_id=$(this).attr('saving_id');

                   $("#delete_id").val(saving_id);

                    var html='<h1>Hello</h1>';

                });

            });
        </script>
    @endpush

@stop