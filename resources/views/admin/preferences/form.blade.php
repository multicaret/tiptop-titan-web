@extends('layouts.admin')
@section('title', trans('strings.deep_links'))
@section('content')

    <div class="card">
        <div class="card-header d-none">Table within card</div>
        <table class="table card-table">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in adjustTrackers">
                <th scope="row">@{{index + 1}}</th>
                <td>@{{ item.title }}</td>
                <td>
                    <button class="btn btn-link" data-toggle="tooltip" title="@lang('strings.edit')"
                            @click="editDeepLink(index)">
                        &nbsp;<i class="far fa-edit"></i>&nbsp;
                        @lang('strings.edit')
                    </button>
                </td>
            </tr>
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
                console.log('$adjustTrackers', this.adjustTrackers);
            },
            methods: {
                editDeepLink: function (index) {
                    if (this.adjustTrackers[index]) {
                        console.log('adjustTrackers', this.adjustTrackers[index].value);
                    }
                },
                submitButton(e) {
                    if (this.contactDetails.length) {
                        let validationData = this.validationData;
                        let name = false
                        let phone = false
                        const titleElement = this.$refs['main-form'].elements.namedItem('en[title]');
                        console.log("titleElement");
                        console.log(titleElement);
                        this.contactDetails.forEach(function (element) {
                            name = element.name.length
                            phone = element.phone.length
                        })
                        validationData[0] = {'Name': name};
                        validationData[1] = {'Phone': phone};
                        for (let i = 0; i < validationData.length; i++) {
                            const tmpItem = validationData[i], inputLabel = Object.keys(tmpItem)[0];
                            if (!tmpItem[inputLabel]) {
                                // this.setErrorMessage(`${inputLabel} is required.`);
                                break;
                            }
                        }
                        if (!!this.formErrorMessage) {
                            e.preventDefault();
                        } else {
                            this.$refs['main-form'].submit();
                        }
                    } else {
                        this.$refs['main-form'].submit();
                    }
                },
            },
        })
    </script>

@endpush
