@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/datatables/datatables.css">
@endpush

<table class="datatable-table table table-striped table-bordered">

    <tfoot>
    <tr>
        @foreach($columns as $column)
            <td>
                @if(!isset($column['searchable']))
                    <input type="text" class="form-control" style="width: {{ $column['width'] ?? '100%' }};"
                           value="{{ request( str_replace('.','-', $column['data'] ) ) }}"
                           placeholder="Search...">
                @endif
            </td>
        @endforeach
        <td></td>
    </tr>
    </tfoot>
</table>

@push('scripts')
    {{--    <script src="/admin-assets/libs/datatables/datatables.js"></script>--}}
    {{--    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>--}}
    {{--    <script src="/admin-assets/libs/datatables/datatables.js"></script>--}}
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

    <script>
        @php

            $columns = array_values(array_merge($columns,
            [
                'action'=>
                [
                    'title' => trans('strings.actions'),
                    'data' => 'action',
                    'name' => 'action',
                    'orderable' => false,
                    'searchable' => false,
                    'width' => '5',
                ]
            ]
             ) );

        @endphp

        $('document').ready(function () {

            window.datatable = $('.datatable-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '{{ $ajax_route }}',
                    "type": 'get',
                    "data": @json($route_params??[]),
                },
                createdRow: function (row, data, dataIndex) {
                    $(row).attr('id', 'row-' + dataIndex);
                    $(row).attr('row-id', dataIndex);
                },
                columns: @json($columns),
                iDisplayLength: 25,
                order: [[0, "desc"]],
                columnDefs: [
                    {
                        width: 200,
                        targets: -1,
                        className: 'noVis'
                    }
                ],
                autoWidth: true,
                responsive: true,
                initComplete: function () {
                    this.api().columns().every(function () {
                        let column = this;
                        input = $(column.footer()).children('input');
                        input.on('change', function () {
                            let val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? val : '', true, false).draw();
                        });
                    });
                },
                drawCallback: function () {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        });
    </script>
@endpush
