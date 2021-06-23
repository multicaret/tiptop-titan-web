@component('mail::message')
    @component('mail::panel')
        <h2 style="text-align: center;color: rgba(41,51,81,0.87)">{{$newRecord->day->toDateString()}}</h2>
        <span style="font-size: smaller"> New Record has been Made </span> <span style="font-weight: bold;color: #FEC63D">{{$newRecord->orders_count}} order  🎉🎊🎉🎉🎊🎉</span>. <br><br>
        <span style="font-size: smaller">Previous Record: </span> <span style="color: #FEC63D">{{$previousRecord->orders_count}} order </span> <span style="font-size: smaller"> {{$previousRecord->day->diffForHumans()}}</span>. <br><br>
        <?php
        $increase = $newRecord->orders_count - (int) $previousRecord->orders_count;
        $increasePercentage = ($increase / (int) $previousRecord->orders_count) *100
        ?>
        <span style="font-size: smaller">Difference :</span> <span style="font-weight: bold;color: #FEC63D">{{number_format($increasePercentage,1)}} %</span>
    @endcomponent

    Best Wishes
    {{ config('app.name') }} Team.
@endcomponent
