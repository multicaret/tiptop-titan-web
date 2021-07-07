@extends('layouts.admin')

@section('title', 'Order #'.$order->reference_code)

@push('styles')
    <link rel="stylesheet" href="/admin-assets/css/pages/chat.css">
    <style>

        /* The Modal (background) */
        #image-viewer {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;

        }
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }
        .modal-content {
            animation-name: zoom;
            animation-duration: 0.3s;
        }
        @keyframes zoom {
            from {transform:scale(0)}
            to {transform:scale(1)}
        }
        #image-viewer .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #FEC63D;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            z-index: 1000;
        }
        #image-viewer .close:hover,
        #image-viewer .close:focus {
            color: rgba(254, 198, 61, 0.42);
            text-decoration: none;
            cursor: pointer;
        }

        @media only screen and (max-width: 700px){
            .modal-content {
                width: 100%;
            }
        }
    </style>

@endpush
@section('content')
    <livewire:orders.jet-order-show :order="$order"/>
@endsection

@push('scripts')
    {{--    @livewireScripts--}}
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://unpkg.com/hotkeys-js/dist/hotkeys.min.js"></script>
    <script type="text/javascript">
        hotkeys('ctrl+r,r', function (event, handler) {
            switch (handler.key) {
                case 'ctrl+r':
                case 'r':
                    window.location.reload();
                    break;
                default:
                    alert(event);
            }
        });

        $(".order-image").click(function(){
            $("#full-image").attr("src", $(this).attr("src"));
            $('#image-viewer').show();
        });

        $("#image-viewer .close").click(function(){
            $('#image-viewer').hide();
        });
        $(document).click(function(event) {
            //if you click on anything except the modal itself or the "open modal" link, close the modal
            if ($(event.target).closest('#image-viewer').length) {
                $('#image-viewer').hide();
            }
        });
    </script>

@endpush
