<form method="POST" action="{{route('admin.branch.apply_discount',$branch->uuid)}}" enctype="multipart/form-data">

<div class="row">
        @csrf
        <div class="col-12  ">
            <div class="form-group">
            @component('admin.components.form-group', ['name' => 'menu_categories[]', 'type' => 'select','class' => 'w-100'])
                @slot('label', 'Menu Categories')
                @slot('attributes', [
                   'class' => 'select2-categories w-100',
                   'multiple'
               ])
                @slot('options', $branch->menuCategories->pluck('title','id')->prepend('',''))
                @slot('selected', $branch->menuCategories)

            @endcomponent
            </div>
        </div>

        <div class="form-group col-6">
            <label
                for="price-discount-began-at">{{trans('strings.discount_begins_at')}}</label>
            <input type="date"
                   id="price-discount-began-at" class="form-control"
                   name="price_discount_began_at"

                   min="{{now()->format('Y-m-d')}}">
            <small class="form-text text-danger">
                @error('price_discount_began_at')
                {{$message}}
                @enderror
            </small>
        </div>
        <div class="form-group col-6">
            <label
                for="price-discount-finished-at">{{trans('strings.discount_ends_at')}}</label>
            <input type="date"
                   id="price-discount-finished-at" class="form-control"
                   name="price_discount_finished_at"
                   min="{{now()->addDay()->format('Y-m-d')}}">
            <small class="form-text text-danger">
                @error('price_discount_finished_at')
                {{$message}}
                @enderror
            </small>
        </div>
    <div class="col-6 form-group">
        @component('admin.components.form-group', ['name' => 'discount_percentage', 'type' => 'number'])
            @slot('label', 'Discount percentage')
        @endcomponent
    </div>

</div>
    <button type="submit" class="btn btn-success">Submit</button>

</form>

@push('styles')
<style>
    .select2-container{
        width: 100% !important;
    }
</style>
@endpush
