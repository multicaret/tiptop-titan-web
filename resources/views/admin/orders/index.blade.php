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
@endpush
