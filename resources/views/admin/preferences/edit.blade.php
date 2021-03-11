@extends('layouts.admin')
@section('title', __('Preferences.index'))
@section('content')
    <h4 class="font-weight-bold py-3 mb-4">
        {{__('All Preferences')}}
        <small class="mt-2 d-block text-muted">Your website configs</small>
    </h4>

    <div class="col-xl-12">
        <div class="nav-tabs-left mb-4">
            <ul class="nav nav-tabs">
                @foreach($sections as $sectionIndex => $section)
                    <li class="nav-item text-nowrap">
                        <a class="nav-link {{$section->id == $currentSection->id ? 'active':''}}"
                           href="{{route('admin.preferences.edit',$section)}}" role="tab">
                                <span>
                                    <i class="{{$section->icon}}"></i>
                                </span>
                            &nbsp;{{ $section->key }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach($sections as $sectionIndex => $section)
                    <div class="tab-pane {{$section->id == $currentSection->id ? 'active show':''}}">
                        <form action="{{route('admin.preferences.store')}}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="card-body">
                                {{--localization tabs--}}
                                <ul class="nav nav-tabs mb-4">
                                    @foreach(localization()->getSupportedLocales() as $langKey => $locale)
                                        @if(localization()->getSupportedLocales()->count() > 1)
                                            <li class="nav-item">
                                                <a class="nav-link {{ $langKey == localization()->getDefaultLocale() ? 'active' : '' }}"
                                                   data-toggle="tab"
                                                   href="#tab_{{$sectionIndex}}_title_{{$langKey}}">
                                                        <span class="hidden-sm-up">
                                                            <i class="ti-home"></i>
                                                        </span>
                                                    <span class="hidden-xs-down">
                                                            {{$locale->native()}}
                                                        </span>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul><!-- /.nav-tabs -->

                                <div class="tab-content tabcontent-border">
                                    @foreach(localization()->getSupportedLocales() as $langKey => $locale)
                                        <div id="tab_{{$sectionIndex}}_title_{{$langKey}}"
                                             class="tab-pane {{ $langKey == localization()->getDefaultLocale() ? 'active' : '' }}">

                                            @foreach($children as $preference)
                                                @component('admin.components.form-group', ['name' => $langKey .'['. $preference->key .']', 'type' => $preference->type])
                                                    @slot('attributes', [
                                                        'dir' => $locale->direction() == 'rtl' && in_array($preference->type,['url','tel','email'])? 'ltr' : $locale->direction()
                                                    ])
                                                    @slot('label', trans('preferences.'.$preference->key))
                                                    @slot('value', optional($preference->translate($langKey))->value)
                                                    @if($preference->type == 'file')
                                                        @php($file = $preference->getValue())
                                                        <img src="{{ $file }}" width="200" alt="{{$file}}"
                                                             class="img-thumbnail d-block mb-3">
                                                        <a class="btn btn-outline-info btn-sm"
                                                           target="_blank" href="{{ $file }}">
                                                            View
                                                        </a>
                                                        <a class="btn btn-outline-success btn-sm"
                                                           target="_blank" href="{{ $file }}" download>
                                                            Download
                                                        </a>
                                                    @endif
                                                    @if($preference->notes)
                                                        @lang('preferences.'. $preference->notes )
                                                    @endif
                                                @endcomponent
                                            @endforeach

                                            <div class="form-group">
                                                <button class="btn btn-primary" type="submit">
                                                    {{__('Submit')}}
                                                </button>
                                            </div>

                                        </div>
                                    @endforeach
                                </div><!-- /.tab-content of localization-->
                                {{--end of localization tabs--}}
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


@endsection
