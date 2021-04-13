<div class="chat-wrapper">

    <!-- Make card full height of `.chat-wrapper` -->
    <div class="card2 flex-grow-1 position-relative overflow-hidden">

        <!-- Make row full height of `.card` -->
        <div class="row no-gutters h-100">
            <div class="d-flex col flex-column">
            {{--
            <!-- Chat header -->
            <div class="flex-grow-0 py-3 pr-4 pl-lg-4">

                <div class="media align-items-center">
                    <a href="javascript:void(0)"
                       class="chat-sidebox-toggler d-lg-none d-block text-muted text-large px-4 mr-2"><i
                            class="ion ion-md-more"></i></a>

                    <div class="position-relative">
                        <span class="badge badge-dot badge-success indicator"></span>
                        <img src="{{url('images/user-avatar.png')}}" class="ui-w-40 rounded-circle" alt="">
                    </div>
                    <div class="media-body pl-3">
                        <strong>{{$auth->name}}</strong>
--}}{{--                            <div class="text-muted small"><em>Typing...</em></div>--}}{{--
                    </div>
                    --}}{{--<div>
                        <button type="button" class="btn btn-primary rounded-pill icon-btn mr-1"><i
                                class="ion ion-ios-call"></i></button>
                        <button type="button" class="btn btn-secondary rounded-pill icon-btn mr-1"><i
                                class="ion ion-md-videocam"></i></button>
                        <button type="button" class="btn btn-default rounded-pill icon-btn"><i
                                class="ion ion-ios-more"></i></button>
                    </div>--}}{{--
                </div>

            </div>
            <hr class="flex-grow-0 border-light m-0">
            <!-- / Chat header -->
--}}
            <!-- Chat footer -->
                <div class="flex-grow-0 py-3 px-4">
                    <form wire:submit.prevent="addNewNote">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Type your message"
                                   wire:model="note">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </div>
                        @error('note') <span class="error">{{$message}}</span> @enderror

                    </form>
                </div>
                <hr class="border-light m-0">
                <!-- / Chat footer -->
                <!-- Wrap `.chat-scroll` to properly position scroll area. Remove this wtapper if you don't need scroll -->
                <div class="flex-grow-1 position-relative">
                    `
                    <!-- Remove `.chat-scroll` and add `.flex-grow-1` if you don't need scroll -->
                    <div class="chat-messages chat-scroll p-4 ps">
                        @forelse($selectedOrder->agentNotes()->get() as $note)
                            <div
                                class="{{$note->agent->id == $auth->id?'chat-message-right':'chat-message-left'}} mb-4">
                                <div>
                                    <img src="{{$note->agent->avatar}}" class="ui-w-40 rounded-circle"
                                         alt="{{$note->agent->name}}">
                                    <div class="text-muted small text-nowrap mt-2">
                                        {{ $note->created_at->format(config('defaults.time.normal_format')) }}
                                    </div>
                                </div>
                                <div class="flex-shrink-1 bg-lighter rounded py-2 px-3 mr-3">
                                    <div class="font-weight-semibold mb-1">
                                        @if($note->agent->id == $auth->id)
                                            You
                                        @else
                                            {{$note->agent->name}}
                                        @endif

                                    </div>
                                    {{ $note->message }}
                                </div>
                            </div>
                        @empty
                            <h4 class="text-center text-muted"><i>No Notes</i></h4>
                        @endforelse


                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                        </div>
                        <div class="ps__rail-y" style="top: 0px; right: 0px;">
                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                        </div>
                    </div>
                    <!-- / .chat-messages -->
                </div>


            </div>
        </div><!-- / .row -->

    </div><!-- / .card -->

</div>
