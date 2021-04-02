<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrdernController extends Controller
{
    function __construct()
    {
//        $this->middleware('permission:order.permissions.index', ['only' => ['index', 'store']]);
    }

    public function index()
    {

        $orders = Order::latest()->get();

        return view('admin.orders.index', compact('orders'));
    }
}
