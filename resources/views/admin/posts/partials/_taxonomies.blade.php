@push('styles')
    <link rel="stylesheet" href="/admin-assets/libs/bootstrap-select/bootstrap-select.css">
    <link rel="stylesheet" href="/admin-assets/libs/select2/select2.css">
@endpush

@push('scripts')
    <script src="/admin-assets/libs/select2/select2.js"></script>
    <script>
        // Select2
        $(function () {
            $('.select2-demo').each(function () {
                $(this)
                    .wrap('<div class="position-relative"></div>')
                    .select2({
                        placeholder: 'Select a tag',
                        dropdownParent: $(this).parent()
                    });
            })
        });
    </script>
@endpush
<div class="row">
    <div class="col-12">
        <div class="card card-outline-inverse">
            <h4 class="card-header">Taxonomies</h4>
            <div class="card-body">

                <label>Category</label>
                @foreach($categories->pluck('title', 'id') as $id => $category)

                    <label class="form-check">
                        <input class="form-check-input" type="radio" id="category-{{$id}}"
                               value="{{$id}}" name="category_id"
                            {{ $id == $post->category_id || $id == old('category_id') ? 'checked':''}}>
                        <label class="form-check-label" for="category-{{$id}}">
                            {{$category}}
                        </label>
                    </label>
                @endforeach

                <br>
                <hr>
                {{--@component('admin.components.form-group', ['name' => 'category_id', 'type' => 'select'])
                    @slot('label', 'Categories')
                    @slot('options', $post->categories->pluck('title', 'id'))
                    @slot('attributes',[
                        'data-select-two',
                        'class' => 'form-group w-100',
                        'data-ajax--url' => route('ajax.search.taxonomies', ['type' => 'category']),
                        'data-ajax--method' => 'GET',
                    ])
                @endcomponent--}}

                @component('admin.components.form-group', ['name' => 'tags[]', 'type' => 'select'])
                    @slot('label', 'Tags')
                    @slot('options', $tags->pluck('title', 'id'))
                    @slot('attributes', [
                        'multiple',
                        'class' => 'select2-demo m-b-10 w-100',
                    ])
                    @slot('selected',$post->tags)
                @endcomponent
            </div>
        </div>
    </div>
</div>
