@extends('layouts.admin')

@if(!is_null($team->id))
    @section('title', trans('strings.editing') .' - ' . trans('strings.captain_teams'))
@else
    @section('title', trans('strings.add_new_captain_teams'))
@endif

@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/quill/typography.css">
    <link rel="stylesheet" href="/admin-assets/libs/quill/editor.css">
@endpush

@section('content')

    <div class="mb-4">
        @if(!is_null($team->id))
            <h5>Editing Team - {{ $team->name }}</h5>
        @else
            <h5>{{trans('strings.add_new')}} {{trans('strings.captain_teams')}}</h5>
        @endif
    </div>

    <form method="POST" enctype="multipart/form-data"
          @if(is_null($team->id))
          action="{{route('admin.teams.store')}}"
          @else
          action="{{route('admin.teams.update', $team)}}"
        @endif
    >
        {{csrf_field()}}
        @if(!is_null($team->id))
            {{method_field('put')}}
        @endif
        <div class="row mb-4">
            <div class="col-md-12 mt-4">
                <div class="card card-outline-inverse">
                    <div class="card-body row">
                        <div class="col-md-8">
                            @component('admin.components.form-group', ['name' => 'name', 'type' => 'text'])
                                @slot('label', trans('strings.name'))
                                @if(! is_null($team->id))
                                    @slot('value', $team->name)
                                @endif
                            @endcomponent
                        </div>
                        <div class="col-md-4">
                            @component('admin.components.form-group', ['name' => 'tokan_team_id', 'type' => 'text'])
                                @slot('label', 'Tookan ID')
                                @slot('attributes', ['disabled'])
                                @if(! is_null($team->id))
                                    @slot('value', $team->tokan_team_id)
                                @endif
                            @endcomponent
                        </div>
                        <div class="col-md-12">
                            @component('admin.components.form-group', ['name' => 'description', 'type' => 'textarea'])
                                @slot('label', 'Description')
                                @slot('value', $team->description)
                            @endcomponent
                        </div>
                        <div class="col-4">
                            @component('admin.components.form-group', ['name' => 'status', 'type' => 'select'])
                                @slot('label', trans('strings.status'))
                                @slot('options', \App\Models\TokanTeam::getStatusesArray())
                                @slot('selected', $team->status)
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-success" type="submit">{{trans('strings.submit')}}</button>
    </form>

@endsection

@push('scripts')
    <script src="{{ asset('/admin-assets/libs/quill/quill.js') }}"></script>
@endpush
