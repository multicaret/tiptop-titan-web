@extends('layouts.admin')
@section('title', 'Translations')
@section('content')
    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        <div class="d-block">
            {{trans('strings.translations_manager')}}
            <div class="m-3 btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">{{request('group_by', 'All Groups')}}</button>
                <div class="dropdown-menu">
                    @if(!is_null(request('group_by')))
                        <a class="dropdown-item" href="javascript:filterByGroup('*')">All Groups</a>
                        <div class="dropdown-divider"></div>
                    @endif
                    @foreach(\App\Models\Translation::getTranslationGroupsFromFiles() as $groupName)
                        @if(request('group_by') != $groupName)
                            <a class="dropdown-item" href="javascript:filterByGroup('{{$groupName}}')">{{$groupName}}</a>
                            @if(!$loop->last)
                                <div class="dropdown-divider"></div>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>

        </div>

        @if(auth()->user()->id === 1)
            <button type="button" class="btn btn-primary d-block" onclick="loadNewKeys()">
                <span class="ion ion-md-add"></span>&nbsp;
                @lang('strings.load_new_keys')
            </button>
        @endif
    </h4>

    <div class="card">
        <div class="card-datatable table-responsive">
            @component('admin.components.datatables.index')
                @slot('columns', $columns)
                @slot('ajax_route', route('ajax.datatables.translations', request()->all()))
            @endcomponent
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <script src="{{asset('/admin-assets/js/dropdown-hover.js')}}"></script>
    <script src="{{asset('/admin-assets/libs/bootstrap-menu/bootstrap-menu.js')}}"></script>
    <script src="{{asset('/admin-assets/js/ui_dropdowns.js')}}"></script>

    <script>
        function updateTranslations(data) {
            const id = data.id;
            const localeKey = data.localeKey;
            const textAreaValue = $(`#${id}-${localeKey}-input`).val();
            if (textAreaValue.length === 0) return;
            data.value = textAreaValue;
            const postUrl = @json(route('ajax.translation.update'));
            axios.put(postUrl, data)
                .then((response) => {
                    data.defaultValue = textAreaValue;
                    cancelTranslations(data);
                    let labelElement = $(`#${id}-${localeKey}-label`);
                    labelElement.html(textAreaValue);
                    showToast(response.data.isSuccess ? 'info' : 'error', response.data.message);
                    if (response.data.isSuccess) {
                        if (labelElement.hasClass('text-danger')) {
                            labelElement.removeClass('text-danger');
                            labelElement.addClass('text-primary');
                        }
                        $(`#${id}-${localeKey}-container`).attr('x-data', '{open: false}');
                    }
                }, () => {
                    showToast('error', 'Server Error, try later!');
                });
        }

        function cancelTranslations(data) {
            const localeKey = data.localeKey;
            const id = data.id;
            const defaultValue = data.defaultValue;
            $(`#${id}-${localeKey}-input`).val(defaultValue);
        }


        function showAlert(title, message, type) {
            window.swal.fire({
                title: "Response Message",
                text: title,
                type: type,
                confirmButtonText: "OK",
                html: message,
                // cancelButtonText: "Cancel",
                showCancelButton: false,
            }).then(console.log)
                .catch(console.error);
        }

        function replaceUrlParam(url, paramName, paramValue) {
            if (paramValue == null) {
                paramValue = '';
            }
            const pattern = new RegExp('\\b(' + paramName + '=).*?(&|#|$)');
            if (url.search(pattern) >= 0) {
                return url.replace(pattern, '$1' + paramValue + '$2');
            }
            url = url.replace(/[?#]$/, '');
            return url + (url.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue;
        }

        function filterByGroup(groupName) {
            const currentUrl = window.location.href;
            if (groupName !== '*') {
                window.location.href = replaceUrlParam(currentUrl, 'group_by', groupName);
            } else {
                window.location.href = window.location.href.split("?")[0];
            }
        }


        function loadNewKeys() {
            const urlValue = @json(localization()->getLocalizedURL(null,route('ajax.translation.load')));
            axios.get(urlValue)
                .then((response) => {
                    // Todo: translate variables.
                    const title = 'Loaded successfully';
                    const message = response.data.data.html;
                    const type = response.data.isSuccess ? 'info' : 'error';
                    showAlert(title, message, type);
                }, () => {
                    showToast('error', 'Server Error, try later!');
                });
        }
    </script>
@endpush
