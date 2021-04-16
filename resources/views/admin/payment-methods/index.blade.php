@extends('layouts.admin')
@section('title', 'Payment Methods')

@section('content')

    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        Payment Methods
    </h4>
    <div class="card">
        <div class="card-datatable table-responsive">
            @component('admin.components.datatables.index-without-ordering-ability')
                @slot('columns', $columns)
                @slot('ajax_route', route('ajax.datatables.payment-methods'))
            @endcomponent
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function changeStatus(event) {
            event.preventDefault();
            const actionButton = $(event.target);
            const postUrl = actionButton.attr('href');
            swal.fire({
                title: @json(trans('strings.delete_confirmation_title')),
                icon: 'info',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                /*confirmButtonText: 'Apply',
                confirmButtonAriaLabel: 'Apply',
                cancelButtonText: 'Cancel',
                cancelButtonAriaLabel: 'Cancel',*/
                onBeforeOpen: function (ele) {
                    $(ele).find('button.swal2-confirm.swal2-styled')
                        .toggleClass('swal2-styled swal2-confirm btn btn-primary')
                        .attr('style', '');
                    $(ele).find('button.swal2-cancel')
                        .toggleClass('swal2-cancel btn btn-secondary')
                        .attr('style', '');
                },
            }).then((isConfirm) => {
                if (isConfirm.value) {
                    axios.post(postUrl)
                        .then((response) => {
                            showToast(response.data.isSuccess ? 'success' : 'error', response.data.message);
                            if (response.data.isSuccess) {
                                console.log("response.data", response.data);
                                const currentStatus = response.data.currentStatus;
                                // Todo: use the incooooming data.
                                let dropdownButton = actionButton.parent().parent().find('button');
                                dropdownButton.find(".button-text").html(currentStatus['title']);
                                dropdownButton.attr('class', function (i, c) {
                                    return c.replace(/(^|\s)btn-\S+/g, ' btn-' + currentStatus['class']);
                                });
                                // console.log("actionButton.parent()", actionButton.parent());
                            }
                        }, () => {
                            showToast('error', 'Server Error, try later!');
                        });
                }
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
