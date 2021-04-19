@extends('layouts.admin')

@section('title', $user->id? trans('strings.editing') .' - ' . trans("strings.$role") : trans(trans('strings.add') . ' - ' . trans("strings.$role")))

@section('breadcrumb')
    <li class="breadcrumb-item"><a
            href="{{route('admin.users.index', ['role' => $roleName ])}}">@lang('strings.users')</a></li>
    <li class="breadcrumb-item active">{{ $user->id? trans('strings.edit'. $roleName): trans('strings.add_new_'. $roleName)}}</li>
@endsection


@section('content')
    <div class="mb-4">
        @if(!is_null($user->id))
            <h5>{{trans('strings.editing'). ' - '. trans("strings.$role")}}</h5>
        @else
            <h5>{{trans('strings.add') . ' - ' . trans("strings.$role")}}</h5>
        @endif
    </div>
    <form method="POST" enctype="multipart/form-data"
          @if(is_null($user->id))
          action="{{route('admin.users.store', ['role' => $role])}}"
          @else
          action="{{route('admin.users.update', [$user, 'role' => $role])}}"
        @endif
    >
        {{csrf_field()}}
        @if(!is_null($user->id))
            {{method_field('put')}}
        @endif



        {{--<div class="row">
            <div class="col-md-12">
                <div class="card card-outline-inverse">
                    <div class="card-header">
                        <h4 class="m-b-0">Essential Details</h4></div>
                    <div class="card-body row">
                                        <h3 class="card-title">Special title treatment</h3>

                    </div>
                </div>
            </div>
        </div>--}}


        <div class="card card-outline-inverse mb-4">
            <h4 class="card-header">
                Details
            </h4>
            <div class="card-body row">
                <div class="col-md-10 row">
                    <div class="col-md-6">
                        @component('admin.components.form-group', ['name' => 'first', 'type' => 'text'])
                            @slot('label', 'First Name')
                            @slot('attributes', ['required'])
                            @slot('value', $user->first)
                        @endcomponent
                    </div>
                    <div class="col-md-6">
                        @component('admin.components.form-group', ['name' => 'last', 'type' => 'text'])
                            @slot('label', 'Last Name')
                            @slot('attributes', ['required'])
                            @slot('value', $user->last)
                        @endcomponent
                    </div>

                    {{--                    todo: Do we have a user title here?--}}
                    {{--<div class="col-md-6">
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="title" class="control-label">Title</label>
                            <input type="text" id="title" name="title" class="form-control"
                                   value="{{ old('title')??$user->title }}">
                            @if ($errors->has('title'))
                                <span class="help-block">
                                    <b>{{ $errors->first('title') }}</b>
                                </span>
                            @endif
                        </div>
                    </div>--}}
                    <div class="col-md-6">
                        @component('admin.components.form-group', ['name' => 'gender', 'type' => 'select'])
                            @slot('label', 'Gender')
                            {{--@slot('attributes', ['required'])--}}
                            @slot('options', [
                                App\Models\User::GENDER_UNSPECIFIED => trans('strings.sex_other'),
                                App\Models\User::GENDER_MALE => trans('strings.sex_male'),
                                App\Models\User::GENDER_FEMALE => trans('strings.sex_female'),
                            ])
                            @slot('selected', $user->gender)
                        @endcomponent
                    </div>
                    @if(in_array($role, \App\Models\User::rolesHaving('branch')))
                        <div class="col-6">
                            @component('admin.components.form-group', ['name' => 'branch_id', 'type' => 'select'])
                                @slot('label', trans('strings.branch'))
                                @slot('options', $branches)
                                @slot('attributes', [
                                    'class' => 'select-2-branch w-100',
                                    'required',
                                ])
                                @slot('selected', $user->branch_id)
                            @endcomponent
                        </div>
                    @endif
                    @if($role == \App\Models\User::ROLE_TIPTOP_DRIVER)
                        <div class="col-6">
                            @component('admin.components.form-group', ['name' => 'employment', 'type' => 'select'])
                                @slot('label', trans('strings.employment'))
                                @slot('options', \App\Models\User::getEmploymentsArray())
                                @slot('attributes', [
                                    'class' => 'select-2-employment w-100',
                                    'required',
                                ])
                                @slot('selected', $user->employment)
                            @endcomponent
                        </div>
                    @endif
                    @if($role == \App\Models\User::ROLE_TIPTOP_DRIVER)
                        <div class="col-{{$user->id ? '4' : '6'}}">
                            @component('admin.components.form-group', ['name' => 'team_id', 'type' => 'select'])
                                @slot('label', trans('strings.captain_teams'))
                                @slot('options', $teams->prepend('',''))
                                @slot('attributes', [
                                    'class' => 'select-2-captain_team w-100',
                                    'allowClear' => true,
                                    'required',
                                ])
                                @slot('selected', $user->team_id)
                            @endcomponent
                        </div>
                        @if($user->id)
                            <div class="col-md-2">
                                @component('admin.components.form-group', ['name' => 'tookan_id', 'type' => 'number'])
                                    @slot('label', 'Captain ID')
                                    @slot('value', $user->tookan_id)
                                    @slot('attributes', [
                                    'disabled'
                                ])
                                @endcomponent
                            </div>
                        @endif
                    @endif
                    <div class="col-md-6">
                        @component('admin.components.form-group', ['name' => 'status', 'type' => 'select'])
                            @slot('label', 'Status')
                            @slot('options', [
                                App\Models\User::STATUS_INACTIVE => 'Inactive',
                                App\Models\User::STATUS_ACTIVE => 'Active',
                            ])
                            @slot('selected', $user->status)
                        @endcomponent
                    </div>
                </div>
                <div class="col-12 col-lg-2">
                    <h5>Avatar</h5>
                    @component('admin.components.form-group', ['name' => 'avatar', 'type' => 'file'])
                        @slot('attributes', [
                            'class' => 'cover-uploader',
                            'accept' => '.jpg, .jpeg, .png, .bmp',
                            'dropzone' => 'media-list',
                            'data-fileuploader-listInput' => 'media-list',
                            'data-fileuploader-extensions' => 'jpg, jpeg, png, bmp',
                            'data-fileuploader-files' => json_encode($user->getMediaForUploader('avatar'), JSON_UNESCAPED_UNICODE),
                        ])
                    @endcomponent
                </div>
            </div>
        </div>
        <div class="card card-outline-inverse mb-4">
            <h4 class="card-header">
                Login Details
            </h4>
            <div class="card-body row">

                <div class="col-md-12">
                    @component('admin.components.form-group', ['name' => 'email', 'type' => 'email'])
                        @slot('label', 'Primary Email')
                        @slot('attributes', ['required'])
                        @slot('value', $user->email)
                    @endcomponent
                </div>

                <div class="col-md-6">
                    @component('admin.components.form-group', ['name' => 'password', 'type' => 'password'])
                        @slot('label', 'Password')
                        @if(Route::currentRouteName() == 'admin.users.create')
                            @slot('attributes', ['required'])
                        @endif
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-group', ['name' => 'password_confirmation', 'type' => 'password'])
                        @slot('label', 'Confirm Password')
                        @if(Route::currentRouteName() == 'admin.users.create')
                            @slot('attributes', ['required'])
                        @endif
                    @endcomponent
                </div>
            </div>
        </div>
        {{--<div class="card card-outline-inverse mb-4">
            <h4 class="card-header">
                Contact Details
            </h4>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label class="control-label">
                                @lang('strings.country')
                            </label>
                            <multiselect
                                :options="countries"
                                v-model="user.country"
                                track-by="name"
                                label="name"
                                :searchable="true"
                                :allow-empty="true"
                                select-label=""
                                selected-label=""
                                deselect-label=""
                                placeholder=""
                                @select="retrieveRegions"
                                autocomplete="false"
                            ></multiselect>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label class="control-label">
                                @lang('strings.region')
                            </label>
                            <multiselect
                                :options="regions"
                                v-model="user.region"
                                track-by="name"
                                label="name"
                                :searchable="true"
                                :allow-empty="true"
                                select-label=""
                                selected-label=""
                                deselect-label=""
                                placeholder=""
                                @select="retrieveCities"
                                autocomplete="false"
                            ></multiselect>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label class="control-label">
                                @lang('strings.city')
                            </label>
                            <multiselect
                                :options="cities"
                                v-model="user.city"
                                track-by="name"
                                label="name"
                                :searchable="true"
                                :allow-empty="true"
                                select-label=""
                                selected-label=""
                                deselect-label=""
                                placeholder=""
                                --}}{{--@select="retrieveNeighborhoods"--}}{{--
                                autocomplete="false"
                            ></multiselect>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group {{ $errors->has('address1') ? 'has-error' : '' }}">
                            <label for="address1" class="control-label">Address 1</label>
                            <input type="text" id="address1" name="address1" class="form-control"
                                   value="{{ old('address1')??optional($user->defaultAddress)->address1 }}">
                            @if ($errors->has('address1'))
                                <span class="help-block">
                                        <b>{{ $errors->first('address1') }}</b>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group {{ $errors->has('address2') ? 'has-error' : '' }}">
                            <label for="address2" class="control-label">Address 2</label>
                            <input type="text" id="address2" name="address2" class="form-control"
                                   value="{{ old('address2')??optional($user->defaultAddress)->address2 }}">
                            @if ($errors->has('address2'))
                                <span class="help-block">
                                        <b>{{ $errors->first('address2') }}</b>
                                    </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <div class="form-group">
                            <label for="" class="">
                                Phones:
                            </label>
                            <div v-for="(phone,index) in phones"
                                 :class="{'input-group mb-3':true, 'has-danger' :!isValidPhone(phone.value)}">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                            data-toggle="dropdown">
                                        @{{ phone.option.name }}
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" v-for="phoneOption in phoneOptions"
                                            @click="phone.option = phoneOption" type="button">
                                            @{{ phoneOption.name }}
                                        </button>
                                    </div>
                                </div>
                                <input type="text"
                                       :class="{'form-control':true,'form-control-danger': !isValidPhone(phone.value)}"
                                       placeholder=""
                                       v-model="phone.value">
                                <div class="input-group-append">
                                    <button class="btn btn-block btn-danger" type="button"
                                        @click="removePhone(index)">
                                        <i class="ti-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="00123456789"
                                       v-model="newPhone.value">
                                <div class="input-group-append">
                                    <button class="btn btn-sm d-inline-block btn-outline-success" type="button"
                                        @click="addPhone"
                                            :disabled="newPhone.value == '' || !isValidPhone(newPhone.value)">
                                        <i class="ti-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                            <small class=" text-danger"
                                   v-show="newPhone.value != '' && !isValidPhone(newPhone.value)">
                                Invalid Phone
                            </small>
                        </div>
                    </div>
                    <div class="offset-1"></div>
                    <div class="col-5">
                        <div class="form-group">
                            <label for="" class="">
                                Emails:
                            </label>
                            <div v-for="(email,index) in emails"
                                 :class="{'input-group mb-3':true, 'has-danger' :!isValidEmail(email.value)}">
                                <input type="text"
                                       :class="{'form-control':true,'form-control-danger': !isValidEmail(email.value)}"
                                       placeholder=""
                                       v-model="email.value">
                                <div class="input-group-append">
                                    <button class="btn btn-block btn-danger" type="button"
                                        @click="removeEmail(index)">
                                        <i class="ti-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="example@domain.com"
                                       v-model="newEmail.value">
                                <div class="input-group-append">
                                    <button class="btn btn-sm d-inline-block btn-outline-success" type="button"
                                        @click="addEmail"
                                            :disabled="newEmail.value == '' || !isValidEmail(newEmail.value)">
                                        <i class="ti-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                            <small class=" text-danger"
                                   v-show="newEmail.value != '' && !isValidEmail(newEmail.value)">
                                Invalid Email
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}


        {{--<div class="row">
            <div class="col-md-10 row">

                <div class="col-md-12">
                     @component('admin.components.form-group', ['name' => 'role', 'type' => 'select'])
                         @slot('label', 'Role')
                         @slot('attributes', ['required'])
                         @slot('options', [
                             \App\Models\User::ROLE_USER => trans('strings.'. \App\Models\User::ROLE_USER),
                             \App\Models\User::ROLE_EDITOR => trans('strings.'. \App\Models\User::ROLE_EDITOR),
                             \App\Models\User::ROLE_ADMIN => trans('strings.'. \App\Models\User::ROLE_ADMIN),
                         ])
                     @endcomponent
                 </div>
            </div>
        </div>--}}

        {{--        Cedit said they didn't want any user-specific permissions--}}
        {{--@role('Super')
        <div id="accordion">
            <div class="card mb-2">
                <a class="text-body" data-toggle="collapse" href="#accordion-1">
                    <h4 class="card-header">{{trans('strings.permissions')}}</h4>
                </a>

                <div id="accordion-1" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            @foreach($permissions as $permissionName => $group)
                                <div class="col-12">
                                    <div class="card card-hover card-outline-inverse mb-3 permission-accordion">
                                        <div class="card-header with-elements">
                                            <span class="card-header-title mr-2">
                                                {{trans('roles.'.$permissionName.'.title')}}
                                            </span>
                                            <div class="card-header-elements ml-md-auto">
                                                <button type="button" class="btn btn-xs btn-outline-success select-all">
                                                    <span class="ion ion-md-checkmark"></span>
                                                    Select All
                                                </button>
                                                <button type="button"
                                                        class="btn btn-xs btn-outline-danger deselect-all">
                                                    <span class="ion ion-md-time"></span>
                                                    Deselect All
                                                </button>
                                                <button type="button"
                                                        class="btn btn-xs btn-outline-warning inherit-all">
                                                    <span class="ion ion-md-time"></span>
                                                    Inherit All
                                                </button>
                                            </div>
                                        </div>


                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($group as $permission)
                                                    <div class="col-6">
                                                        {{trans('roles.'.$permissionName.'.'.str_replace('.','_',$permission))}}
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="{{'01_' . str_replace('.','-',$permission)}}"
                                                                   name="permissions[{{$permission}}]"
                                                                   value="1"
                                                                {{$user->hasDirectPermission($permission) || $user->hasPermissionTo($permission)? 'checked' : '' }}
                                                            >
                                                            <span class="custom-control-label">Yes</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="{{'00_' . str_replace('.','-',$permission)}}"
                                                                   value="0"
                                                                {{ $user->hasPermissionTo($permission) || !$user->hasDirectPermission($permission) ? '' : 'checked' }}
                                                            >
                                                            <span class="custom-control-label">No</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="{{'02_' . str_replace('.','-',$permission)}}"
                                                                   value="2"
                                                                {{$user->hasPermissionTo($permission)? 'checked': 'disabled' }}
                                                            >
                                                            <span class="custom-control-label">Inherit</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole--}}
        <div class="ml-1">
            @if(is_null($user->id))
                @component('admin.components.form-group', ['name' => 'send_notification', 'type' => 'checkbox'])
                    @slot('label', 'Send the new user an email about their account.')
                @endcomponent
            @endif
        </div>
        <button class="btn btn-success" type="submit">@lang('strings.submit')</button>


        <input type="hidden" name="emails" :value="JSON.stringify(emails)">
        <input type="hidden" name="phones" :value="JSON.stringify(phones)">
        <input type="hidden" name="country_id" :value="JSON.stringify(user.country.id)">
        <input type="hidden" name="region_id" :value="JSON.stringify(user.region.id)">
        <input type="hidden" name="city_id" :value="JSON.stringify(user.city.id)">

    </form>

@endsection


@push('scripts')
    <script>
        $('input:radio').change(
            function () {
                let inputId = $(this).attr('id');
                let inputId1;
                if (inputId.substring(0, 2) === '00') {
                    inputId = inputId.replace('00', '01');
                    inputId1 = inputId.replace('01', '02');
                } else if (inputId.substring(0, 2) === '01') {
                    inputId = inputId.replace('01', '00');
                    inputId1 = inputId.replace('00', '02');
                } else if (inputId.substring(0, 2) === '02') {
                    inputId = inputId.replace('02', '00');
                    inputId1 = inputId.replace('00', '01');
                }
                $(`#${inputId}`).prop('checked', false);
                $(`#${inputId1}`).prop('checked', false);
            });
        $(".select-all").click(function () {
            $(this).parents(".permission-accordion").find('[value="1"]').prop('checked', true);
            $(this).parents(".permission-accordion").find('[value="0"]').prop('checked', false);
            $(this).parents(".permission-accordion").find('[value="2"]').prop('checked', false);
        });
        $(".deselect-all").click(function () {
            $(this).parents(".permission-accordion").find('[value="1"]').prop('checked', false);
            $(this).parents(".permission-accordion").find('[value="0"]').prop('checked', true);
            $(this).parents(".permission-accordion").find('[value="2"]').prop('checked', false);
        });
        $(".inherit-all").click(function () {
            $(this).parents(".permission-accordion").find('[value="1"]').prop('checked', false);
            $(this).parents(".permission-accordion").find('[value="0"]').prop('checked', false);
            $(this).parents(".permission-accordion").find('[value="2"]').prop('checked', true);
        });
    </script>
    {{--<script>
        new Vue({
            el: '#vue-app',
            data: {
                user: @json($user),
                countries: @json($countries),
                regions: @json($regions),
                cities: @json($cities),
                emails: [
                    {value: 'foo@bar.com', valid: true},
                    {value: 'foo2@bar2.com', valid: true},
                ],
                newEmail: {value: '', valid: false},
                phoneOptions: [
                    {name: 'Mobile', value: 'Mobile'},
                    {name: 'WhatsApp Mobile', value: 'whatsapp-mobile'},
                    {name: 'Work Phone', value: 'work-phone'},
                    {name: 'Work Mobile', value: 'work-mobile'},
                    {name: 'Home Phone', value: 'home-phone'},
                ],
                phones: [
                    {value: '00123123', valid: true, option: {name: 'Work Phone', value: 'work-phone'}},
                    {value: '00123123', valid: true, option: {name: 'Work Mobile', value: 'work-mobile'}},
                ],
                newPhone: {value: '', valid: false, option: {name: 'Mobile', value: 'mobile'}},
            },
            methods: {
                addEmail: function () {
                    if (this.isValidEmail(this.newEmail.value)) {
                        this.newEmail.valid = true;
                        this.emails.push(this.newEmail);
                        this.newEmail = {value: '', valid: false};
                    }
                },
                removeEmail: function (index) {
                    this.emails.splice(index, 1);
                },
                isValidEmail: function (value) {
                    return /^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!\.)){0,61}[a-zA-Z0-9]?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!$)){0,61}[a-zA-Z0-9]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/.test(value);
                },
                addPhone: function () {
                    if (this.isValidPhone(this.newPhone.value)) {
                        this.newPhone.valid = true;
                        this.phones.push(this.newPhone);
                        this.newPhone = {value: '', valid: false, option: this.phoneOptions[0]};
                        console.log("this.newPhone", this.newPhone);
                    }
                },
                removePhone: function (index) {
                    this.phones.splice(index, 1);
                },
                isValidPhone: function (value) {
                    return /^[0]{2}|[+]/.test(value);
                },
                retrieveRegions: function (country) {
                    axios.post(window.App.domain + `/ajax/countries/${country.id}/regions`)
                        .then((res) => {
                            this.regions = res.data;
                            this.user.region_id = res.data.length > 1 ? this.regions[0] : null;
                        });
                },
                retrieveCities: function (region) {
                    axios.post(window.App.domain + `/ajax/countries/${region.country_id}/regions/${region.id}/cities`)
                        .then((res) => {
                            this.cities = res.data;
                        });
                },
                /*retrieveNeighborhoods: function (city) {
                    axios.post(window.App.domain + `/ajax/countries/${city.country_id}/regions/${city.region_id}/cities/${city.id}/neighborhoods`)
                        .then((res) => {
                            this.neighrboods = res.data;
                        });
                },*/
            },

        })
    </script>--}}

    <script src="/admin-assets/libs/select2/select2.js"></script>
    <script>
        $(function () {
            $('.select-2-branch').select2({
                placeholder: 'Select Branch',
            });
            $('.select-2-employment').select2({
                placeholder: 'Select Employment Type',
            });
            $('.select-2-captain_team').select2({
                placeholder: 'Select Team',
            });
            $('.select-2-status').select2({
                placeholder: 'Select Status',
            });
        });
    </script>
@endpush
