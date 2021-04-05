@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/datatables/datatables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.7/css/rowReorder.dataTables.min.css">
    <style>
        .large-icon > [class^="ion-"]::before, .large-icon >  [class*=" ion-"]::before {
            font-size: 2em;
        }
        .medium-icon > [class^="ion-"]::before, .medium-icon >  [class*=" ion-"]::before {
            font-size: 1.5em;
        }
    </style>
@endpush

<table class="datatable-table table table-striped table-bordered">

    <tfoot>
    <tr>
        <td></td>
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
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

    <script
        src="https://cdn.datatables.net/rowreorder/1.2.7/js/dataTables.rowReorder.min.js"></script>

    <script>
        @php

            array_unshift($columns, [
                            'title' => '<i class="fas fa-arrows-alt-v"></i>',
                            'data' => 'order_column',
                            'name' => 'order_column',
                            'orderable' => false,
                            'searchable' => false,
                            'className' => 'reorder',
                            'targets' => '0',
                            'width' => '5',
            ]);

            $foodTypeName = \App\Models\Taxonomy::getCorrectTypeName(\App\Models\Taxonomy::TYPE_FOOD_CATEGORY, false);
            $isArticleType = request('type') === \App\Models\Post::getCorrectTypeName(\App\Models\Post::TYPE_ARTICLE, false);
            $isFoodType = request('type') === $foodTypeName ;
            $columns = array_values(array_merge($columns,
            [
                'action'=>
                [
                    'title' => trans('strings.actions'),
                    'data' => 'action',
                    'name' => 'action',
                    'orderable' => false,
                    'searchable' => false,
                    'width' => $isFoodType || $isArticleType ? '100' : '10',
                ]
            ]
             ) );

        @endphp

        /*$('document').ready(function () {
            window.datatable = $('.datatable-table').DataTable();
            window.datatable.fnDestroy();
        });*/

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
                rowReorder: {
                    selector: 'td:first-child',
                    dataSrc: 'order_column'
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
            // window.datatable.rowReordering();
            window.datatable.on('row-reorder', function (e, diff, edit) {
                    // let result = 'Reorder started on row: ' + edit.triggerRow.data()[1] + '<br>';
                    let positions = Array();
                    for (let i = 0, ien = diff.length; i < ien; i++) {
                        let rowData = window.datatable.row(diff[i].node).data();
                        /*console.log("diff[i]diff[i]diff[i]");
                        console.log(diff[i]);*/
                        let data = diff[i].node.baseURI.split('?')[0];
                        data = data.split("/").splice(-1)['0'];
                        if (rowData.id != undefined && diff[i].newData != undefined) {
                            positions.push({
                                id: rowData.id,
                                order_new_value: diff[i].newPosition + 1,
                                model_name: data,
                            });

                            // result += rowData.title + ' updated to be in position ' +
                            //     diff[i].newData + ' (was ' + diff[i].oldData + ')<br>';
                        }
                    }
                    positions = positions.filter((item) => item != null);
                    if (positions.length) {
                        const reorderUrl = '{{localization()->getLocalizedURL(null,route('ajax.datatables.reorder'))}}';
                        axios.post(reorderUrl, {
                            positions: positions,
                        });
                    }
                }
            );
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

        function showToast(type, message) {
            window.toast.fire({
                icon: type,
                type: type,
                title: message,
            });
        }
    </script>
@endpush
