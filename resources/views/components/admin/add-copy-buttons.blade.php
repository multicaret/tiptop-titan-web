@php
    if (!isset($showAddButton)) { $showAddButton = true; }
    if (!isset($copyLabelIsBefore)) { $copyLabelIsBefore = false; }
    if(!isset($copyButtonLabel)) { $copyButtonLabel = 'Copy Deep Link';}
@endphp
<div class="d-flex justify-content-between align-items-center">

    @isset($copyContent)
        <button class="clipboard-btn btn {{!empty($copyButtonLabel) ? 'mr-3': ''}} {{isset($classes) ? $classes : 'btn-primary rounded-pill mr-3'}}" data-clipboard-action="copy"
                data-clipboard-text="{{$copyContent}}" data-toggle="tooltip" title="@lang('strings.copy_deep_link')"
                @isset($cutSelector) data-clipboard-target="{{$cutSelector}}" @endisset>
            {{ $copyLabelIsBefore ? $copyButtonLabel: ''}}
            <span class="ion ion-md-{{isset($actionName) ? $actionName : 'copy'}}"></span>&nbsp;
            {{ !$copyLabelIsBefore ? $copyButtonLabel: ''}}
        </button>
    @endisset

    {{$slot}}

    @if ($showAddButton)
        <a href="{{ isset($createRoute) ? $createRoute : '#' }}">
            <button type="button" class="btn btn-primary rounded-pill d-block">
                <span class="ion ion-md-add"></span>
                &nbsp;
                {{isset($addButtonLabel) ? $addButtonLabel : trans('strings.add')}}
            </button>
        </a>
    @endif
</div>
