@extends('layouts.admin')
@section('title', "{$product->title} Options")

@push('styles')
    {{--    @livewireStyles--}}
@endpush

@section('content')
    <h2>
        {{$product->title}} Options
    </h2>


    Ingredients:
    <livewire:product-options-index :product="$product"/>
@endsection
