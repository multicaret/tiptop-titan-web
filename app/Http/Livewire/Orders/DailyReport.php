<?php

namespace App\Http\Livewire\Orders;

use App\Models\OrderDailyReport;
use App\Models\Region;
use Livewire\Component;

class DailyReport extends Component
{
    public $dateFrom;
    public $dateTo;
    public $regionId;


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
