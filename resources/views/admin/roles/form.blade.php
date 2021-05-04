@extends('layouts.admin')
@if(!is_null($role->id))
    @section('title', trans('strings.editing') .' - ' .'('.$role->name.')')
@else
    @section('title', trans('strings.add_new') . ' ' . trans('strings.role'))
@endif

@section('content')
    <div class="mb-4">
        @if(!is_null($role->id))
            <h4>Editing - {{ $role->name }}</h4>
        @else
            <h4>{{trans('strings.add_new') . ' ' . trans('strings.role')}}</h4>
        @endif
    </div>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" enctype="multipart/form-data"
          @if(is_null($role->id))
          action="{{route('admin.roles.store', ['type' => request('type')])}}"
          @else
          action="{{route('admin.roles.update', $role->id)}}"
        @endif>
        {{csrf_field()}}
        @if(!is_null($role->id))
            {{method_field('put')}}
        @endif
        <div class="row mb-4">
            <div class="col-md-12 mt-4">
                <div class="card mb-3">
                    <div class="card-body row">
                        <div class="col-md-12">
                            @php
                                $disabled = ! is_null($role->id) && $role->id <= 5? 'disabled' : '';
                            @endphp
                            @component('admin.components.form-group', ['name' =>'name', 'type' => 'text'])
                                @slot('label', trans('strings.name'))
                                @slot('attributes', ['required', $disabled])
                                @slot('value',$role->name)
                            @endcomponent
                            @if(!empty($disabled))
                                <input type="hidden" name="name" value="{{$role->name}}">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            @foreach($permissions as $permissionName => $group)
                                <div class="col-12">
                                    <div class="card card-hover card-outline-inverse mb-3">
                                        <div class="card-header with-elements">
                                            <span class="card-header-title mr-2">
                                                {{trans('roles.'.$permissionName.'.title')}}
                                            </span>
                                            <div class="card-header-elements ml-md-auto">
                                                <button type="button" class="btn btn-xs btn-outline-success select-all">
                                                    <span class="ion ion-md-checkmark"></span>
                                                    @lang('strings.select_all')
                                                </button>
                                                <button type="button"
                                                        class="btn btn-xs btn-outline-danger deselect-all">
                                                    <span class="ion ion-md-time"></span>
                                                    @lang('strings.deselect_all')
                                                </button>
                                            </div>
                                        </div>


                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($group as $permission)
                                                    <div class="col-6">
                                                        {{trans('roles.'.$permissionName.'.'.str_replace('.','_',$permission))}}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="{{'01_' . str_replace('.','-',$permission)}}"
                                                                   name="permissions[{{$permission}}]"
                                                                   value="1"
                                                                {{$role->hasPermissionTo($permission)? 'checked' : '' }}
                                                            >
                                                            <span class="custom-control-label">@lang('strings.yes')</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="{{'00_' . str_replace('.','-',$permission)}}"
                                                                   value="0"
                                                                {{$role->hasPermissionTo($permission)? '' : 'checked' }}
                                                            >
                                                            <span class="custom-control-label">@lang('strings.no')</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="btn btn-success" type="submit">{{trans('strings.submit')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        $('input:radio').change(
            function () {
                let inputId = $(this).attr('id');
                inputId = inputId.substring(0, 2) == '00' ? inputId.replace('00', '01') : inputId.replace('01', '00');
                $(`#${inputId}`).prop('checked', false);
            });
        $(".select-all").click(function () {
            $(this).parents(".card").find('[value="1"]').prop('checked', true);
            $(this).parents(".card").find('[value="0"]').prop('checked', false);
        });
        $(".deselect-all").click(function () {
            $(this).parents(".card").find('[value="1"]').prop('checked', false);
            $(this).parents(".card").find('[value="0"]').prop('checked', true);
        });
    </script>
@endpush
