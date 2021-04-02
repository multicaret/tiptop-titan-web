@extends('layouts.admin')

@section('title', 'Orders')

@push('styles')
    @livewireStyles
@endpush

@section('content')

    <livewire:counter/>

    <livewire:orders-index/>
@endsection

@push('scripts')
    @livewireScripts
@endpush
