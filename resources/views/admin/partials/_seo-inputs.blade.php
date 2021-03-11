@if(is_null($model->meta))
   {{dd('use "HasMetaData" trait')}}
@endif
@push('styles')
    <style>
        .hide-me[aria-expanded="true"] {
            display: none;
        }
    </style>
@endpush
<a class="btn btn-link hide-me" data-toggle="collapse" href="#collapse-seo-inputs" role="button" aria-expanded="false"
   aria-controls="collapse-seo-inputs">
    Click here to modify SEO data
</a>
<div class="seo-inputs w-100 collapse" id="collapse-seo-inputs">
    <div class="col-md-12">
        @component('admin.components.form-group', ['name' => 'meta['.$localeKey .'][meta_title]', 'type' => 'text'])
            @slot('label', trans('meta_title'))
            @slot('value', optional($model->meta->translate($localeKey))->meta_title)
        @endcomponent
    </div>
    <div class="col-md-12">
        @component('admin.components.form-group', ['name' => 'meta['.$localeKey .'][meta_description]', 'type' => 'textarea'])
            @slot('label', trans('meta_description'))
            @slot('value', optional($model->meta->translate($localeKey))->meta_description)
        @endcomponent
    </div>
    {{-- OpenGraph --}}
    <div class="col-md-6">
        @component('admin.components.form-group', ['name' => 'meta['.$localeKey .'][og_title]', 'type' => 'text'])
            @slot('label', trans('open_graph_title'))
            @slot('value', optional($model->meta->translate($localeKey))->og_title)
        @endcomponent
    </div>
    <div class="col-md-6">
        @component('admin.components.form-group', ['name' => 'meta['.$localeKey .'][og_type]', 'type' => 'text'])
            @slot('label', trans('open_graph_type'))
            @slot('value', optional($model->meta->translate($localeKey))->og_type)
        @endcomponent
    </div>
    <div class="col-md-12">
        @component('admin.components.form-group', ['name' => 'meta['.$localeKey .'][og_description]', 'type' => 'textarea'])
            @slot('label', trans('open_graph_description'))
            @slot('value', optional($model->meta->translate($localeKey))->og_description)
        @endcomponent
    </div>
    {{-- Twitter --}}
    <div class="col-md-6">
        @component('admin.components.form-group', ['name' => 'meta['.$localeKey .'][twitter_title]', 'type' => 'text'])
            @slot('label', trans('twitter_title'))
            @slot('value', optional($model->meta->translate($localeKey))->og_title)
        @endcomponent
    </div>
    <div class="col-md-6">
        @component('admin.components.form-group', ['name' => 'meta['.$localeKey .'][twitter_card]', 'type' => 'text'])
            @slot('label', trans('twitter_card'))
            @slot('value', optional($model->meta->translate($localeKey))->og_type)
        @endcomponent
    </div>
    <div class="col-md-12">
        @component('admin.components.form-group', ['name' => 'meta['.$localeKey .'][twitter_description]', 'type' => 'textarea'])
            @slot('label', trans('twitter_description'))
            @slot('value', optional($model->meta->translate($localeKey))->og_description)
        @endcomponent
    </div>
</div>
