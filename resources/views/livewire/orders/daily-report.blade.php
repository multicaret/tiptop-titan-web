<div>
    <style>
        .width-180 {
            /*width: 180px !important;*/
        }

        .width-65 {
            /*width: 65px !important;*/
        }

        thead th {
            text-align: center !important;
        }
    </style>
    <div class="tableFixHeadX">
        <table class="table table-sm card-table table-bordered table-hover">
            <colgroup>
                <col style="width:15%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:8%">
                <col style="width:8%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
            </colgroup>
            <thead class="thead-dark">
            <tr>
                <th class="width-180">Day</th>
                <th class="width-65">Orders</th>
                <th class="width-65">Delivered</th>
                <th class="width-65">
                    Avg.
                    <i class="fas fa-stopwatch"></i>
{{--                    Avg. D.T--}}
                </th>
                <th class="width-65">
                    Avg.&nbsp;
                    <i class="fas fa-shopping-basket"></i>
{{--                    AOV--}}
                </th>
                <th class="width-65">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>9-12</sub></small>
                </th>
                <th class="width-65">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>12-15</sub></small>
                </th>
                <th class="width-65">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>15-18</sub></small>
                </th>
                <th class="width-65">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>18-21</sub></small>
                </th>
                <th class="width-65">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>21-00</sub></small>
                </th>
                <th class="width-65">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>00-03</sub></small>
                </th>
                <th class="width-65">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>03-09</sub></small>
                </th>
                <th class="width-65">
                    <i class="far fa-user"></i>
{{--                    N.U--}}
                </th>
                <th class="width-65">
                    <i class="far fa-user"></i>
                    <small class="d-block"><sub>ORDERS</sub></small>
{{--                    %N.U.O--}}
                </th>
                <th class="width-65">
                    <i class="fab fa-apple"></i>
                    {{--                    iOS--}}
                </th>
                <th class="width-65">
                    <i class="fab fa-android"></i>
                    {{--                    Android--}}
                </th>
                <th class="width-65">
                    <i class="fas fa-mobile-alt"></i>
{{--                    Mobile--}}
                </th>
                <th class="width-65">
                    <i class="fas fa-desktop"></i>
{{--                    Web--}}
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse($dailyReports as $report)
                <tr>
                    <td>
                        <small class="text-muted d-block">
                            <sub>{{$report->day->format('l')}}</sub>
                        </small>
                        @if($report->day->dayOfWeek === \Carbon\Carbon::FRIDAY)
                            <span class="badge badge-dot badge-primary"></span>
                        @else
                            <span class="badge badge-dot badge-success"></span>
                        @endif
                        {{$report->day->toDateString()}}
                    </td>
                    <td>
                        {{$report->total_orders_count}}
                        @if($report->is_peak_of_this_month)
                            <div class="spinner-grow spinner-grow-sm text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        @endif
                        @if($report->is_nadir_of_this_month)
                            <div class="spinner-grow spinner-grow-sm text-danger" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">
                            ({{$report->total_delivered_orders_count}})
                        </small>
                        {{\App\Http\Controllers\Controller::percentageInRespectToTwoNumbers($report->total_delivered_orders_count,$report->total_orders_count).'%'}}
                    </td>
                    <td>
                        {{$report->average_delivery_time}}
                        <sub class="text-muted" style="font-size: 9px">minute</sub>
                    </td>
                    <td>
                        {!! \App\Models\Currency::formatHtml($report->average_orders_value) !!}
                    </td>
                    <td>{{$report->orders_count_between_09_12}}</td>
                    <td>{{$report->orders_count_between_12_15}}</td>
                    <td>{{$report->orders_count_between_15_18}}</td>
                    <td>{{$report->orders_count_between_18_21}}</td>
                    <td>{{$report->orders_count_between_21_00}}</td>
                    <td>{{$report->orders_count_between_00_03}}</td>
                    <td>{{$report->orders_count_between_03_09}}</td>
                    <td>{{$report->registered_users_count}}</td>
                    <td>
                        <small class="text-muted">
                            ({{$report->ordered_users_count}})
                        </small>
                        {{\App\Http\Controllers\Controller::percentageInRespectToTwoNumbers($report->ordered_users_count,$report->registered_users_count).'%'}}
                    </td>
                    <td>
                        <small class="text-muted">
                            ({{$report->ios_devices_count}})
                        </small>
                        {{\App\Http\Controllers\Controller::percentageInRespectToTwoNumbers($report->ios_devices_count,$report->total_mobile_users_count).'%'}}
                    </td>
                    <td>
                        <small class="text-muted">
                            ({{$report->android_devices_count}})
                        </small>
                        {{\App\Http\Controllers\Controller::percentageInRespectToTwoNumbers($report->android_devices_count,$report->total_mobile_users_count).'%'}}
                    </td>
                    <td>
                        <small class="text-muted">
                            ({{$report->total_mobile_users_count}})
                        </small>
                        {{\App\Http\Controllers\Controller::percentageInRespectToTwoNumbers($report->total_mobile_users_count,$report->registered_users_count).'%'}}
                    </td>
                    <td>
                        <small class="text-muted">
                            ({{$report->total_web_users_count}})
                        </small>
                        {{\App\Http\Controllers\Controller::percentageInRespectToTwoNumbers($report->total_web_users_count,$report->registered_users_count).'%'}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">
                        <h4>
                            No items found!
                        </h4>
                    </td>
                </tr>
            @endforelse
            </tbody>
            <tfoot class="thead-white">
            <tr>
                <th>AVG Last Week:</th>
                @for ($i = 0; $i < 17; $i++)
                    <th>Data</th>
                @endfor
            </tr>
            <tr>
                <th>AVG Last Month:</th>
                @for ($i = 0; $i < 17; $i++)
                    <th>Data</th>
                @endfor
            </tr>
            <tr>
                <th>Weekdays' AVG:</th>
                @for ($i = 0; $i < 17; $i++)
                    <th>Data</th>
                @endfor
            </tr>
            <tr>
                <th>Weekends' AVG</th>
                @for ($i = 0; $i < 17; $i++)
                    <th>Data</th>
                @endfor
            </tr>
            <tr>
                <th>Total Orders</th>
                @for ($i = 0; $i < 17; $i++)
                    <th>Data</th>
                @endfor
            </tr>
            </tfoot>
        </table>
    </div>

    {{--<div class="tableFixHeadX">
        <table class="table table-sm card-table table-bordered table-hover">
            <colgroup>
                <col style="width:15%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
                <col style="width:5%">
            </colgroup>
            <tfoot class="thead-white">
            <tr>
                <td class="width-180">AVG Last 7 Days:</td>
                @for ($i = 0; $i < 17; $i++)
                    <td class="width-65">Data</td>
                @endfor
            </tr>
            <tr>
                <td class="width-180">AVG Last 31 Day:</td>
                @for ($i = 0; $i < 17; $i++)
                    <td class="width-65">Data</td>
                @endfor
            </tr>
            <tr>
                <td class="width-180">Weekdays' AVG:</td>
                @for ($i = 0; $i < 17; $i++)
                    <td class="width-65">Data</td>
                @endfor
            </tr>
            <tr>
                <td class="width-180">Weekend AVG</td>
                @for ($i = 0; $i < 17; $i++)
                    <td class="width-65">Data</td>
                @endfor
            </tr>
            <tr>
                <td class="width-180">Total Orders</td>
                @for ($i = 0; $i < 17; $i++)
                    <td class="width-65">Data</td>
                @endfor
            </tr>
            </tfoot>
        </table>
    </div>--}}
</div>
