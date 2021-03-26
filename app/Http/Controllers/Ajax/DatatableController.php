<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Branch;
use App\Models\Chain;
use App\Models\City;
use App\Models\Post;
use App\Models\Region;
use App\Models\Slide;
use App\Models\Taxonomy;
use App\Models\Translation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class DatatableController extends AjaxController
{
    /**
     * @param  Request  $request
     *
     * @return mixed
     * @throws \Exception
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

                             return "<i>Never</i>";
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
     * @throws \Exception
     */
    public function taxonomies(Request $request)
    {
        $correctType = Taxonomy::getCorrectType($request->type);
        $taxonomies = Taxonomy::orderBy('order_column')
                              ->with('parent')
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
                             'action',
                             'order_column',
                             'created_at'
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
     * @throws \Exception
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
        $slides = Slide::selectRaw('slides.*');

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
            /*->editColumn('begins_at', function ($item) {
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
            })*/
                         ->editColumn('time_left', function ($item) {
                if ( ! is_null($item->expires_at)) {
                    return view('admin.components.datatables._time-left', [
                        'expires_at' => $item->expires_at
                    ])->render();
                }

                return null;
            })
                         ->editColumn('phase', function ($item) {


                             $colorArray = [
                                 'future' => [
                                     'color' => '#0090f0',
                                     'text' => __('strings.future_slide_phase'),
                                     'icon' => 'fas fa-clock'
                                 ],
                                 'active' => [
                                     'color' => '#63d134',
                                     'text' => __('strings.active_slide_phase'),
                                     'icon' => 'fas fa-play'
                                 ],
                                 'over' => [
                                     'color' => '#CE4F4B',
                                     'text' => __('strings.over_slide_phase'),
                                     'icon' => 'fas fa-clipboard-check'
                                 ],
                                 'n/a' => [
                                     'color' => 'black',
                                     'text' => __('strings.always_on'),
                                     'icon' => 'fas fa-infinity'
                                 ],
                             ];
                             $now = Carbon::now();

                             switch ($now) {
                                 case $now->lt($item->begins_at):
                                     $phase = $colorArray['future'];

                                     break;
                                 case $now->lt($item->expires_at):
                                     $phase = $colorArray['active'];
                                     break;
                                 case $now->gt($item->expires_at):
                                     $phase = $colorArray['over'];
                                     break;
                                 case is_null($item->begins_at) || is_null($item->expires_at):
                                     $phase = $colorArray['n/a'];
                                     break;
                             }
                             $color = $phase['color'];
                             $tooltipText = $phase['text'];
                             $icon = $phase['icon'];

                             return "<i class='$icon' data-toggle='tooltip' title=$tooltipText style='color:$color'></i>";
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
                         ->editColumn('thumbnail', function (Slide $item) {
                             $array = $item->getTranslationsArray();
                             foreach ($array as $locale => $value) {
                                 $thumbnails[] = [
                                     'tooltip' => $locale.' - '.$value['alt_tag'],
                                     'image' => $value['image']
                                 ];
                             }

                             return view('admin.components.datatables._thumbnails', [
                                 'thumbnails' => $thumbnails
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
                             'phase',
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
                                     $chain->id,
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

    public function branches(Request $request)
    {
        $branches = Branch::selectRaw('branches.*');

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
            array_push($rawColumns, $key."_value");
            $of->editColumn($key."_value", function ($transition) use ($key) {
                $hasTranslation = $transition->hasTranslation($key);
                $value = $hasTranslation ? $transition->getTranslation($key)->value : '';

                return view('admin.components.datatables._row-transition-value', [
                    'id' => $transition->id,
                    'value' => $value,
                    'localeKey' => $key,
                    'is_empty' => ! $hasTranslation,
                ])->render();
            })->filterColumn($key."_value", function ($builderData, $search) use ($key) {
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

}
