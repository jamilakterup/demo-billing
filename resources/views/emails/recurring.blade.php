@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <!-- <img src="{{ asset('logo/rajlogo.png') }}" width="50px"> -->
        @endcomponent
    @endslot

    <table style="width:100%">
        <tr>
            <td style="text-align:left; vertical-align:middle;"><img src="{{ asset('logo/rajit.png') }}" alt="rajit logo"
                    width="130px"> </td>
            <td style="text-align:center; vertical-align:middle;"><img src="{{ asset('logo/iso.png') }}" alt="iso logo"
                    width="80px"> </td>
            <td style="text-align:right; vertical-align:middle;"><img src="{{ asset('logo/basis.jpg') }}" alt="basis logo"
                    width="120px"> </td>
        </tr>
    </table>

    {{-- Subcopy --}}
    @slot('subcopy')
        @component('mail::subcopy')
            Invoice Details:
            <hr>
            Invoice Number: {{ $invoice->number }} || Invoice Date: {{ $invoice->date }} || Total Amount: {{ $invoice->total }} ||
            Recurring Interval: {{ $invoice->recurring_interval }} Days
        @endcomponent
    @endslot

@endcomponent
 