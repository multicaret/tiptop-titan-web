@extends('layouts.admin')

@if(!is_null($city->id))
    @section('title', trans('strings.editing') .' - ' . 'Neighborhoods')
@else
    @section('title', trans('strings.add_new') .' - ' . 'Neighborhood')
@endif

@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/quill/typography.css">
    <link rel="stylesheet" href="/admin-assets/libs/quill/editor.css">
@endpush

@section('content')

    <div class="mb-4">
        @if(!is_null($city->id))
            <h5>Editing Neighborhood - {{ $city->name }}</h5>
        @else
            <h5>{{trans('strings.add_new')}} Neighborhood</h5>
        @endif
    </div>

    <form method="post" enctype="multipart/form-data"
          @if(is_null($city->id))
          action="{{route('admin.cities.store')}}"
          @else
          action="{{route('admin.cities.update', [$city->id])}}"
        @endif
    >
        {{csrf_field()}}
        @if(!is_null($city->id))
            {{method_field('put')}}
        @endif
        <div class="row mb-4">
            <div class="col-12">
                <ul class="nav nav-tabs border-bottom-0">
                    @foreach(localization()->getSupportedLocales() as $key => $locale)
                        <li class="nav-item">
                            <a class="nav-link {{ $key == localization()->getDefaultLocale() ? 'active' : '' }}"
                               data-toggle="tab"
                               href="#title_{{$key}}">
                                <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                <span class="hidden-xs-down">{{$locale->native()}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content card card-outline-inverse">
                    @foreach(localization()->getSupportedLocales() as $langKey => $locale)
                        <div
                            class="tab-pane {{ $langKey == localization()->getDefaultLocale() ? 'active' : '' }}"
                            id="title_{{$langKey}}">
                            <div class="card-body pb-0">
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        @component('admin.components.form-group', ['name' => $langKey .'[name]', 'type' => 'text'])
                                            @slot('label', trans('strings.name'))

                                            @if(! is_null($city->id))
                                                @slot('value', optional($city->translate($langKey))->name)
                                            @endif
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-12">
                                @component('admin.components.form-group', ['name' => 'region_id', 'type' => 'select'])
                                    @slot('label', trans('strings.city'))
                                    @slot('options', $regions->pluck('name', 'id')->prepend('',''))
                                    @slot('attributes', [
                                        'class' => 'select2-regions w-100',
                                        'required',
                                    ])
                                    @slot('selected', $city->region_id)
                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <button class="btn btn-success" type="submit">{{trans('strings.submit')}}</button>
        <input type="hidden" name="unattached-media" class="deleted-file" value="">
    </form>

@endsection

@push('scripts')
    <script src="{{ asset('/admin-assets/libs/quill/quill.js') }}"></script>
    <script src="/admin-assets/libs/select2/select2.js"></script>
    <script>
        $(function () {
            $('.select2-regions').select2({
                placeholder: 'Select Cities',
            });
        });
    </script>

@endpush
