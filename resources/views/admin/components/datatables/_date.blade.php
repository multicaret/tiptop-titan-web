<i class="lnr lnr-calendar-full"></i> &nbsp;
<span data-toggle="tooltip"
      title="{{$date->ago()}} at {{$date->format(config('defaults.time.normal_format'))}}">
    {{$date->format(config('defaults.date.short_format'))}}
</span>
