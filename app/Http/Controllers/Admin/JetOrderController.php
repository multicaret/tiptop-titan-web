<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JetOrder;
use App\Models\Order;
use App\Models\Taxonomy;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JetOrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:order.permissions.show', ['only' => ['show']]);
        $ratingOrderType = request('type');
        $this->middleware('permission:order.permissions.index|rating-'.$ratingOrderType.'.permissions.index',
            ['only' => ['index', 'store']]);
    }

    public function index(Request $request)
    {

        $orders = JetOrder::with( 'branch')
                       ->orderBy('created_at', 'desc')
                       ->orderBy('status')
                       ->paginate(30);

        return view('admin.jet-orders.index', compact('orders'));
    }


    public function show(JetOrder $order)
    {
        return view('admin.jet-orders.show', compact('order'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function orderRatings(Request $request)
    {
        $typeName = Order::getCorrectChannelName($request->type, false);
        $columns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => trans('strings.id'),
                'width' => '5',
            ],
            [
                'data' => 'order',
                'name' => 'order',
                'title' => trans('strings.order_number'),
                'width' => '5',
            ],
            [
                'data' => 'branch',
                'name' => 'branch',
                'title' => trans('strings.branch'),
                'width' => '10',
            ],
            [
                'data' => 'rating',
                'name' => 'rating',
                'title' => trans('strings.rating'),
                'width' => '80',
            ],
            [
                'data' => 'issue',
                'name' => 'issue',
                'title' => trans('strings.issues'),
                'width' => '10',
            ],
            [
                'data' => 'comment',
                'name' => 'comment',
                'title' => trans('strings.comment'),
                'width' => '10',
            ],
            [
                'data' => 'created_at',
                'name' => 'created_at',
                'title' => trans('strings.date'),
                'width' => '10',
            ],
        ];

        return view('admin.orders.ratings.index', compact('columns', 'typeName'));
    }

}
