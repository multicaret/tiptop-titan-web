<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Models\Taxonomy;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PaymentMethodController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function __construct()
    {
        $this->modelName = PaymentMethod::class;
        $this->middleware('permission:payment_method.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:payment_method.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:payment_method.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:payment_method.permissions.destroy', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $columns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => trans('strings.id'),
                'width' => '20',
            ],
            [
                'data' => 'title',
                'name' => 'translations.title',
                'title' => trans('strings.title'),
            ],
            /*     [
                     'data' => 'description',
                     'name' => 'translations.description',
                     'title' => 'Description',
                 ],*/
            [
                'data' => 'status',
                'name' => 'status',
                'title' => trans('strings.status'),
            ],
            [
                'data' => 'created_at',
                'name' => 'created_at',
                'title' => trans('strings.create_date')
            ],
        ];

        return view('admin.payment-methods.index', compact('columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     *
     * @return View
     */
    public function create(Request $request)
    {
        $paymentMethod = new PaymentMethod();

        return view('admin.payment-methods.form', compact('paymentMethod'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws DiskDoesNotExist
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRules());

        $paymentMethod = new PaymentMethod();
        $paymentMethod->creator_id = auth()->id();
        $this->storeUpdateLogic($request, $paymentMethod);

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('message', [
                'type' => 'Success',
                'text' => __('strings.successfully_created'),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PaymentMethod  $paymentMethod
     *
     * @param  Request  $request
     *
     * @return Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $paymentMethodId)
    {
        $paymentMethod = $this->getModelById($paymentMethodId)->first();

        return view('admin.payment-methods.form', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  PaymentMethod  $paymentMethod
     *
     * @return RedirectResponse
     * @throws DiskDoesNotExist
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(Request $request, $paymentMethodId)
    {
        $paymentMethod = $this->getModelById($paymentMethodId)->first();
        $request->validate($this->validationRules());
        $this->storeUpdateLogic($request, $paymentMethod);

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('message', [
                'type' => 'Success',
                'text' => __('strings.successfully_updated'),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PaymentMethod  $paymentMethod
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy($paymentMethodId)
    {
        $paymentMethod = $this->getModelById($paymentMethodId)->first();
        $paymentMethod->media()->delete();

        $paymentMethod->delete();

        return back()->with('message', [
            'type' => 'Success',
            'text' => __('strings.successfully_deleted'),
        ]);
    }

    /**
     * @return array
     */
    private function validationRules(): array
    {
        $defaultLocale = localization()->getDefaultLocale();

        return [
            "{$defaultLocale}.title" => 'required',
            "status" => 'required',
        ];
    }

    /**
     * @param  Request  $request
     * @param  PaymentMethod  $paymentMethod
     * @throws DiskDoesNotExist
     * @throws FileDoesNotExist
     * @throws FileIsTooBig|\Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    private function storeUpdateLogic(
        Request $request,
        PaymentMethod $paymentMethod
    ): void {
        \DB::beginTransaction();
        $paymentMethod->editor_id = auth()->id();
        $paymentMethod->status = $request->status;
        $paymentMethod->save();

        // Filling translations
        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                $paymentMethod->translateOrNew($key)->title = $request->input($key.'.title');
                $paymentMethod->translateOrNew($key)->description = $request->input($key.'.description');
                $paymentMethod->translateOrNew($key)->instructions = $request->input($key.'.instructions');
            }
        }
        $paymentMethod->save();

        $this->handleSubmittedSingleMedia('logo', $request, $paymentMethod);

        if ($request->has('unattached-media') && $unattachedMediaId = $request->input('unattached-media')) {
            Media::find($unattachedMediaId)->delete();
        }
        \DB::commit();
    }
}
