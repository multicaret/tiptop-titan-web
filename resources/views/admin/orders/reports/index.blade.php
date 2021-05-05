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
    <style>
        thead th {
            text-align: center !important;
            padding-left: 10px
        }

        tbody td:first-child {
            padding-left: 15px
        }

        .table-wrap {
            max-height: 50vh;
            overflow: auto;
            z-index: 1;
        }

        .hidden-thead {
            opacity: 1 !important;
            height: 2px !important;
            font-size: 0;
            border-bottom: 1px solid black;
        }

        .hidden-thead tr,
        .hidden-thead td,
        .hidden-thead th {
            border: unset !important;
        }

        .table-scroll table {
            width: 100%;
            margin: auto;
            /*border-collapse: separate;*/
            border-spacing: 0;
        }

        thead {
            z-index: 1231231312123;
        }

        /*
                .table-scroll th, .table-scroll td {
                    padding: 5px 5px;
                    border: 1px solid #e8e8e9;
                    background: #fff;
                    vertical-align: top;
                }

                .th {
                    visibility: visible;
                    border-color: #000;
                }*/
    </style>
@endpush
@push('scripts')
    {{--<script>
        $(function () {
            let fauxTable = $('#hidden-table')
            let mainTable = $('#main-table')
            let clonedElement = mainTable.clone(true);
            let clonedElement2 = mainTable.clone(true);
            clonedElement.id = "";
            clonedElement2.id = "";
            fauxTable.append(clonedElement);
            fauxTable.append(clonedElement2);
        });
    </script>--}}
@endpush

