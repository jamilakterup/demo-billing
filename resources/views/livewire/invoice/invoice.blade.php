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
            <p><b>Dear Sir,</b><br>Greetings! Please find the bill, which goes for <b> {{ $data['total'] }}</b> and has a <b>
                    {{ $data['unit'] }} </b> -day term. The bill information is linked to this email. Please don't hesitate to get
                in touch with us with any questions at anytime. We are open from 9 AM to 5 PM, Saturday-Thursday except for public
                holidays. We can be reached over the phone at +8801772176237. <br>Kind regards,<br>North Bengal
                Engineering<br><br>NB: This is a computer-generated Bill- no signature required.
            </p>
        @endcomponent
    @endslot


    @slot('footer')
        @component('mail::footer')
            <!-- Thanks,<br>{{ $organization->name }} -->

            &copy; {{ date('Y') }} {{ $organization->name }}
            @lang('All rights reserved.')
        @endcomponent
    @endslot
@endcomponent
