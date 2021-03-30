<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{

    function __construct()
    {
//        $this->middleware('permission:chain.permissions.index', ['only' => ['index', 'store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|View
     */
    public function ratings(Request $request)
    {
        $typeName = Order::getCorrectTypeName($request->type, false);
        $columns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => trans('strings.id'),
                'width' => '5',
            ],
            [
                'data' => 'reference_code',
                'name' => 'reference_code',
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
                'data' => 'rating_comment',
                'name' => 'rating_comment',
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
