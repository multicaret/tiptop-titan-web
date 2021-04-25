<?php

namespace App\Http\Livewire\Orders;

use App\Models\OrderDailyReport;
use Livewire\Component;

class DailyReport extends Component
{
    public function render()
    {
        $dailyReports = OrderDailyReport::latest()
                                        ->whereMonth('created_at', '=', now()->month)
                                        ->get();

        return view('livewire.orders.daily-report', compact('dailyReports'));
    }
}
