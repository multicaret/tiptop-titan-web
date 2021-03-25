@php
    $expiresAt = Str::kebab($expires_at->diffForHumans(\Carbon\Carbon::now()));
@endphp
<div>
    @if($expires_at->isBefore(\Carbon\Carbon::now()))
        <a class="badge badge-danger text-white" data-toggle='tooltip' title="{{$expiresAt}}">Expired</a>
    @else
        <a class="badge badge-info text-white" data-toggle='tooltip' title="{{$expiresAt}}">Scheduled</a>
    @endif
</div>
