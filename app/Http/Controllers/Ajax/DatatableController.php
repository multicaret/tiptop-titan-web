<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Chain;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Post;
use App\Models\Product;
use App\Models\Region;
use App\Models\Slide;
use App\Models\Taxonomy;
use App\Models\TookanTeam;
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
        $role = $request->all()['role'];
        if (in_array($role, User::getAllRoles())) {
            $role = ucwords(str_replace('-', ' ', Str::title($role)));
        } else {
//            $role = User::ROLE_USER;
            $role = [];
        }
        $users = User::role($role)
                     ->selectRaw('users.*');

        return DataTables::of($users)
                         ->editColumn('action', function ($user) {
                             $data = [
                                 'modelId' => $user->id,
                                 'editAction' => route('admin.users.edit',
                                     ['role' => $user->role_name, 'user' => $user]),
                                 'deleteAction' => route('admin.users.destroy',
                                     ['role' => $user->role_name, 'user' => $user]),
                             ];

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
            /*->editColumn('branch', function ($item) {

                return view('admin.components.datatables._row-reorder')->render();
            })*/
                         ->editColumn('employment', function ($item) {
                if ($item->employment) {
                    return User::getEmploymentsArray()[$item->employment];
                }
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
                         ->editColumn('team', function ($user) {
                             return optional($user->team)->name;
                         })
                         ->rawColumns([
                             'action',
                             'created_at',
                             'employment',
                             'last_logged_in_at',
                             'status',
                             'team',
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
        $parentId = ($request->input('parent_id'));
        $correctType = Taxonomy::getCorrectType($request->type);
        $taxonomies = Taxonomy::orderBy('taxonomies.order_column')
                              ->with('parent', 'chain', 'branches', 'branch')
                              ->where('taxonomies.type', $correctType);
        if ($correctType == Taxonomy::TYPE_GROCERY_CATEGORY) {
            if ( ! is_null($parentId)) {
                $taxonomies = $taxonomies->where('parent_id', $parentId);
            } else {
                $taxonomies = $taxonomies->where('parent_id', null);
            }
        } elseif ($correctType == Taxonomy::TYPE_MENU_CATEGORY) {
            $taxonomies = $taxonomies->selectRaw('taxonomies.*,branch_translations.title')
                                     ->leftJoin('branches', function ($join) {
                                         $join->on('branches.id', '=', 'taxonomies.branch_id');
                                         $join->leftJoin('branch_translations', function ($join) {
                                             $join->on('branches.id', '=', 'branch_translations.branch_id');
                                         });
                                     });

        } elseif ($correctType == Taxonomy::TYPE_END_USER_TAGS) {
            $taxonomies = $taxonomies->selectRaw('taxonomies.id,taxonomies.status,taxonomies.created_at,taxonomies.uuid,count(u.id) as users_count')
                                     ->leftJoin('taggables as t', function ($join) {
                                         $join->on('t.taxonomy_id', '=', 'taxonomies.id')->where('t.taggable_type',
                                             User::class);;
                                         $join->leftJoin('users as u', function ($join) {
                                             $join->on('u.id', '=', 't.taggable_id');
                                         });
                                     })->groupBy('taxonomies.id', 'taxonomies.status', 'taxonomies.created_at',
                    'taxonomies.uuid');

        }

        // dd($taxonomies->get());
        return DataTables::of($taxonomies)
                         ->editColumn('action', function ($taxonomy) use ($correctType) {
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

                             $isGroceryType = $correctType === Taxonomy::TYPE_GROCERY_CATEGORY;
                             $isMenuCategory = $correctType === Taxonomy::TYPE_MENU_CATEGORY;
                             $isFoodCategory = $correctType === Taxonomy::TYPE_FOOD_CATEGORY;


                             $deepLinkChannel = config('app.app-channels.grocery');
                             if ( ! is_null($taxonomy->parent_id) && $isGroceryType) {
                                 $parentId = $taxonomy->parent_id;
                             }
                             if ($isMenuCategory) {
                                 $parentId = $taxonomy->branch_id;
                                 $deepLinkChannel = config('app.app-channels.food');
                             }
                             if ($isFoodCategory) {
                                 $deepLinkChannel = config('app.app-channels.food');
                                 $data['deepLink'] = [
                                     'url' => Controller::generateDeepLink('food_category_show', [
                                         'id' => $taxonomy->id,
                                         'channel' => $deepLinkChannel
                                     ])
                                 ];
                             }

                             if (isset($parentId)) {
                                 $data['deepLink'] = [
                                     'url' => Controller::generateDeepLink('market_food_category_show', [
                                         'id' => $taxonomy->id,
                                         'parent_id' => $parentId,
                                         'channel' => $deepLinkChannel
                                     ])
                                 ];


                             }
                             if (Taxonomy::getCorrectType(request()->type) == Taxonomy::TYPE_END_USER_TAGS) {

                                 $data['exportTaggedUsersAction'] = route('admin.tagged_users.export', [
                                     $taxonomy->uuid,
                                     'type' => request('type')
                                 ]);

                             }

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('parent', function ($item) {
                             return $item->parent ? $item->parent->title : null;
                         })
                         ->editColumn('chain', function ($item) {
                             return $item->chain ? $item->chain->title : null;
                         })
                         ->editColumn('branch_translations.title', function ($item) {
                             return $item->branch ? $item->branch->title : '';
                         })
                         ->editColumn('branches', function ($item) use ($correctType) {
                             $branches = $item->branches->pluck('title')->toArray();
                             $isFoodCategory = $correctType === Taxonomy::TYPE_FOOD_CATEGORY;
                             $isGroceryCategory = $correctType === Taxonomy::TYPE_GROCERY_CATEGORY;

                             return view('admin.components.datatables._badge-items', [
                                 'showDeepLink' => count($branches) && ($isGroceryCategory || $isFoodCategory),
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
                             'branch_title',
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

                             if (\request('type') === Post::getCorrectTypeName(Post::TYPE_ARTICLE, false)) {

                                 $data['deepLink'] = [
                                     'url' => Controller::generateDeepLink('blog_show', [
                                         'id' => $post->id
                                     ])
                                 ];
                             }

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
        $positions = $request->positions;
        $className = str_replace('-', '', Str::singular(Str::title($positions[0]['model_name'])));
        $cacheTag = $className;
        $className = "App\\Models\\$className";
        foreach ($positions as $position) {
            $className::find($position['id'])->update([
                'order_column' => $position['order_new_value']
            ]);
        }

        $cacheTag = Str::plural(strtolower($cacheTag));
        if (in_array($cacheTag, ['taxonomies', 'products', 'posts', 'slides'])) {
            cache()->tags($cacheTag)->flush();
        }
    }

    public function cities(Request $request)
    {
        $cities = City::orderBy('order_column')
                      ->selectRaw('cities.*')
                      ->with('translations', 'region.translations');

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
                         ->editColumn('status', function ($item) {
                             $currentStatus = Post::getAllStatusesRich()[$item->status];
                             $data = [
                                 'item' => $item,
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
                         ->editColumn('order_column', function ($item) {
                             return view('admin.components.datatables._row-reorder')->render();
                         })
                         ->rawColumns([
                             'action',
                             'created_at',
                             'status',
                             'order_column',
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
        $regions = Region::orderBy('order_column')
                         ->selectRaw('regions.*')
                         ->with('translations', 'country.translations');

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
                         ->editColumn('order_column', function ($item) {
                             return view('admin.components.datatables._row-reorder')->render();
                         })
                         ->rawColumns([
                             'action',
                             'created_at',
                             'status',
                             'order_column',
                         ])
                         ->setRowAttr([
                             'row-id' => function ($region) {
                                 return $region->id;
                             }
                         ])
                         ->make(true);
    }

    public function teams(Request $request)
    {
        $teams = TookanTeam::selectRaw('tookan_teams.*')->latest();

        return DataTables::of($teams)
                         ->editColumn('action', function ($team) {
                             $data = [
                                 'editAction' => route('admin.teams.edit', $team),
                                 'deleteAction' => route('admin.teams.destroy', [
                                     $team->id,
                                 ]),
                             ];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('status', function ($coupon) {
                             return view('admin.components.datatables._status', [
                                 'status' => $coupon->status
                             ])->render();
                         })
                         ->editColumn('created_at', function ($item) {
                             return view('admin.components.datatables._date', [
                                 'date' => $item->created_at
                             ])->render();
                         })
                         ->rawColumns([
                             'action',
                             'name',
                             'created_at',
                             'status',
                         ])
                         ->setRowAttr([
                             'row-id' => function ($team) {
                                 return $team->id;
                             }
                         ])
                         ->make(true);
    }


    public function slides(Request $request)
    {
        $slides = Slide::orderBy('order_column')
                       ->selectRaw('slides.*');

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
                         ->editColumn('order_column', function ($item) {
                             return view('admin.components.datatables._row-reorder')->render();
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
            /*->editColumn('region', function ($item) {
                return ! is_null($item->region) ? $item->region->name : '';
            })*/
            /*->editColumn('city', function ($item) {
            return ! is_null($item->city) ? $item->city->name : '';
            })*/
                         ->editColumn('location', function ($item) {
                return (! is_null($item->city) ? $item->city->name : 'All Cities').' - '.(! is_null($item->region) ? $item->region->name : 'All Regions');
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
                             $imageLarge = config('defaults.images.slider_image');
                             $altTag = null;
                             $translations = $item->translations->filter(function ($translation) {
                                 return $translation->image != url(config('defaults.images.slider_image'));
                             })->first();

                             if ( ! is_null($translations)) {
                                 $image = $translations->image;
                                 $imageLarge = $translations->image_full;
                                 $altTag = $translations->alt_tag;
                             }

                             return view('admin.components.datatables._thumbnails', [
                                 'id' => $item->id,
                                 'imageUrl' => $image,
                                 'imageUrlLarge' => $imageLarge,
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
                             'location',
                             'state',
                             'channel',
                             'time_left',
                             'thumbnail',
                             'order_column',
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
        $chains = Chain::where('type', Chain::getCorrectChannel($request->type))
                       ->orderByDesc('id')
                       ->selectRaw('chains.*');

        return DataTables::of($chains)
                         ->editColumn('action', function ($chain) {
                             $data = [
                                 'uuid' => $chain->uuid,
                                 'editAction' => route('admin.chains.edit', [
                                     $chain->uuid,
                                     'type' => request('type')
                                 ]),
                                 'deleteAction' => route('admin.chains.destroy', [
                                     $chain->uuid,
                                     'type' => request('type')
                                 ]),
                             ];
//                             if ( ! $chain->is_synced) {
                             $data['syncAction'] = route('admin.chains.sync', $chain);

//                             }

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
        $branches = Branch::whereType(Branch::getCorrectChannel($request->type))
                          ->selectRaw('branches.*')
                          ->leftJoin('branch_translations', function ($join) {
                              $join->on('branches.id', '=', 'branch_translations.branch_id');
                          })
                          ->groupBy('branches.id');

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
                         ->editColumn('status', function ($branch) {
                             return view('admin.components.datatables._status', [
                                 'status' => $branch->status
                             ])->render();
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
        if (is_null($group)) {
            $transitions = Translation::where('group', '!=', Translation::IGNORE_FILENAME);
        } else {
            $transitions = Translation::where('group', $group);
        }
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

                return $builderData->whereTranslationLike('value', "%$search%", $key);
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
        $products = Product::whereType(Product::getCorrectChannel($request->type));
        $onlyForChains = $request->has('only-for-chains');
        if ($onlyForChains && $request->input('only-for-chains')) {
            $products = $products->whereNull('branch_id');
        } else {
            $products = $products->whereNotNull('branch_id');
        }
        $products = $products->selectRaw('products.*')
                             ->leftJoin('product_translations', function ($join) {
                                 $join->on('products.id', '=', 'product_translations.product_id');
                             })
                             ->groupBy('products.id');


        $dataTables = DataTables::of($products);
        if ($request->type == 'grocery-product') {

        }

        return $dataTables
            ->editColumn('action', function ($product) use ($onlyForChains) {
                $data = [
                    'editAction' => route('admin.products.edit', [
                        $product->uuid,
                        'type' => request('type'),
                        'only-for-chains' => $onlyForChains ? '1' : '0',
                    ]),
                    'deleteAction' => route('admin.products.destroy', [
                        $product->uuid,
                        'type' => request('type')
                    ]),
                ];

                $deepLinkChannel = config('app.app-channels.grocery');
                if (str_contains(request('type'), 'food')) {
                    $deepLinkChannel = config('app.app-channels.food');
                }

                $data['deepLink'] = [
                    'url' => Controller::generateDeepLink('product_show', [
                        'id' => $product->id,
                        'channel' => $deepLinkChannel
                    ])
                ];

                return view('admin.components.datatables._row-actions', $data)->render();
            })
            ->editColumn('image', function ($product) {
                return view('admin.components.datatables._thumbnails', [
                    'id' => $product->id,
                    'imageUrl' => $product->cover,
                    'imageUrlLarge' => $product->cover_full,
                    'tooltip' => $product->title,
                    'style' => 'height:120px',
                ])->render();
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
            ->editColumn('parent_Category', function ($product) {
                return optional($product->category->parent)->title;
            })->editColumn('child_category', function ($product) {
                $cats = '';
                if ( ! empty($product->categories)) {
                    $product->categories->map(function ($item) use (&$cats) {
                        $cats = $item->title.$cats.' ';
                    });
                }

                return $cats;
            })
            ->editColumn('created_at', function ($product) {
                return view('admin.components.datatables._date', [
                    'date' => $product->created_at
                ])->render();
            })
            ->rawColumns([
                'action',
                'image',
                'chain',
                'branch',
                'category',
                'child_categories',
                'price',
                'created_at',
                'product_deep_link',
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
        $orders = Order::whereType(Order::getCorrectChannel($request->type))
                       ->with([
                           'branch.translations',
                           'ratingIssue.translations',
                           'user',
                       ])
                       ->whereNotNull('rated_at')
                       ->selectRaw('orders.*');

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
                                 'link' => route('admin.orders.show', [$order->id]),
                             ])->render();
                         })
                         ->editColumn('comment', function ($order) {
                             return view('admin.components.datatables._comment', [
                                 'order' => $order,
                             ])->render();
                         })
                         ->editColumn('issue', function ($order) {
                             return optional($order->ratingIssue)->title;
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

    public function paymentMethods(Request $request)
    {
        $paymentMethods = PaymentMethod::selectRaw('payment_methods.*');

        return DataTables::of($paymentMethods)
                         ->editColumn('action', function ($paymentMethod) {
                             $data = [
                                 'modelId' => $paymentMethod->id,
                                 'status' => $paymentMethod->status,
                                 /*'editAction' => route('admin.payment-methods.edit', [
                                     $paymentMethod->id,
                                 ]),
                                 'deleteAction' => route('admin.payment-methods.destroy', [
                                     $paymentMethod->id,
                                 ]),*/
//                                 'permissionModel' => 'payment_method',
                             ];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('status', function ($paymentMethod) {
                             $currentStatus = PaymentMethod::getAllStatusesRich()[$paymentMethod->status];
                             $data = [
                                 'item' => $paymentMethod,
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
                         ->editColumn('updated_at', function ($item) {
                             return view('admin.components.datatables._date', [
                                 'date' => $item->updated_at
                             ])->render();
                         })
                         ->rawColumns([
                             'action',
                             'description',
                             'created_at',
                             'updated_at',
                             'status'
                         ])
                         ->setRowAttr([
                             'row-id' => function ($paymentMethod) {
                                 return $paymentMethod->id;
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
    public function brands(Request $request)
    {
        $brands = Brand::selectRaw('brands.*');

        return DataTables::of($brands)
                         ->editColumn('action', function ($brand) {
                             $data = [
                                 'editAction' => route('admin.brands.edit', [
                                     $brand->id
                                 ]),
                                 'deleteAction' => route('admin.brands.destroy', [
                                     $brand->id
                                 ]),
                             ];

                             return view('admin.components.datatables._row-actions', $data)->render();
                         })
                         ->editColumn('image', function ($brand) {
                             return view('admin.components.datatables._thumbnails', [
                                 'id' => $brand->id,
                                 'imageUrl' => $brand->cover,
                                 'imageUrlLarge' => $brand->cover_full,
                                 'tooltip' => $brand->title,
                                 'style' => 'height:120px',
                             ])->render();
                         })
                         ->editColumn('status', function ($brand) {
                             $currentStatus = Brand::getAllStatusesRich()[$brand->status];
                             $data = [
                                 'item' => $brand,
                                 'currentStatus' => $currentStatus,
                             ];

                             return view('admin.components.datatables._row-actions-status', $data)
                                 ->render();
                         })
                         ->rawColumns([
                             'action',
                             'status',
                             'image'
                         ])
                         ->setRowAttr([
                             'row-id' => function ($brand) {
                                 return $brand->id;
                             }
                         ])
                         ->make(true);
    }


}
