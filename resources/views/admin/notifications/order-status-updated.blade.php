<a href="javascript:void(0)"
   class="list-group-item list-group-item-action media d-flex align-items-center">
    <div class="ui-icon ui-icon-sm ion ion-md-home bg-secondary border-0 text-white"></div>
    <div class="media-body line-height-condenced ml-3">
        <div class="text-body">{{$notification->created_at}}</div>
        <div class="text-light small mt-1">
            Aliquam ex eros, imperdiet vulputate hendrerit et.
        </div>
        <div class="text-light small mt-1">{{$notification->created_at->ago()}}</div>
    </div>
</a>
