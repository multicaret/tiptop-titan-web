@component('mail::message')
    # {{ $sender['name'] }}, Sent you a new message!
    Message info:
    - <strong>Name:</strong> {{ $sender['name'] }}
    - <strong>Email:</strong> {{ $sender['email'] }}
    - <strong>Phone:</strong> {{ $sender['phone'] }}
    <hr>
    ## The Message:
    @component('mail::panel')
        {{ $sender['message'] }}
    @endcomponent

    Best Wishes<br>
    {{ config('app.name') }} Team.
@endcomponent
