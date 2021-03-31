@php
    $length = Str::length($order->rating_comment);
    $isLong = $length > 50;
@endphp

<a href="!#" data-toggle="modal" data-target="#modals-{{$order->reference_code}}">
    {{\Str::limit($order->rating_comment, 80) }}
</a>

@if($isLong)
    <i class="far fa-comment-dots"></i>
@endif


<div class="modal fade" id="modals-{{$order->reference_code}}">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{$order->user->name}}'s
                    <span class="font-weight-light">Comment</span>
                    <br>
                </h5>
                <p>Order Number: <span class="font-weight-bold">{{$order->reference_code}}</span></p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col">
                        <p>{{$order->rating_comment}}</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
