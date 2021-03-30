<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $columns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => trans('strings.id'),
                'width' => '50',
            ],
            [
                'data' => 'name',
                'name' => 'name',
                'title' => 'Name',
            ],
            [
                'data' => 'description',
                'name' => 'description',
                'title' => 'Description',
            ],
            [
                'data' => 'discount',
                'name' => 'discount',
                'title' => 'Discount Amount',
                'searchable' => false,
            ],
            [
                'data' => 'status',
                'name' => 'status',
                'title' => 'Status',
                'searchable' => false,
            ],
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
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
     * @param  \App\Models\Coupon  $coupon
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.form', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Coupon $coupon): \Illuminate\Http\RedirectResponse
    {
        $request->validate($this->validationRules());
        $coupon->editor_id = auth()->id();
        $this->storeUpdateLogic($request, $coupon);

        return redirect()
            ->route('admin.coupons.index')
            ->with('message', ['text' => 'Added successfully', 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Coupon $coupon): \Illuminate\Http\RedirectResponse
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
            "name" => 'required',
            "redeem_code" => 'required',
            "discount_amount" => 'required',
        ];
    }

    private function storeUpdateLogic(Request $request, Coupon $coupon)
    {
        $coupon->name = $request->input('name');
        $coupon->redeem_code = $request->input('redeem_code');
        $coupon->description = $request->input('description');
        $coupon->discount_amount = json_decode($request->input('discount_amount'));
        $coupon->total_usage_count = $request->input('total_usage_count');
        $coupon->usage_count_by_same_user = $request->input('usage_count_by_same_user');
        $coupon->is_delivery_free = $request->has('is_delivery_free') ? $request->input('is_delivery_free') : 0;
        $coupon->discount_by_percentage = $request->has('discount_by_percentage') ? json_decode($request->input('discount_by_percentage')) : 0;
        $coupon->max_allowed_discount_amount = $request->input('max_allowed_discount_amount');
        $coupon->expired_at = Carbon::parse($request->input('expired_at'));
        $coupon->status = $request->input('status');
        $coupon->save();
    }
}
