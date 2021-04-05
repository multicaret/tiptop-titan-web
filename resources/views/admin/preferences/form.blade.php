@extends('layouts.admin')
@section('title', trans('strings.deep_links'))
@push('styles')
    <style>
        .hide-bottom {
            border-bottom-style: hidden;
        }
    </style>
@endpush
@section('content')

    <div class="card">
        <div class="card-header d-none">Table within card</div>
        <table class="table card-table">
            <colgroup>
                <col span="1" style="width: 5%;">
                <col span="1" style="width: 16%;">
                <col span="1" style="width: 66%;">
                <col span="1" style="width: 13%;">
            </colgroup>
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>url</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <template v-for="(item, index) in adjustTrackers">
                <tr :class="{'hide-bottom': item.deep_link_params}" style="height: 70px;">
                    <th scope="row">@{{index + 1}}</th>
                    <td>@{{ item.title }}</td>
                    <td :id="'item-value-'+ index" style="overflow-wrap: anywhere;">@{{ item.value }}</td>
                    <td>
                        <button class="clipboard-btn btn btn-link btn-lg p-0 m-0" data-clipboard-action="copy"
                                data-toggle="tooltip" title="@lang('strings.copy_deep_link')"
                                :data-clipboard-target="'#item-value-'+ index">
                            <span class="ion ion-md-copy"></span>&nbsp;
                            {{trans('strings.copy_deep_link')}}
                        </button>


                    </td>
                </tr>
                <tr v-if="item.deep_link_params">
                    <td colspan="3">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2 text-sm-right">Param Key</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" disabled placeholder="key"
                                       :id="'key-element-' + index"
                                       v-model="item.deep_link_params[0].key">
                            </div>
                            <label class="col-form-label col-md-2 text-sm-right">Param Value</label>
                            <div class="col-sm-3">
                                <input v-if="item.key !== 'home_screen_by_channel'" type="text" class="form-control"
                                       placeholder="value" :id="'value-element-' + index" @input="updateValue(index)"
                                       v-model="item.deep_link_params[0].value">
                                <multiselect
                                    v-if="item.key === 'home_screen_by_channel'"
                                    :options="appChannels"
                                    v-model="item.deep_link_params[0].value"
                                    track-by="key"
                                    label="title"
                                    select-label=""
                                    selected-label=""
                                    deselect-label=""
                                    placeholder=""
                                    @input="updateValue(index)"
                                    autocomplete="false"
                                ></multiselect>
                            </div>
                        </div>
                        <div class="form-group row">

                        </div>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>

@endsection


@push('scripts')
    <script>
        new Vue({
            el: '#vue-app',
            data: {
                adjustTrackers: @json($adjustTrackers),
                appChannels: @json($appChannels),
                contactDetail: {
                    name: '',
                    email: '',
                    phone: ''
                },
                validationData: [],
                formErrorMessage: null,
                selectedRegion: null,
            },
            mounted() {
            },
            methods: {
                updateValue: function (index) {
                    this.adjustTrackers[index].value = this.getUpdateDeepLinkEncoded(this.adjustTrackers[index]);
                },
                getUpdateDeepLinkEncoded: function (trackerObject) {
                    let updatedParam = {};
                    trackerObject.deep_link_params.map(obj => {
                        updatedParam = {[obj.key]: obj.value.key ? obj.value.key : obj.value};
                    });
                    updatedParam[trackerObject.key] = '';
                    const encodedParams = new URLSearchParams(updatedParam).toString();
                    const encodedString = encodeURIComponent(trackerObject.deep_link + '&' + encodedParams);
                    return trackerObject.url + '?deep_link=' + encodedString;
                },
            },
        })
    </script>

@endpush
