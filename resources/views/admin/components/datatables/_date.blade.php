<i class="lnr lnr-calendar-full"></i> &nbsp;
<span data-toggle="tooltip"
      title="{{$date->format(config('defaults.date.normal_format'))}}&nbsp;-&nbsp;{{$date->format(config('defaults.time.normal_format'))}}">
    {{$date->ago()}}
</span>
