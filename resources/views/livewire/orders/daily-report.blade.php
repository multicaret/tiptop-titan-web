<div>
    <style>
        .width-180 {
            /*width: 180px !important;*/
        }

        .width-65 {
            width: 65px !important;
        }

        thead th {
            text-align: center !important;
        }
    </style>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-3">
                <label class="form-label" for="date-from">Channel</label>
                <select wire:model="channel" class="form-control">
                    <option value="both">Both</option>
                    <option value="grocery">Grocery</option>
                    <option value="food">Food</option>
                </select>
            </div>
            <div class="form-group col-3">
                <label class="form-label" for="date-from">Region</label>
                <select wire:model="regionId" class="form-control">
                    <option value="all">All</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-3">
                <label class="form-label" for="date-from">Date from</label>
                <input type="date" class="form-control" id="date-from"
                       placeholder="" wire:model.lazy="dateFrom">
            </div>
            <div class="form-group col-3">
                <label class="form-label" for="date-to">Date to</label>
                <input type="date" class="form-control" id="date-to"
                       placeholder="" wire:model.lazy="dateTo">
            </div>
        </div>
    </div>

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
                <th class="width-180">
                    <i class="fas fa-calendar-alt"></i>&nbsp;
                                                       Day
                </th>
                <th class="width-65">
                    <i class="fas fa-box-open"></i>
                    <small class="d-block"><sub>Orders</sub></small>
                </th>
                <th class="width-65">
                    <i class="fas fa-check-double"></i>
                    <small class="d-block"><sub>Delivered</sub></small>
                </th>
                <th class="width-65">
                    <i class="fas fa-stopwatch"></i>
                    <small class="d-block"><sub>Avg. Delivery</sub></small>
                    {{--                    Avg. D.T--}}
                </th>
                <th class="width-65">
                    <i class="fas fa-shopping-basket"></i>
                    <small class="d-block"><sub>Avg. Cart</sub></small>
                    {{--                    AOV--}}
                </th>
                <th class="width-65 bg-windows-darker">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>9-12</sub></small>
                </th>
                <th class="width-65 bg-windows-darker">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>12-15</sub></small>
                </th>
                <th class="width-65 bg-windows-darker">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>15-18</sub></small>
                </th>
                <th class="width-65 bg-windows-darker">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>18-21</sub></small>
                </th>
                <th class="width-65 bg-windows-darker">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>21-00</sub></small>
                </th>
                <th class="width-65 bg-windows-darker">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>00-03</sub></small>
                </th>
                <th class="width-65 bg-windows-darker">
                    <i class="far fa-clock"></i>
                    <small class="d-block"><sub>03-09</sub></small>
                </th>
                <th class="width-65">
                    <i class="fas fa-users"></i>
                    <small class="d-block"><sub>T. USERS</sub></small>
                </th>
                <th class="width-65">
                    <i class="fas fa-user-check"></i>
                    <small class="d-block"><sub>ORDERS</sub></small>
                    {{--                    %N.U.O--}}
                </th>
                <th class="width-65">
                    <i class="fab fa-apple"></i>
                    <small class="d-block"><sub>iOS</sub></small>
                </th>
                <th class="width-65">
                    <i class="fab fa-android"></i>
                    <small class="d-block"><sub>ANDROID</sub></small>
                </th>
                <th class="width-65">
                    <i class="fas fa-mobile-alt"></i>
                    <small class="d-block"><sub>T.MOBILE</sub></small>
                </th>
                <th class="width-65">
                    <i class="fas fa-desktop"></i>
                    <small class="d-block"><sub>WEB</sub></small>
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
                        @php
                            if($channel == 'grocery') {
                                $report->total_orders_count = $report->total_grocery_orders_count;
                            }elseif($channel == 'food') {
                                $report->total_orders_count = $report->total_food_orders_count;
                            }
                        @endphp
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
                        @php
                            if($channel == 'grocery') {
                                $report->total_delivered_orders_count = $report->total_delivered_grocery_orders_count;
                            }elseif($channel == 'food') {
                                $report->total_delivered_orders_count = $report->total_delivered_food_orders_count;
                            }
                        @endphp
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
                    <td colspan="18" class="text-center p-3">
                        <h4 class="m-0">
                            <i>No items found!</i>
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