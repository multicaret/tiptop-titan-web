@extends('layouts.admin')
@section('title', 'Orders')
@push('styles')
    <style>
        .tableFixHeadX {
            overflow: auto;
            /*height: 500px;*/
            max-height: 80vh;
        }

        .tableFixHeadX thead th {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        /* Just common table stuff. Really. */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px 16px;
        }

        th {
            background: #eee;
        }
    </style>
@endpush
@section('content')

    <div class="mb-5">
        <div class="card border-light">
            <div class="tableFixHeadX">
                <table class="table table-sm card-table table-bordered table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th class="width-100">Date</th>
                        <th class="width-65">T-Orders</th>
                        <th class="width-65">Delivered</th>
                        <th class="width-65">AVG D.T</th>
                        <th class="width-65">AOV</th>
                        <th class="width-65">9-12</th>
                        <th class="width-65">12-15</th>
                        <th class="width-65">15-18</th>
                        <th class="width-65">18-21</th>
                        <th class="width-65">21-00</th>
                        <th class="width-65">00-03</th>
                        <th class="width-65">03-09</th>
                        <th class="width-65">N.U</th>
                        <th class="width-65">%N.U.O</th>
                        <th class="width-65">%iOS</th>
                        <th class="width-65">%Android</th>
                        <th class="width-65">%Web</th>
                        <th class="width-65">%Mobile</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($orders)
                        @forelse($orders as $order)
                            <tr>
                                <td>{{$order->id}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <h4>
                                        No items found!
                                    </h4>
                                </td>
                            </tr>
                        @endforelse
                    @endif
                    </tbody>
                </table>
            </div>
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

