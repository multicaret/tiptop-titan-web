@component('mail::message')
<h1>{{__('Dear')}} {{ $user['name'] }},</h1>
<p>
{{__('We would like to welcome you to')}} {{ config('app.name') }}
</p>
<br>
{{__('Regards')}}<br>{{ config('app.name') }}
@endcomponent
