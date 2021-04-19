@php
    $length = Str::length($order->rating_comment);
    $isLong = $length > 50;
@endphp

<a href="!#" data-toggle="modal" data-target="#modals-{{$order->reference_code}}">
    {{\Str::limit($order->rating_comment, 80, '') }}
    @if($isLong)
        <i class="far fa-comment-dots" style="color: #1e70cd"></i>
    @endif
</a>


<div class="modal fade" id="modals-{{$order->reference_code}}">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header px-0">
                <div class="container">
                    <div class="d-flex justify-content-around align-center h-100">
                        {{--Left side--}}
                        <div class="w-100 d-flex flex-column align-items-start">
                            <span class="font-weight-bold mb-2">
                                Order ID: {{$order->user->id}}
                            </span>
                            <span class="font-weight-bold mb-2">
                                User: {{$order->user->name}}
                            </span>
                            <span class="font-weight-bold mb-2">Branch: {{$order->branch->title}}</span>
                        </div>
                        {{--Right side--}}
                        <div class="w-100 d-flex flex-column align-items-end">
                            <span class="font-weight-bold mb-2 float-right">
                                Order Reference Code: {{$order->reference_code}}
                            </span>
                            <span class="font-weight-bold mb-2 float-right" title="{{$order->rated_at}}">
                                Date:
                                {{optional($order->rated_at)->format(config('defaults.date.normal_format'))}}
                            </span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col">
                        <div class="d-flex justify-content-around mb-3">
                            <span class="font-weight-bold float-right">
                                Issue: {{$order->ratingIssue->title}}
                            </span>
                            @include('admin.components.datatables._rating', ['rating' => $order->branch_rating_value])
                        </div>

                        <p>{{$order->rating_comment}}</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
