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
                        @component('admin.components.form-group', ['name' => 'phone', 'type' => 'tel'])
                            @slot('label', 'Phone')
                            @slot('attributes', ['required'])
                            @slot('value', $user->phone_number)
                        @endcomponent
                    </div>
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

                    @if(in_array($role, \App\Models\User::rolesHaving('branches')))
                        <div class="col-6">
                            @component('admin.components.form-group', ['name' => 'branches[]', 'type' => 'select'])
                                @slot('label', trans('strings.branches'))
                                @slot('options', $branches)
                                @slot('attributes', [
                                    'multiple',
                                    'required',
                                    'class' => 'select2-branches w-100',
                                ])
                                @slot('selected', $menuCategoryData['hasBranch'] ?  $menuCategoryData['branchId'] : ($user->id? $user->branches($role): []))
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
        @if($role == \App\Models\User::ROLE_USER)
            <div class="card card-outline-inverse mb-4">
                <h4 class="card-header">Addresses</h4>
                <div class="card-body">
                    <div class="row">
                        @if(!empty($user->addresses->count()))
                            @foreach($user->addresses as $address)
                                <div class="col-4">
                                    <div class="card mb-3 border-light">
                                        <div class="card-body">
                                            <div class="">
                                                <h4 class="card-title d-inline">{{is_null($address->alias) ? 'Empty' : $address->alias}}</h4>
                                                <div
                                                    class="text-muted d-inline float-right">{{$address->kindName}}
                                                </div>
                                                <hr>
                                            </div>
                                            @php
                                                $empty = '<p class="card-text text-muted d-inline">Empty</p>'
                                            @endphp

                                            @foreach(['City'=> optional($address->region)->name,
                                                    'Neighborhood'=>optional($address->city)->name,
                                                    'Address'=>$address->address1, 'Directions'=>$address->notes,
                                                    'Latitude'=>$address->latitude, 'Longitude'=>$address->longitude]
                                                    as $name => $var)
                                                <div class="card-text mb-2">
                                                    {{$name}}
                                                    : {!!empty($var) ? $empty : $var!!}
                                                </div>
                                            @endforeach
                                            <a href="{{ route('admin.users.addresses.edit', ['user' => $user, 'address' => $address]) }}">
                                                <button type="button"
                                                        class="btn btn-primary btn-sm rounded-pill d-block float-right">
                                                    {{trans('strings.edit')}}
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-4 text-muted">
                                User has no addresses
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
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
    <script src="/admin-assets/libs/select2/select2.js"></script>
    <script>
        $(function () {
            $('.select2-branches').select2({
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
