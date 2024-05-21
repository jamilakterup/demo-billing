@component('mail::message')
# Recurring invoice

Invoice Details:
<hr>
Invoice Number: {{$invoice->number}} || Invoice Date: {{$invoice->date}}
@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
