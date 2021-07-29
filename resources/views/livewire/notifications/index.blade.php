<div class="row">
    <div class="col-md-12 {{--offset-2--}}">
        <div class="card mb-4">
            <div class="card-header">All notifications</div>
            @foreach($notifications as $notification)
                <div class="list-group-item border-top-0">
                    @include('admin.notifications.'. \Illuminate\Support\Str::kebab(last(explode('\\',$notification->type))))
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-12 d-flex justify-content-center">
        {{$notifications->links()}}
    </div>
</div>
