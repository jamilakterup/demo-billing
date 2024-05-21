
@extends('backend.layout')
@section('content')
    @include('backend.inc.delete')


    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Savings withdrawal</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">savings withdrawal</a></li>
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
                            <div class="card-title">All savings withdrawal</div>
                            <div class="card-tools">
                                <a  href="{{route('savings_withdrawal.create')}}" title="add new member" class="btn-sm btn-primary" data-card-widge="collapse">
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

                                @foreach($savings_withdrawals as $savings_withdrawal)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>
                                            <img class="img-circle mr-2 img-size-32" src="{{asset('img/members/'.$savings_withdrawal->memberName->photo)}}" width="30px">


                                            {{$savings_withdrawal->memberName->name}}
                                        </td>
                                        <td>{{$savings_withdrawal->memberName->phone}}</td>


                                        <td>{{$savings_withdrawal->amount}}</td>
                                        <td>{{$savings_withdrawal->date}}</td>
                                        <td>{{$savings_withdrawal->userName->name}}</td>

                                        <td>
                                            <a href="{{route('savings_withdrawal.edit',$savings_withdrawal->id)}}" title="edit" class="btn-sm btn-success "><i class="fas fa-edit"></i></a>
                                            <span style="cursor: pointer" saving_id="{{$savings_withdrawal->id}}" data-toggle="modal" data-target="#mymodal" title="delete" class="btn-sm btn-danger delete"><i class="fas fa-trash-alt"></i></span>

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