@component('admin.components.form-group', ['name' => $langKey .'[notes]', 'type' => 'textarea'])
    @slot('label', 'Notes')
    @if(! is_null($post->id))
        @slot('value', optional($post->translate($langKey))->notes)
    @endif
    @slot('attributes' , [
      'rows' => '2',
    ])
@endcomponent
