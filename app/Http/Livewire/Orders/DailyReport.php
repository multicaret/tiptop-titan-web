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
        $this->dateFrom = now()->toDateString();
        $this->dateTo = now()->addMonth()->toDateString();
    }

    public function render()
    {
        $dailyReports = OrderDailyReport::latest();
        $dailyReports = $dailyReports->whereBetween('day', [$this->dateFrom, $this->dateTo]);
        if ($this->regionId && $this->regionId != 'all') {
            $dailyReports = $dailyReports->where('region_id', $this->regionId);
        }
        $dailyReports = $dailyReports->get();

        $regions = Region::active()->get();

        return view('livewire.orders.daily-report', compact('dailyReports', 'regions'));
    }
}
