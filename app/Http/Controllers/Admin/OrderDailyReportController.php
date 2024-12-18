<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderDailyReport;

class OrderDailyReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:daily-report.permissions.index', ['only' => ['index']]);
    }


    public function index()
    {

        return view('admin.orders.reports.index');
    }
}
