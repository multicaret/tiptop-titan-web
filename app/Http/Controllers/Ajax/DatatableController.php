<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Branch;
use App\Models\Chain;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Region;
use App\Models\Slide;
use App\Models\Taxonomy;
use App\Models\Translation;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class DatatableController extends AjaxController
{
    /**
     * @param  Request  $request
     *
     * @return mixed
     * @throws Exception
     */
    public function users(Request $request)
    {
        $type = Str::ucfirst($request->type);
        if ( ! in_array($type, User::getAllRoles())) {
            $type = User::ROLE_USER;
        }

        $users = User::with(['defaultAddress'])
                     ->orderBy('order_column')
                     ->role($type)
                     ->selectRaw('users.*');

        return DataTables::of($users)
                         ->editColumn('action', function ($user) {
                             $data = [];
                             if (auth()->user()->role == User::ROLE_SUPER || auth()->user()->role != User::ROLE_SUPER) {
                                 $data = [
                                     'modelId' => $user->id,
                                     'editAction' => route('admin.users.edit', [$user, 'type' => request('type')]),
                                     'deleteAction' => route('admin.users.destroy', $user),
                                 ];
                             }

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('status', function ($user) {
                             $currentStatus = User::getAllStatusesRich()[$user->status];
                             $data = [
                                 'item' => $user,
                                 'currentStatus' => $currentStatus,
                             ];

                             return view('admin.components.datatables._row-actions-status', $data)
                                 ->render();
                         })
                         ->editColumn('order_column', function ($item) {
                             return view('admin.components.datatables._row-reorder')->render();
                         })
                         ->editColumn('last_logged_in_at', function ($item) {
                             if ( ! is_null($item->last_logged_in_at)) {
                                 return view('admin.components.datatables._date', [
                                     'date' => $item->last_logged_in_at
                                 ])->render();
                             }

                             return '<i>Never</i>';
                         })
                         ->editColumn('created_at', function ($item) {
                             return view('admin.components.datatables._date', [
                                 'date' => $item->created_at
                             ])->render();
                         })
                         ->rawColumns([
                             'action',
                             'order_column',
                             'created_at',
                             'last_logged_in_at',
                             'status',
                         ])
                         ->setRowAttr([
                             'row-id' => function ($user) {
                                 return $user->id;
                             }
                         ])
                         ->make(true);
    }

    /**
     * @param  Request  $request
     *
     * @return mixed
     * @throws Exception
     */
    public function taxonomies(Request $request)
    {
        $correctType = Taxonomy::getCorrectType($request->type);
        $taxonomies = Taxonomy::orderBy('order_column')
                              ->with('parent', 'chain', 'branches', 'branch')
                              ->where('type', $correctType);

        return DataTables::of($taxonomies)
                         ->editColumn('action', function ($taxonomy) {
                             $data = [
                                 'editAction' => route('admin.taxonomies.edit', [
                                     $taxonomy->uuid,
                                     'type' => request('type')
                                 ]),
                                 'deleteAction' => route('admin.taxonomies.destroy', [
                                     $taxonomy->uuid,
                                     'type' => request('type')
                                 ]),
                             ];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('parent', function ($item) {
                             return $item->parent ? $item->parent->title : null;
                         })
                         ->editColumn('chain', function ($item) {
                             return $item->chain ? $item->chain->title : null;
                         })
                         ->editColumn('branches', function ($item) {
                             $branches = $item->branches->pluck('title')->toArray();

                             return view('admin.components.datatables._badge-items', [
                                 'items' => $branches
                             ])->render();
                         })
                         ->editColumn('ingredientCategory', function ($item) {
                             return ! is_null($item->ingredientCategory) ? $item->ingredientCategory->title : null;
                         })
                         ->editColumn('order_column', function ($item) {
                             return view('admin.components.datatables._row-reorder')->render();
                         })
                         ->editColumn('created_at', function ($item) {
                             return view('admin.components.datatables._date', [
                                 'date' => $item->created_at
                             ])->render();
                         })
                         ->editColumn('status', function ($item) {
                             return view('admin.components.datatables._status', [
                                 'status' => $item->status
                             ])->render();
                         })
                         ->rawColumns([
//                             'chain',
                             'step',
                             'branches',
                             'action',
                             'order_column',
                             'created_at',
                             'ingredientCategory'
                         ])
                         ->setRowAttr([
                             'row-id' => function ($taxonomy) {
                                 return $taxonomy->id;
                             }
                         ])
                         ->make(true);
    }

    /**
     * @param  Request  $request
     *
     * @return mixed
     * @throws Exception
     */
    public function posts(Request $request)
    {
        $posts = Post::orderBy('order_column')
                     ->where('type', Post::getCorrectType($request->type))
                     ->selectRaw('posts.*');

        return DataTables::of($posts)
                         ->editColumn('action', function ($post) {
                             $data = [
                                 'editAction' => route('admin.posts.edit', [
                                     $post->uuid,
                                     'type' => request('type')
                                 ]),
                                 'deleteAction' => route('admin.posts.destroy', [
                                     $post->uuid,
                                     'type' => request('type')
                                 ]),
                             ];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('status', function ($post) {
                             $currentStatus = Post::getAllStatusesRich()[$post->status];
                             $data = [
                                 'item' => $post,
                                 'currentStatus' => $currentStatus,
                             ];

                             return view('admin.components.datatables._row-actions-status', $data)
                                 ->render();
                         })
                         ->editColumn('order_column', function ($item) {
                             return view('admin.components.datatables._row-reorder')->render();
                         })
                         ->editColumn('created_at', function ($item) {
                             return view('admin.components.datatables._date', [
                                 'date' => $item->created_at
                             ])->render();
                         })
                         ->rawColumns([
                             'action',
                             'order_column',
                             'excerpt',
                             'created_at',
                             'status',
                         ])
                         ->setRowAttr([
                             'row-id' => function ($post) {
                                 return $post->id;
                             }
                         ])
                         ->make(true);
    }

    public function reorder(Request $request)
    {
        foreach ($request->positions as $position) {
            $className = str_replace('-', '', Str::singular(Str::title($position['model_name'])));
            $className = "App\\Models\\$className";
            $className::find($position['id'])->update([
                'order_column' => $position['order_new_value']
            ]);
        }
    }

    public function cities(Request $request)
    {
        $cities = City::selectRaw('cities.*')->with('translations', 'region.translations');

        return DataTables::of($cities)
                         ->editColumn('action', function ($city) {
                             $data = [
                                 'editAction' => route('admin.cities.edit', $city),
                                 'deleteAction' => route('admin.cities.destroy', [
                                     $city->id,
                                 ]),
                             ];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('status', function ($post) {
                             $currentStatus = Post::getAllStatusesRich()[$post->status];
                             $data = [
                                 'item' => $post,
                                 'currentStatus' => $currentStatus,
                             ];

                             return view('admin.components.datatables._row-actions-status', $data)
                                 ->render();
                         })
                         ->editColumn('created_at', function ($item) {
                             return view('admin.components.datatables._date', [
                                 'date' => $item->created_at
                             ])->render();
                         })
                         ->rawColumns([
                             'action',
                             'created_at',
                             'status',
                         ])
                         ->setRowAttr([
                             'row-id' => function ($city) {
                                 return $city->id;
                             }
                         ])
                         ->make(true);
    }

    public function regions(Request $request)
    {
        $regions = Region::selectRaw('regions.*')->with('translations', 'country');

        return DataTables::of($regions)
                         ->editColumn('action', function ($region) {
                             $data = [
                                 'editAction' => route('admin.regions.edit', $region),
                                 'deleteAction' => route('admin.regions.destroy', [
                                     $region->id,
                                 ]),
                             ];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('status', function ($post) {
                             $currentStatus = Post::getAllStatusesRich()[$post->status];
                             $data = [
                                 'item' => $post,
                                 'currentStatus' => $currentStatus,
                             ];

                             return view('admin.components.datatables._row-actions-status', $data)
                                 ->render();
                         })
                         ->editColumn('created_at', function ($item) {
                             return view('admin.components.datatables._date', [
                                 'date' => $item->created_at
                             ])->render();
                         })
                         ->rawColumns([
                             'action',
                             'created_at',
                             'status',
                         ])
                         ->setRowAttr([
                             'row-id' => function ($region) {
                                 return $region->id;
                             }
                         ])
                         ->make(true);
    }

    public function slides(Request $request)
    {
        $slides = Slide::selectRaw('slides.*')->latest();

        return DataTables::of($slides)
                         ->editColumn('action', function ($slide) {
                             $data = [
                                 'editAction' => route('admin.slides.edit', $slide),
                                 'deleteAction' => route('admin.slides.destroy', [
                                     $slide->uuid,
                                 ]),
                             ];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('begins_at', function ($item) {
                             if ( ! is_null($item->begins_at)) {
                                 return view('admin.components.datatables._date', [
                                     'date' => $item->begins_at
                                 ])->render();
                             }
                         })
                         ->editColumn('expires_at', function ($item) {
                             if ( ! is_null($item->expires_at)) {
                                 {
                                     return view('admin.components.datatables._date', [
                                         'date' => $item->expires_at
                                     ])->render();
                                 }
                             }
                         })
                         ->editColumn('state', function ($item) {
                             $colorArray = [
                                 'scheduled' => [
                                     'color' => '#0CB4F0',
                                     'text' => __('strings.scheduled'),
                                     'icon' => 'fas fa-clock'
                                 ],
                                 'active' => [
                                     'color' => '#4F9234',
                                     'text' => __('strings.active'),
                                     'icon' => 'fas fa-play'
                                 ],
                                 'expired' => [
                                     'color' => '#CE4F4B',
                                     'text' => __('strings.expired'),
                                     'icon' => 'fas fa-clipboard-check'
                                 ],
                                 'n/a' => [
                                     'color' => '#1F2024',
                                     'text' => __('strings.always_on'),
                                     'icon' => 'fas fa-infinity'
                                 ],
                             ];
                             $now = Carbon::now();

                             switch ($now) {
                                 case $now->lt($item->begins_at):
                                     $state = $colorArray['scheduled'];
                                     break;
                                 case $now->lt($item->expires_at):
                                     $state = $colorArray['active'];
                                     break;
                                 case $now->gt($item->expires_at):
                                     $state = $colorArray['expired'];
                                     break;
                                 case is_null($item->begins_at) || is_null($item->expires_at):
                                     $state = $colorArray['n/a'];
                                     break;
                             }
                             $color = $state['color'];
                             $title = $state['text'];
                             $icon = $state['icon'];

                             if ( ! is_null($item->expires_at)) {
                                 return view('admin.components.datatables._time-left', [
                                     'title' => $title,
                                     'tooltip' => Str::kebab($item->expires_at->diffForHumans(Carbon::now())),
                                     'icon' => $icon,
                                     'color' => $color,
                                 ])->render();
                             }

                             return null;
                         })
                         ->editColumn('region', function ($item) {
                             return ! is_null($item->region) ? $item->region->name : '';
                         })
                         ->editColumn('city', function ($item) {
                             return ! is_null($item->city) ? $item->city->name : '';
                         })
                         ->editColumn('has_been_authenticated', function ($item) {
                             return Slide::getTargetsArray()[$item->has_been_authenticated];
                         })
                         ->editColumn('channel', function ($item) {
                             $currentChannel = Slide::getAllChannelsRich()[$item->channel];
                             $data = [
                                 'item' => $item,
                                 'currentChannel' => $currentChannel,
                             ];

                             return view('admin.components.datatables._row-actions-channel', $data)
                                 ->render();
                         })
                         ->editColumn('thumbnail', function (Slide $item) {
                             $image = config('defaults.images.slider_image');
                             $altTag = null;
                             $translations = $item->translations->filter(function ($translation) {
                                 return $translation->image != url(config('defaults.images.slider_image'));
                             })->first();

                             if ( ! is_null($translations)) {
                                 $image = $translations->image;
                                 $altTag = $translations->alt_tag;
                             }

                             return view('admin.components.datatables._thumbnails', [
                                 'imageUrl' => $image,
                                 'tooltip' => $altTag,
                             ])->render();
                         })
                         ->editColumn('created_at', function ($item) {
                             return view('admin.components.datatables._date', [
                                 'date' => $item->created_at
                             ])->render();
                         })
                         ->rawColumns([
                             'action',
                             'created_at',
                             'status',
                             'begins_at',
                             'expires_at',
                             'region',
                             'city',
                             'state',
                             'channel',
                             'time_left',
                             'thumbnail',
                         ])
                         ->setRowAttr([
                             'row-id' => function ($slide) {
                                 return $slide->id;
                             }
                         ])
                         ->make(true);
    }

    public function chains(Request $request)
    {
        $chains = Chain::where('type', Chain::getCorrectType($request->type))->selectRaw('chains.*');

        return DataTables::of($chains)
                         ->editColumn('action', function ($chain) {
                             $data = [
                                 'editAction' => route('admin.chains.edit', [
                                     $chain->uuid,
                                     'type' => request('type')
                                 ]),
                                 'deleteAction' => route('admin.chains.destroy', [
                                     $chain->uuid,
                                     'type' => request('type')
                                 ]),
                             ];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('region', function ($chain) {
                             return ! is_null($chain->region) ? $chain->region->name : '';
                         })
                         ->editColumn('city', function ($chain) {
                             return ! is_null($chain->city) ? $chain->city->name : '';
                         })
                         ->editColumn('created_at', function ($chain) {
                             return view('admin.components.datatables._date', [
                                 'date' => $chain->created_at
                             ])->render();
                         })
                         ->rawColumns([
                             'action',
                             'region',
                             'city',
                             'created_at',
                         ])
                         ->setRowAttr([
                             'row-id' => function ($chain) {
                                 return $chain->id;
                             }
                         ])
                         ->make(true);
    }


    public function coupons(Request $request)
    {
        $coupons = Coupon::selectRaw('coupons.*');

        return DataTables::of($coupons)
                         ->editColumn('action', function ($coupon) {
                             $data = [
                                 'editAction' => route('admin.coupons.edit', [
                                     $coupon->id,
                                 ]),
                                 'deleteAction' => route('admin.coupons.destroy', [
                                     $coupon->id,
                                 ]),
                             ];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('discount', function ($coupon) {

                             $prefix = $coupon->discount_by_percentage ? '%' : config('defaults.currency.code');

                             return $prefix.' '.$coupon->discount_amount;
                         })
                         ->editColumn('expired_at', function ($coupon) {
                             return view('admin.components.datatables._date', [
                                 'date' => $coupon->expired_at
                             ])->render();
                         })
                         ->editColumn('status', function ($coupon) {
                             return view('admin.components.datatables._status', [
                                 'status' => $coupon->status
                             ])->render();
                         })
                         ->editColumn('created_at', function ($coupon) {
                             return view('admin.components.datatables._date', [
                                 'date' => $coupon->created_at
                             ])->render();
                         })
                         ->rawColumns([
                             'action',
                             'status',
                             'discount',
                             'created_at',
                             'expired_at',
                         ])
                         ->setRowAttr([
                             'row-id' => function ($coupon) {
                                 return $coupon->id;
                             }
                         ])
                         ->make(true);
    }

    public function branches(Request $request)
    {

        $branches = Branch::whereType(Branch::getCorrectType($request->type))->selectRaw('branches.*');

        return DataTables::of($branches)
                         ->editColumn('action', function ($branch) {
                             $data = [
                                 'editAction' => route('admin.branches.edit', [
                                     $branch->uuid,
                                     'type' => request('type')
                                 ]),
                                 'deleteAction' => route('admin.branches.destroy', [
                                     $branch->uuid,
                                     'type' => request('type')
                                 ]),
                             ];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('region', function ($branch) {
                             return ! is_null($branch->region) ? $branch->region->name : '';
                         })
                         ->editColumn('city', function ($branch) {
                             return ! is_null($branch->city) ? $branch->city->name : '';
                         })
                         ->editColumn('chain', function ($branch) {
                             return ! is_null($branch->chain) ? $branch->chain->title : '';
                         })
                         ->editColumn('created_at', function ($branch) {
                             return view('admin.components.datatables._date', [
                                 'date' => $branch->created_at
                             ])->render();
                         })
                         ->rawColumns([
                             'action',
                             'chain',
                             'region',
                             'city',
                             'created_at',
                         ])
                         ->setRowAttr([
                             'row-id' => function ($branch) {
                                 return $branch->id;
                             }
                         ])
                         ->make(true);
    }


    public function translationList(Request $request)
    {
        $group = $request->input('group_by');
        $transitions = is_null($group) ? Translation::on() : Translation::on()->group($group);
        $of = DataTables::of($transitions);
        $rawColumns = [
            'action',
            'order_column'
        ];
        foreach (localization()->getSupportedLocalesKeys() as $key) {
            array_push($rawColumns, $key.'_value');
            $of->editColumn($key.'_value', function ($transition) use ($key) {
                $hasTranslation = $transition->hasTranslation($key);
                $value = $hasTranslation ? $transition->getTranslation($key)->value : '';

                return view('admin.components.datatables._row-transition-value', [
                    'id' => $transition->id,
                    'value' => $value,
                    'localeKey' => $key,
                    'is_empty' => ! $hasTranslation,
                ])->render();
            })->filterColumn($key.'_value', function ($builderData, $search) use ($key) {
                if (strpos(Str::lower($search), 'empty') !== false) {
                    return $builderData->notTranslatedIn($key);
                }

                return $builderData->whereTranslationLike('value', " % $search", $key);
            });
        }

        return $of
            ->editColumn('action', function ($transition) {
            })
            ->editColumn('order_column', function ($transition) {
                return view('admin.components.datatables._row-reorder')->render();
            })
            ->rawColumns($rawColumns)
            ->make(true);
    }

    public function products(Request $request)
    {
        $products = Product::whereType(Product::getCorrectType($request->type))->selectRaw('products.*');

        return DataTables::of($products)
                         ->editColumn('action', function ($product) {
                             $data = [
                                 'editAction' => route('admin.products.edit', [
                                     $product->uuid,
                                     'type' => request('type')
                                 ]),
                                 'deleteAction' => route('admin.products.destroy', [
                                     $product->uuid,
                                     'type' => request('type')
                                 ]),
                             ];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('chain', function ($product) {
                             return ! is_null($product->chain) ? $product->chain->title : '';
                         })
                         ->editColumn('branch', function ($product) {
                             return ! is_null($product->branch) ? $product->branch->title : '';
                         })
                         ->editColumn('price', function ($product) {
                             return ! is_null($product->price) ? $product->price_formatted : '';
                         })
                         ->editColumn('created_at', function ($product) {
                             return view('admin.components.datatables._date', [
                                 'date' => $product->created_at
                             ])->render();
                         })
                         ->rawColumns([
                             'action',
                             'chain',
                             'branch',
                             'price',
                             'created_at',
                         ])
                         ->setRowAttr([
                             'row-id' => function ($branch) {
                                 return $branch->id;
                             }
                         ])
                         ->make(true);
    }

    public function orderRatings(Request $request)
    {
        $orders = Order::whereType(Order::getCorrectType($request->type))->selectRaw('orders.*');

        return DataTables::of($orders)
                         ->editColumn('action', function ($order) {
                             $data = [];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('branch', function ($order) {
                             return ! is_null($order->branch) ? $order->branch->title : '';
                         })
                         ->editColumn('order', function ($order) {
                             return view('admin.components.datatables._link', [
                                 'text' => $order->reference_code,
                                 'link' => route('admin.products.index'),
//                                 'link' => route('admin.orders.edit', [$order->id]),

                             ])->render();
                         })
                         ->editColumn('comment', function ($order) {
                             return view('admin.components.datatables._comment', [
                                 'order' => $order,
                             ])->render();
                         })
                         ->editColumn('issue', function ($order) {
                             return ! is_null($order->ratingIssue) ? $order->ratingIssue->title : '';
                         })
                         ->editColumn('rating', function ($order) {
                             return view('admin.components.datatables._rating', [
                                 'rating' => $order->branch_rating_value
                             ])->render();
                         })
                         ->editColumn('created_at', function ($order) {
                             return view('admin.components.datatables._date', [
                                 'date' => $order->created_at
                             ])->render();
                         })
                         ->rawColumns([
                             'action',
                             'order',
                             'comment',
                             'branch',
                             'rating',
                             'issue',
                             'rating_comment',
                             'created_at',
                         ])
                         ->setRowAttr([
                             'row-id' => function ($branch) {
                                 return $branch->id;
                             }
                         ])
                         ->make(true);
    }


}
