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
        <div class="card-header d-none">Deep Links</div>
        <table class="table card-table table-bordered table-striped">
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
                <tr style="height: 70px;">
                    <th scope="row">@{{index + 1}}</th>
                    <td>@{{ item.title }}</td>
                    <td style="overflow-wrap: anywhere;">
                        <form class="form-inline">
                            <div class="form-group mr-4" v-if="item.value.indexOf('id=') != -1">
                                <label>ID</label>&nbsp;
                                <input v-model="item.tempId"
                                       @change="adjustID(index,item)" class="form-control"
                                       placeholder="1">
                            </div>

                            <div class="form-group" v-if="item.value.indexOf('channel=') != -1">
                                <label>Channel</label>&nbsp;
                                <select class="form-control" v-model="item.params.channel"
                                        @change="adjustChannel(index,item)">
                                    <option v-for="(channel,channelIndex) in appChannels" :value="channel.key"
                                            :selected="item.params.channel">
                                        @{{channel.title}}
                                    </option>
                                </select>
                            </div>
                        </form>

                        <code :id="'item-value-'+ index">@{{ item.value }}</code>
                    </td>
                    <td>
                        <button class="clipboard-btn btn btn-link btn-lg p-0 m-0" data-clipboard-action="copy"
                                data-toggle="tooltip" title="@lang('strings.copy_deep_link')"
                                :data-clipboard-target="'#item-value-'+ index">
                            <span class="ion ion-md-copy"></span>&nbsp;
                            {{trans('strings.copy_deep_link')}}
                        </button>


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
            },
            methods: {
                adjustID: function (index, item) {
                    item.value = item.value.replace(/id=\d/, 'id=' + item.tempId);
                    this.adjustTrackers[index] = item;
                },
                adjustChannel: function (index, item) {
                    item.value = item.value.replace(/channel=(grocery|food)/, 'channel=' + item.params.channel);
                    this.adjustTrackers[index] = item;
                },
            },
        })
    </script>

@endpush
