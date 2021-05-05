<?php

namespace App\Http\Livewire\Orders;

use App\Models\OrderDailyReport;
use App\Models\Region;
use Carbon\Carbon;
use Livewire\Component;

class DailyReport extends Component
{
    public $dateFrom;
    public $dateTo;
    public $regionId;
    public $channel = 'both';

    public function updatedChannel($newValue)
    {
        [
            $dailyReports, $lastWeekAvg, $lastMonthAvg, $weekDaysAvg, $weekendsAvg, $totalOrders, $regions
        ] = $this->getAll();

        return view('livewire.orders.daily-report',
            compact('dailyReports', 'regions', 'lastWeekAvg', 'lastMonthAvg',
                'weekDaysAvg', 'weekendsAvg', 'totalOrders'));
    }

    public function updatedDateFrom($newValue)
    {
        $dateFrom = Carbon::parse($newValue);
        if ($this->dateTo instanceof Carbon) {
            $dateTo = $this->dateTo;
        } else {
            $dateTo = Carbon::parse($this->dateTo);
        }
        if ($dateFrom->gt($dateTo)) {
            $this->dateTo = $dateFrom->addDay()->toDateString();
        }
    }

    public function updatedDateTo($newValue)
    {
        $dateTo = Carbon::parse($newValue);
        if ($this->dateFrom instanceof Carbon) {
            $dateFrom = $this->dateFrom;
        } else {
            $dateFrom = Carbon::parse($this->dateFrom);
        }
        if ($dateTo->lt($dateFrom)) {
            $this->dateFrom = $dateTo->subDay()->toDateString();
        }
    }

    public function mount()
    {
        $this->dateFrom = now()->subMonth()->toDateString();
        $this->dateTo = now()->toDateString();
    }

    public function render()
    {
        [
            $dailyReports, $lastWeekAvg, $lastMonthAvg, $weekDaysAvg, $weekendsAvg, $totalOrders, $regions
        ] = $this->getAll();

        return view('livewire.orders.daily-report',
            compact('dailyReports', 'regions', 'lastWeekAvg', 'lastMonthAvg',
                'weekDaysAvg', 'weekendsAvg', 'totalOrders'));
    }

    /**
     * @return array
     */
    private function retrieveAverages(): array
    {
        $lastWeekAvg = OrderDailyReport::retrieveValues($this->channel,
            OrderDailyReport::where('day', '>=', Carbon::now()->subDays(7)->toDateString())
                            ->get());

        $lastMonthAvg = OrderDailyReport::retrieveValues($this->channel,
            OrderDailyReport::where('day', '>=', Carbon::now()->subDays(30)->toDateString())
                            ->get());

        $weekDaysAvg = OrderDailyReport::retrieveValues($this->channel,
            OrderDailyReport::where('is_weekend', false)
                            ->get());

        $weekendsAvg = OrderDailyReport::retrieveValues($this->channel,
            OrderDailyReport::where('is_weekend', true)
                            ->get());

        return [$lastWeekAvg, $lastMonthAvg, $weekDaysAvg, $weekendsAvg];
    }

    /**
     * @return array
     */
    private function getAll(): array
    {
        $dailyReports = OrderDailyReport::orderByDesc('day');
        $dailyReports = $dailyReports->whereBetween('day', [$this->dateFrom, $this->dateTo]);
        if ($this->regionId && $this->regionId != 'all') {
            $dailyReports = $dailyReports->where('region_id', $this->regionId);
        }
        $dailyReports = $dailyReports->get();

        [$lastWeekAvg, $lastMonthAvg, $weekDaysAvg, $weekendsAvg] = $this->retrieveAverages();

        $totalOrders = OrderDailyReport::retrieveValues($this->channel, OrderDailyReport::get(), true);

        $regions = Region::active()->get();

        return [$dailyReports, $lastWeekAvg, $lastMonthAvg, $weekDaysAvg, $weekendsAvg, $totalOrders, $regions];
    }
}
