@extends('layouts.admin')
@section('title', 'Daily Reports')
@section('content')

    <div>
        <div class="card border-light">
            <livewire:orders.daily-report/>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet"
          href={{ asset('/admin-assets/libs/bootstrap-table/extensions/sticky-header/sticky-header.css') }}>
@endpush
@push('scripts')
    <script src={{ asset('/admin-assets/libs/bootstrap-table/extensions/sticky-header/sticky-header.js') }}></script>
@endpush

