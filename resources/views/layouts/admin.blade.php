@extends('layouts.admin-master')
@section('title', 'Dashboard')
@section('container')
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <!-- Layout sidenav -->
        @include('admin.partials._sidenav')

        <!-- Layout container -->
            <div class="layout-container">
                <!-- Layout navbar -->
            @include('admin.partials._navbar')

            <!-- Layout content -->
                <div class="layout-content">

                    <!-- Content -->
                    <div class="container-fluid flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Layout footer -->
                    @include('admin.partials._footer')
                </div>
                <!-- Layout content -->

            </div>
            <!-- / Layout container -->

        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
@endsection
