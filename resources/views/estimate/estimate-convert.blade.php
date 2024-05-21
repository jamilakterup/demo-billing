@extends('layouts.master')
@section('style')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"
    integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/css/tom-select.bootstrap4.min.css"
    integrity="sha512-rSpBVO3jAoJ/9Mqqk9gjVGgZX5ZFiwYXap9xWfweRUoLdSgp8NJ6ERvFc0jW+VsaVLQY4QJts1MF9TQxiP8IEA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{asset('plugins/printjs/print.min.css')}}" rel="stylesheet" type="text/css">
@livewireStyles
@endsection
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Estimate convert</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Estimate convert</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main livewire content -->

    @livewire('estimate.estimate-convert', ['estimate_id' => $estimate_id])

    <!-- /.content -->
</div>


@section('js')
@livewireScripts
<x-livewiremodal-base />
<!-- Daterange picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
    integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/js/tom-select.complete.js"
    integrity="sha512-KfTOBVJv8qnV1b+2tsbTLepS7+RAgmVV0Odk6cj1eHxbR8WFX99gwIWOutwFAUlsve3FaGG3VxoPWWLRnehX1w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<script src="{{asset('plugins/printjs/print.min.js')}}"></script>

@endsection

@push('scripts')
<script>
    $(document).on('change','#type',function(e){
        var invoiceType=$(this).val();
        if(invoiceType==1){
            $('#interval-container').css('display','block');
        }
        else{
            $('#interval-container').css('display','none');
        }
    });
</script>
@endpush


@endsection