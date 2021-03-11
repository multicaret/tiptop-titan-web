<div x-data="{ open: false}" id="{{$id.'-'.$localeKey}}-container">
    <a @click="open = true" class="mt-2 {{$is_empty ? 'text-danger' : 'text-primary'}} translations-link" x-show="!open"
       id="{{$id.'-'.$localeKey}}-label">{{$is_empty ? 'Empty' : $value}}</a>

    <div class="row" x-show="open" @click.away="open = false">
        <textarea class="form-control col-7" id="{{$id.'-'.$localeKey}}-input" rows="1">{{$value}}</textarea>
        <button type="button" @click='updateTranslations(@json(['id' => $id, 'localeKey' => $localeKey]))'
                class="m-1 py-1 btn btn-primary btn-xs col">
            <i class="fas fa-check" aria-hidden="true"></i>
        </button>
        <button @click='open = false;cancelTranslations(@json(['id' => $id, 'localeKey' => $localeKey, 'defaultValue' => $value]))'
                type="button" class="m-1 py-1 btn btn-dark btn-xs col">
            <i class="far fa-times-circle" aria-hidden="true"></i>
        </button>
    </div>
</div>
