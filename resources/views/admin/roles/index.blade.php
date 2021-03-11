@extends('layouts.admin')
@section('title','Roles')
@section('content')
    @can('role.permissions.create')
        <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
            {{trans('strings.roles')}}
            <a href="{{ route('admin.roles.create') }}">
                <button type="button" class="btn btn-primary rounded-pill d-block">
                    <span class="ion ion-md-add"></span>
                    &nbsp;
                    {{trans('strings.add')}}
                </button>
            </a>
        </h4>
    @endcan
    {{--Table start--}}
    <div class="card">
        <table class="table card-table">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>{{trans('strings.name')}}</th>
                <th>{{trans('strings.action')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($roles as $key => $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        @can('role.permissions.edit')
                            <a href="{{ route('admin.roles.edit',$role->id) }}"
                               data-toggle="tooltip"
                               title="{{trans('strings.edit')}}">
                                &nbsp;<i class="far fa-edit"></i>&nbsp;
                            </a>
                        @endcan
                        @can('role.permissions.destroy')
                            @if($role->id > 5)
                                    <a href="#!" data-delete data-toggle="tooltip" title="{{trans('strings.delete')}}">
                                        &nbsp;<i class="far fa-trash-alt text-danger"></i>&nbsp;
                                    </a>
                            @endif
                            <form action="{{ route('admin.roles.destroy', $role->id) }}"
                                  method="post" class="delete">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
