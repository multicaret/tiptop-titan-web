<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CouponController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:coupon.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:coupon.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:coupon.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:coupon.permissions.destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $columns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => trans('strings.id'),
                'width' => '15',
                'searchable' => false,
            ],
            [
                'data' => 'redeem_code',
                'name' => 'redeem_code',
                'title' => 'Redeem code',
                'width' => '70',
            ],
            [
                'data' => 'name',
                'name' => 'name',
                'title' => 'Name',
                'width' => '70',
            ],
            [
                'data' => 'discount',
                'name' => 'discount',
                'title' => 'Discount Amount',
                'searchable' => false,
            ],
            [
                'data' => 'max_usable_count',
                'name' => 'max_usable_count',
                'title' => 'Max usable count',
                'searchable' => false,
            ],
            [
                'data' => 'total_redeemed_count',
                'name' => 'total_redeemed_count',
                'title' => 'Total redeemed count',
                'searchable' => false,
            ],
            [
                'data' => 'money_redeemed_so_far',
                'name' => 'money_redeemed_so_far',
                'title' => 'Money redeemed so far',
                'searchable' => false,
            ],
            /* [
                 'data' => 'status',
                 'name' => 'status',
                 'title' => 'Status',
                 'searchable' => false,
             ],*/
            [
                'data' => 'expired_at',
                'name' => 'expired_at',
                'title' => 'Expired at',
            ],
            [
                'data' => 'created_at',
                'name' => 'created_at',
                'title' => trans('strings.create_date')
            ],
        ];

        return view('admin.coupons.index', compact('columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $coupon = new Coupon();
        $coupon->discount_by_percentage = true;

        return view('admin.coupons.form', compact('coupon'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRules());
        $coupon = new Coupon();
        $coupon->creator_id = $coupon->editor_id = auth()->id();
        $this->storeUpdateLogic($request, $coupon);

        return redirect()
            ->route('admin.coupons.index')
            ->with('message', ['text' => 'Added successfully', 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  Coupon  $coupon
     *
     * @return Response
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Coupon  $coupon
     *
     * @return Application|Factory|View|Response
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.form', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Coupon  $coupon
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $request->validate($this->validationRules());
        $coupon->editor_id = auth()->id();
        $this->storeUpdateLogic($request, $coupon);

        return redirect()
            ->route('admin.coupons.index')
            ->with('message', ['text' => 'Updated successfully', 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Coupon  $coupon
     *
     * @return RedirectResponse
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        if ($coupon->delete()) {
            return back()->with('message', [
                'type' => 'success',
                'text' => 'The coupon has been deleted',
            ]);
        }

        return back()->with('message', [
            'type' => 'error',
            'text' => 'Seems to have gotten a problem',
        ]);
    }

    private function validationRules(): array
    {
        return [
            'name' => 'required',
            'redeem_code' => 'required',
            'discount_amount' => 'required',
        ];
    }

    private function storeUpdateLogic(Request $request, Coupon $coupon)
    {
        $coupon->name = $request->input('name');
        $coupon->redeem_code = $request->input('redeem_code');
        $coupon->description = $request->input('description');
        $coupon->discount_amount = json_decode($request->input('discount_amount'));
        $coupon->max_usable_count = $request->input('max_usable_count');
        $coupon->max_usable_count_by_user = $request->input('max_usable_count_by_user');
        $coupon->min_cart_value_allowed = $request->input('min_cart_value_allowed');
        $coupon->has_free_delivery = $request->has('has_free_delivery') ? $request->input('has_free_delivery') : 0;
        $coupon->discount_by_percentage = $request->has('discount_by_percentage') ? json_decode($request->input('discount_by_percentage')) : 0;
        $coupon->max_allowed_discount_amount = $request->input('max_allowed_discount_amount');
        $coupon->expired_at = Carbon::parse($request->input('expired_at'));
        $coupon->status = $request->input('status');
        $coupon->channel = $request->input('channel');
        $coupon->save();
    }
}
