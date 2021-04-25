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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function changeItemStatus(event, modelId = null) {
            event.preventDefault();
            const actionButton = $(event.target);
            const postUrl = actionButton.attr('href');
            const url = new URL(postUrl);
            const oldStatus = url.searchParams.get("status");
            axios.post(postUrl)
                .then((response) => {
                    showToast(response.data.isSuccess ? 'success' : 'error', response.data.message);
                    if (response.data.isSuccess) {
                        const currentStatus = response.data.currentStatus;
                        let dropdownButton = actionButton.parent().parent().find('button');
                        dropdownButton.find(".button-text").html(currentStatus['title']);
                        dropdownButton.attr('class', function (i, c) {
                            return c.replace(/(^|\s)btn-\S+/g, ' btn-' + currentStatus['class']);
                        });
                        {{--if (modelId && currentStatus.id === @json(\App\Models\Post::STATUS_INACTIVE)) {--}}
                        {{--    $(`#model-id-${modelId}`).hide();--}}
                        {{--    $(`#edit-action-button-id-${modelId}`).hide();--}}
                        {{--} else {--}}
                        {{--    $(`#model-id-${modelId}`).show();--}}
                        {{--    $(`#edit-action-button-id-${modelId}`).show();--}}
                        {{--}--}}
                        {{--if(oldStatus == '1') {--}}
                        {{--    $(`#btn-status-${modelId}-1`).addClass('d-none');--}}
                        {{--    $(`#btn-status-${modelId}-0`).removeClass('d-none');--}}
                        {{--} else {--}}
                        {{--    $(`#btn-status-${modelId}-0`).addClass('d-none');--}}
                        {{--    $(`#btn-status-${modelId}-1`).removeClass('d-none');--}}
                        {{--}--}}
                    }
                }, () => {
                    showToast('error', 'Server Error, try later!');
                });
        }

        function syncChain(syncUrl) {
            axios.get(syncUrl)
                .then((response) => {
                    if (response.data.isSuccess) {
                        $(`#${response.data.uuid}`).hide();
                    }
                    showToast(response.data.isSuccess ? 'success' : 'error', response.data.message);
                }, () => {
                    showToast('error', 'Server Error, try later!');
                });
        }

    </script>
@endpush
