@extends('layouts.admin')

@section('title', 'Orders')

@push('styles')
    @livewireStyles
@endpush

@section('content')
    <livewire:orders-index/>
@endsection

@push('scripts')
    @livewireScripts
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
    </script>

@endpush
