@component('mail::layout')

{{-- Header --}}
@slot ('header')
@component('mail::header', ['url' => config('app.url')])
    <!-- <img src="{{asset('logo/rajlogo.png')}}" width="50px"> -->
@endcomponent
@endslot

<table style="width:100%">
    <tr>
        <td style="text-align:left; vertical-align:middle;"><img src="{{asset('logo/rajit.png')}}" width="130px"></td>
        <td style="text-align:center; vertical-align:middle;"><img src="{{asset('logo/iso.png')}}" width="80px"></td>
        <td style="text-align:right; vertical-align:middle;"><img src="{{asset('logo/basis.jpg')}}" width="120px"></td>
    </tr>
</table>

{{-- Subcopy --}}
@slot('subcopy')
@component('mail::subcopy')
<p><b>Dear Sir,</b><br>
Greetings! Please find the quotation attached herewith the email.
Please feel free to contact us for any query at your convenience.
We are open from 9 AM to 5 PM, Saturday-Thursday except public holidays.
We can be reached over phone at +01762623193 or email - info@rajit.net or WhatsApp +8801888099690
</p>

<br>
<br>

<p>
Kind regards-<br>
rajIT Team
</p>

<br>
<br>
<p>NB: This is a computer-generated Bill- no signature required.</p>


@endcomponent
@endslot

{{-- Footer --}}
@slot ('footer')
@component('mail::footer')
<!-- Thanks,<br>
{{ $organization->name }} -->
&copy; {{ date('Y') }} {{ $organization->name}} 
@lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
