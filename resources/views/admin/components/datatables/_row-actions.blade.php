@if(isset($statusToggle))
    <button type="button" data-status-toggle="{{ $statusToggleUrl }}"
            class=" {{ ($statusToggle == 1) ? 'active' : '' }}"
            data-toggle="button" aria-pressed="false"
            data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order">

        <i class="ti-check text-active" aria-hidden="true"></i>
        <span class="text-active">{{ $activeStr }}</span>

        <i class="ti-close text" aria-hidden="true"></i>
        <span class="text">{{ $inActiveStr }}</span>
    </button>
@endif
@if(isset($editAction))
    <a href="{{ $editAction }}"
       data-toggle="tooltip"
       title="@lang('strings.edit')">
        &nbsp;<i class="far fa-edit"></i>&nbsp;
    </a>
@endif
@if(isset($showAction))
    <a href="{{ $showAction }}"
       data-toggle="tooltip"
       title="@lang('strings.show')">
        &nbsp;<i class="ti-eye text-dark"></i>&nbsp;
    </a>
@endif
@if(isset($deleteAction))
    <a href="#!" data-delete data-toggle="tooltip" title="@lang('strings.delete')">
        &nbsp;<i class="far fa-trash-alt text-danger"></i>&nbsp;
    </a>
    <form action="{{ $deleteAction }}"
          method="post" class="delete">
        {{ csrf_field() }}
        {{ method_field('delete') }}
    </form>
@endif
