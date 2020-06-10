@component('mail::message')
# {{ $data['intromessage'] }}

{!! $data['message'] !!}

@component('mail::button', ['url' => $data['url']])
{{ $data['buttonText'] }}
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
