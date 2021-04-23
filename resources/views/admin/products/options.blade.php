@extends('layouts.admin')
@section('title', "{$product->title} Options")

@section('content')
    <h2>
        {{$product->title}} Options
    </h2>

    <livewire:products.product-options-index :product="$product"/>
@endsection

@push('post-live-wire-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    </script>
@endpush
