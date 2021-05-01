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

        .table-scroll {
            position: relative;
            width: 100%;
            margin: auto;
            display: table;
        }

        .table-wrap {
            width: 100%;
            display: block;
            max-height: 80vh;
            overflow: auto;
            position: relative;
            z-index: 1;
        }

        .table-scroll table {
            width: 100%;
            margin: auto;
            /*border-collapse: separate;*/
            border-spacing: 0;
        }

        .table-scroll th, .table-scroll td {
            padding: 5px 5px;
            border: 1px solid #e8e8e9;
            background: #fff;
            vertical-align: top;
        }

        .hidden-table table {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            pointer-events: none;
        }

        .hidden-table table + table {
            top: auto;
            bottom: 0;
        }

        .hidden-table table tbody, .hidden-table tfoot {
            visibility: hidden;
            border-color: #eee;
        }

        .hidden-table table + table thead {
            visibility: hidden;
            border-color: transparent;
        }

        .hidden-table table + table tfoot {
            visibility: visible;
            border-color: #000;
        }

        .hidden-table thead th {
            color: #fff;
            background-color: rgb(34 35 35);
            border-color: #eee;
        }

        .hidden-table tfoot th, .hidden-table tfoot td {
            color: #4E5155;
            background-color: white;
            border-color: #e8e8e9;
        }

        .hidden-table {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            overflow-y: scroll;
        }

        .hidden-table thead, .hidden-table tfoot, .hidden-table thead th, .hidden-table tfoot th, .hidden-table tfoot td {
            position: relative;
            z-index: 2;
        }
    </style>
@endpush
@push('scripts')
    <script>
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
    </script>
@endpush

