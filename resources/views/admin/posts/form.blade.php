@extends('layouts.admin')

@section('title', $post->id ? trans('strings.'. $typeName.' Edit') : trans('strings.add new '. $typeName) )

@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/quill/typography.css">
    <link rel="stylesheet" href="/admin-assets/libs/quill/editor.css">
@endpush

@section('content')

    <div class="mb-4">
        @if(!is_null($post->id))
            <h5>Editing {{ trans('strings.'. $typeName) }} - {{ $post->title }}</h5>
        @else
            <h5>Add New {{ trans('strings.'. $typeName) }}</h5>
        @endif
    </div>

    <form method="POST" enctype="multipart/form-data"
          @if(is_null($post->id))
          action="{{route('admin.posts.store', ['type' => strtolower($typeName)])}}"
          @else
          action="{{route('admin.posts.update', [$post->uuid, 'type' => strtolower($typeName)])}}"
        @endif
    >
        @csrf
        @if(!is_null($post->id))
            {{method_field('put')}}
        @endif

        @php
            $doesNotHaveContent = [];
            $hasCover = [
                \App\Models\Post::TYPE_ARTICLE,
                \App\Models\Post::TYPE_PORTFOLIO,
                \App\Models\Post::TYPE_TESTIMONIAL_USER,
            ];
            $hasGallery = [
                \App\Models\Post::TYPE_PORTFOLIO,
            ];
            $hasTaxonomies = [
                \App\Models\Post::TYPE_ARTICLE,
                \App\Models\Post::TYPE_PORTFOLIO,
            ];
            $hasExcerpt = [
                \App\Models\Post::TYPE_ARTICLE,
                \App\Models\Post::TYPE_TESTIMONIAL_USER,
            ];
            $hasNotes = [
                \App\Models\Post::TYPE_ARTICLE,
            ];
        @endphp
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
                            <div class="card-body">
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        @component('admin.components.form-group', ['name' => $langKey .'[title]', 'type' => 'text'])
                                            @if($type === \App\Models\Post::TYPE_FAQ)
                                                @slot('label', 'Question')
                                            @elseif($type === \App\Models\Post::TYPE_TESTIMONIAL_USER)
                                                @slot('label', 'Full Name')
                                            @else
                                                @slot('label', 'Title')
                                            @endif

                                            @if($langKey == localization()->getDefaultLocale())
                                                @slot('attributes', ['required'])
                                            @endif

                                            @if(! is_null($post->id))
                                                @slot('value', optional($post->translate($langKey))->title)
                                            @endif
                                        @endcomponent
                                    </div>

                                    @if(!in_array($type, $doesNotHaveContent))
                                        <div class="col-12">
                                            <x-admin.textarea :id="$langKey.'-content'" :name="$langKey.'[content]'"
                                                              :label="$type === \App\Models\Post::TYPE_FAQ ? 'Answer' : 'Content'"
                                                              height="20rem"
                                                              :content="optional($post->translate($langKey))->content"/>
                                        </div>
                                    @endif

                                    @if(in_array($type, $hasExcerpt))
                                        <div class="col-md-12">
                                            <x-admin.textarea :id="$langKey.'-excerpt'" :name="$langKey.'[excerpt]'"
                                                              label="Excerpt"
                                                              :content="optional($post->translate($langKey))->excerpt"/>
                                        </div>
                                    @endif

                                    @if(in_array($type, $hasNotes))
                                        <div class="col-12">
                                            @include('admin.posts.partials._notes')
                                        </div>
                                    @endif
                                    @include('admin.partials._seo-inputs', ['model' => $post, 'localeKey' => $langKey])
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if(in_array($type, $hasCover) || in_array($type, $hasGallery))
                <div class="{{ in_array($type,$hasTaxonomies) ? 'col-md-8' : 'col-md-12' }} mt-4">
                    <div class="card card-outline-inverse">
                        <h4 class="card-header">Images</h4>
                        <div class="card-body">
                            @if(in_array($type, $hasCover))
                                <h5>Cover</h5>
                                @component('admin.components.form-group', ['name' => 'cover', 'type' => 'file'])
                                    @slot('attributes', [
                                        'class' => 'cover-uploader',
                                        'accept' => '.jpg, .jpeg, .png, .bmp',
                                        'dropzone' => 'media-list',
                                        'data-fileuploader-listInput' => 'media-list',
                                        'data-fileuploader-extensions' => 'jpg, jpeg, png, bmp',
                                        'data-fileuploader-files' => json_encode($post->getMediaForUploader('cover'), JSON_UNESCAPED_UNICODE),
                                    ])
                                @endcomponent
                            @endif
                            @if(in_array($type, $hasGallery))
                                <h5>Gallery</h5>
                                @component('admin.components.form-group', ['name' => 'gallery', 'type' => 'file'])
                                    @slot('attributes', [
                                        'label'=> 'Gallery',
                                        'class' => 'images-uploader',
                                        'accept' => '.jpg, .jpeg, .png, .bmp',
                                        'dropzone' => 'media-list',
                                        'data-fileuploader-listInput' => 'media-list',
                                        'data-fileuploader-extensions' => 'jpg, jpeg, png, bmp',
                                        'data-fileuploader-files' => !is_null($post->id) && !is_null($gallery = $post->getMediaForUploader('gallery'))? json_encode($gallery, JSON_UNESCAPED_UNICODE) : null,
                                    ])
                                @endcomponent
                            @endif
                        </div>
                    </div>
                </div>
            @endif


            @if(in_array($type,$hasTaxonomies))
                <div
                    class="{{ in_array($type, $hasCover) || in_array($type, $hasGallery) ? 'col-md-4' : 'col-md-12' }} mt-4">
                    @include('admin.posts.partials._taxonomies')
                </div>
            @endif
        </div>

        <button class="btn btn-success" type="submit">Submit</button>
        <input type="hidden" name="unattached-media" class="deleted-file" value="">
    </form>

@endsection

@push('scripts')
    <script src="{{ asset('/admin-assets/libs/quill/quill.js') }}"></script>
@endpush
