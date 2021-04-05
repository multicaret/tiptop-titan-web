<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\Chain;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Order;
use App\Models\Post;
use App\Models\Preference;
use App\Models\Product;
use App\Models\Taxonomy;
use App\Models\User;
use App\Scopes\ActiveScope;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Route;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param  Request  $request
     *
     * @return void
     * @throws Exception
     */
    public function boot(Request $request)
    {

        view()->composer('*', function ($view) {
            $preferences = Preference::getAllPluckValueKey();
            $enabledCurrencies = cache()->tags('currencies')->rememberForever("{$this->app->getLocale()}.currencies",
                function () {
                    return Currency::all();
                });


            $authenticatable = auth()->user();
            if ( ! is_null($authenticatable)) {
                $authenticatable = $authenticatable->loadMissing('country');
                /* Todo: handle, if necessary, ->withCount('unreadNotifications')
                 * */
            }

            $view->with([
                'auth' => $authenticatable,
                'bodyClasses' => $this->bodyClasses(),
                'appPreferences' => $preferences,
                'languages' => Language::withoutGlobalScope(ActiveScope::class)->get(),
                'enabledCurrencies' => $enabledCurrencies,
            ]);
        });


        view()->composer('partials.notifications.*', function ($view) {
//            $view->with('notifications', auth()->user()->unreadNotifications);
        });

        view()->composer('admin.*', function ($view) {
            if ( ! is_null($user = auth()->user())) {
                $sidenavLinks = $this->initSidenavLinks();
                // Todo: if you want to build route use buildInParams attribute to add it inside route like 'userId'
                // or if you want to add parameter to route user params attribute as array to build it like '["type" => "faq"]'
                $sidenavLinks = array_merge($sidenavLinks, $this->getAllSideNavLinks());
                $sidenavLinks = $this->workOnActiveItem($sidenavLinks);
//                dd($sidenavLinks);
                $view->with([
                    'sidenavLinks' => $sidenavLinks,
                    'count' => [
//                    'user_testimonial' => Post::usersTestimonials()->count(),
                    ]
                ]);
            }
        });
    }

    private function getAllSideNavLinks(): array
    {
        $links = [];
        $links = array_merge($links, [
            [
                'children' => [
                    [
                        'title' => 'Market',
                        'icon' => 'fas fa-shopping-basket',
                        'routeName' => 'admin.index',
                        'params' => ['type' => 'collapse'],
                        'subChildren' => [
                            [
                                'title' => 'Chains',
                                'icon' => 'fas fa-link',
                                'params' => [
                                    'type' =>
                                        Chain::getCorrectChannelName(Chain::CHANNEL_GROCERY_OBJECT, false),
                                ],
                                'routeName' => 'admin.chains.index',
                            ],
                            [
                                'title' => 'Branches',
                                'icon' => 'fas fa-store-alt',
                                'params' => [
                                    'type' => Branch::getCorrectChannelName(Branch::CHANNEL_GROCERY_OBJECT, false),
                                ],
                                'routeName' => 'admin.branches.index',
                            ],
                            [
                                'title' => 'Products',
                                'icon' => 'fas fa-box-open',
                                'params' => [
                                    'type' => Product::getCorrectChannelName(Product::CHANNEL_GROCERY_OBJECT, false)
                                ],
                                'routeName' => 'admin.products.index',
                            ],
                            [
                                'title' => 'Market Categories',
                                'icon' => 'fas fa-shapes',
                                'params' => [
                                    'type' =>
                                        Taxonomy::getCorrectTypeName(Taxonomy::TYPE_GROCERY_CATEGORY,
                                            false)
                                ],
                                'routeName' => 'admin.taxonomies.index',
                            ],
                            [
                                'title' => 'Units',
                                'icon' => 'fas fa-balance-scale',
                                'params' => [
                                    'type' =>
                                        Taxonomy::getCorrectTypeName(Taxonomy::TYPE_UNIT,
                                            false),
                                ],
                                'routeName' => 'admin.taxonomies.index',
                            ],
                            [
                                'title' => 'Ratings',
                                'icon' => 'fas fa-star',
                                'params' => ['type' => Order::getCorrectChannelName(Order::CHANNEL_GROCERY_OBJECT, false)],
                                'routeName' => 'admin.orders.ratings',
                            ],
                        ]
                    ]
                ]
            ],
            [
                'children' => [
                    [
                        'title' => 'Food',
                        'icon' => 'fas fa-utensils',
                        'routeName' => 'admin.index',
                        'params' => ['type' => 'collapse'],
                        'subChildren' => [
                            [
                                'title' => 'Chains',
                                'icon' => 'fas fa-link',
                                'params' => [
                                    'type' => Chain::getCorrectChannelName(Chain::CHANNEL_FOOD_OBJECT, false),
                                ],
                                'routeName' => 'admin.chains.index',
                            ],
                            [
                                'title' => 'Branches',
                                'icon' => 'fas fa-store-alt',
                                'params' => [
                                    'type' => Branch::getCorrectChannelName(Branch::CHANNEL_FOOD_OBJECT, false),
                                ],
                                'routeName' => 'admin.branches.index',
                            ],
                            [
                                'title' => 'Products',
                                'icon' => 'fas fa-box-open',
                                'params' => ['type' => Product::getCorrectChannelName(Product::CHANNEL_FOOD_OBJECT, false)],
                                'routeName' => 'admin.products.index',
                            ],
                            [
                                'title' => 'Food Categories',
                                'icon' => 'fas fa-shapes',
                                'params' => [
                                    'type' =>
                                        Taxonomy::getCorrectTypeName(Taxonomy::TYPE_FOOD_CATEGORY,
                                            false)
                                ],
                                'routeName' => 'admin.taxonomies.index',
                            ],
                            [
                                'title' => 'Menu Categories',
                                'icon' => 'fas fa-shapes',
                                'params' => [
                                    'type' =>
                                        Taxonomy::getCorrectTypeName(Taxonomy::TYPE_MENU_CATEGORY,
                                            false)
                                ],
                                'routeName' => 'admin.taxonomies.index',
                            ],
                            [
                                'title' => 'Ingredient Categories',
                                'icon' => 'fas fa-shapes',
                                'params' => [
                                    'type' =>
                                        Taxonomy::getCorrectTypeName(Taxonomy::TYPE_INGREDIENT_CATEGORY,
                                            false),
                                ],
                                'routeName' => 'admin.taxonomies.index',
                            ],
                            [
                                'title' => 'Ingredients',
                                'icon' => 'fas fa-leaf',
                                'params' => [
                                    'type' =>
                                        Taxonomy::getCorrectTypeName(Taxonomy::TYPE_INGREDIENT,
                                            false),
                                ],
                                'routeName' => 'admin.taxonomies.index',
                            ],
                        ]
                    ]
                ]
            ],
            [
                'children' => [
                    [
                        'title' => trans('strings.content_management'),
                        'icon' => 'fas fa-newspaper',
                        'routeName' => 'admin.index',
                        'params' => ['type' => 'collapse'],
                        'subChildren' => [
                            [
                                'title' => 'Slides',
                                'icon' => 'fas fa-images',
                                'routeName' => 'admin.slides.index',
                            ],
                            [
                                'title' => 'Articles',
                                'icon' => 'fas fa-pencil-alt',
                                'routeName' => 'admin.posts.index',
                                'params' => ['type' => 'article'],
                            ],
                            /*[
                                'title' => 'Portfolio',
                                'icon' => 'fas fa-image',
                                'routeName' => 'admin.posts.index',
                                'params' => ['type' => 'portfolio'],
                            ],*/
                            /*[
                                'title' => 'News',
                                'icon' => 'fas fa-newspaper',
                                'routeName' => 'admin.posts.index',
                                'params' => ['type' => Post::getCorrectTypeName(Post::TYPE_NEWS, false)],
                            ],*/
                            [
                                'title' => 'Pages',
                                'icon' => 'fas fa-file',
                                'routeName' => 'admin.posts.index',
                                'params' => ['type' => Post::getCorrectTypeName(Post::TYPE_PAGE, false)],
                            ],
                            [
                                'title' => 'FAQ',
                                'icon' => 'fas fa-question-circle',
                                'routeName' => 'admin.posts.index',
                                'params' => ['type' => Post::getCorrectTypeName(Post::TYPE_FAQ, false)],
                            ],
                            /*[
                                'title' => 'Services',
                                'icon' => 'fas fa-suitcase',
                                'routeName' => 'admin.posts.index',
                                'params' => ['type' => 'service'],
                            ],*/
                            /*[
                                'title' => 'Testimonials',
                                'icon' => 'far fa-grin-stars',
                                'routeName' => 'admin.posts.index',
                                'params' => ['type' => Post::getCorrectTypeName(Post::TYPE_TESTIMONIAL_USER, false)],
                            ],*/
                        ]
                    ]
                ]
            ],
            [
                'children' => [
                    [
                        'title' => trans('strings.logistics'),
                        'icon' => 'fas fa-globe',
                        'routeName' => 'admin.index',
                        'params' => ['type' => 'collapse'],
                        'subChildren' => [
                            [
                                'title' => 'Cities',
                                'icon' => 'fas fa-map-signs',
                                'routeName' => 'admin.regions.index',
                            ],
                            [
                                'title' => 'Neighborhoods',
                                'icon' => 'fas fa-city',
                                'routeName' => 'admin.cities.index',
                            ],
                        ]
                    ]
                ]
            ],
            [
                'children' => [
                    [
                        'title' => trans('strings.users'),
                        'icon' => 'fas fa-user-alt',
                        'routeName' => 'admin.index',
                        'params' => ['type' => 'collapse'],
                        'subChildren' => [
                            [
                                'title' => 'End Users',
                                'icon' => 'fas fa-user',
                                'routeName' => 'admin.users.index',
                                'params' => ['type' => User::ROLE_USER],
                            ],
                            [
                                'title' => 'Restaurant Drivers',
                                'icon' => 'fas fa-truck',
                                'routeName' => 'admin.users.index',
                                'params' => ['type' => User::ROLE_RESTAURANT_DRIVER],
                            ],
                            [
                                'title' => 'Tiptop Drivers',
                                'icon' => 'fas fa-car-side',
                                'routeName' => 'admin.users.index',
                                'params' => ['type' => User::ROLE_TIPTOP_DRIVER],
                            ],
                            [
                                'title' => 'Admins',
                                'icon' => 'fas fa-user-shield',
                                'routeName' => 'admin.users.index',
                                'params' => ['type' => User::ROLE_ADMIN],
                            ],
                            [
                                'title' => 'Supervisors',
                                'icon' => 'fas fa-user-secret',
                                'routeName' => 'admin.users.index',
                                'params' => ['type' => User::ROLE_SUPERVISOR],
                            ],
                            [
                                'title' => 'Agents',
                                'icon' => 'fas fa-user-tie',
                                'routeName' => 'admin.users.index',
                                'params' => ['type' => User::ROLE_AGENT],
                            ],
                            [
                                'title' => 'Content Editors',
                                'icon' => 'fas fa-user-edit',
                                'routeName' => 'admin.users.index',
                                'params' => ['type' => User::ROLE_CONTENT_EDITOR],
                            ],
                            [
                                'title' => 'Marketers',
                                'icon' => 'fas fa-users',
                                'routeName' => 'admin.users.index',
                                'params' => ['type' => User::ROLE_MARKETER],
                            ],
                            [
                                'title' => 'Branch Owners',
                                'icon' => 'fas fa-user-plus',
                                'routeName' => 'admin.users.index',
                                'params' => ['type' => User::ROLE_BRANCH_OWNER],
                            ],
                            [
                                'title' => 'Branch Managers',
                                'icon' => 'fas fa-users-cog',
                                'routeName' => 'admin.users.index',
                                'params' => ['type' => User::ROLE_BRANCH_MANAGER],
                            ],
                        ]
                    ]
                ]
            ],
            [
                'children' => [
                    [
                        'title' => trans('strings.misc'),
                        'icon' => 'fas fa-list',
                        'routeName' => 'admin.index',
                        'params' => ['type' => 'collapse'],
                        'subChildren' => [
                            /*[
                                'title' => 'Categories',
                                'icon' => 'fas fa-shapes',
                                'params' => [
                                    'type' =>
                                        \App\Models\Taxonomy::getCorrectTypeName(\App\Models\Taxonomy::TYPE_POST_CATEGORY,
                                            false)
                                ],
                                'routeName' => 'admin.taxonomies.index',
                            ],*/
                            [
                                'title' => 'Roles',
                                'icon' => 'fas fa-user-cog',
                                'routeName' => 'admin.roles.index'
                            ],
                            [
                                'title' => 'Translations',
                                'icon' => 'fas fa-language',
                                'routeName' => 'admin.translations.index',
                            ],
                            [
                                'title' => 'Tags',
                                'icon' => 'fas fa-tag',
                                'params' => [
                                    'type' =>
                                        Taxonomy::getCorrectTypeName(Taxonomy::TYPE_TAG,
                                            false),
                                ],
                                'routeName' => 'admin.taxonomies.index',
                            ],
                            [
                                'title' => 'Search Tags',
                                'icon' => 'fas fa-tag',
                                'params' => [
                                    'type' =>
                                        Taxonomy::getCorrectTypeName(Taxonomy::TYPE_SEARCH_TAGS, //todo: make it search tags
                                            false),
                                ],
                                'routeName' => 'admin.taxonomies.index',
                            ],

                        ]
                    ]
                ]
            ],
            [
                'children' => [
                    [
                        'title' => 'Promotions',
                        'icon' => 'fas fa-chart-line',
                        'routeName' => 'admin.index',
                        'params' => ['type' => 'collapse'],
                        'subChildren' => [
                            [
                                'title' => 'Coupons',
                                'icon' => 'fas fa-ticket-alt',
                                'routeName' => 'admin.coupons.index',
                            ],

                        ]
                    ]
                ]
            ],
            [
                'children' => [
                    [
                        'title' => 'Preferences',
                        'icon' => 'fas fa-cog',
                        'routeName' => 'admin.preferences.index',
                    ],
                ]
            ],
        ]);

        return $links;
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public
    function register()
    {
        //
    }

    private function getSubChildren(array $children)
    {
        return collect($children)->mapWithKeys(function ($item, $key) {
            return [
                $key => [
                    'title' => \Str::title($item['title']),
                    'icon' => $item['icon'],
                    'routeName' => $item['route'],
                ]
            ];
        })->all();
    }


    /**
     * Display the classes for the body element.
     *
     * @param  array  $classes  One or more classes to add to the class list.
     *
     * @return string
     */
    protected function bodyClasses($classes = null)
    {
        if ( ! is_array($classes)) {
            $classes = [];
        }

        $classes[] = auth()->check() ? 'logged-in' : 'guest';

        $classes[] = str_replace('.', '-', Route::currentRouteName());

        return implode(' ', $classes);
    }

    private function initSidenavLinks(): array
    {
        $sidenavLinks = [
            [
                'title' => '',
                'children' => [
                    [
                        'title' => trans('strings.dashboard'),
                        'icon' => 'fas fa-home',
                        'routeName' => 'admin.index',
                    ],
                ]
            ]
        ];

        return $sidenavLinks;
    }


    private function getCan(string $viewName, string $crudAction = 'index'): bool
    {
        $permissionSource = "defaults.all_permission.admin.$viewName.$crudAction";
        $permissionName = config($permissionSource);

        return ! is_null($permissionName) ? auth()->user()->can($permissionName) : false;
    }

    private function workOnActiveItem(array $sidenavLinks): array
    {
        $sidenavLinksCollection = collect($sidenavLinks);
        $sidenavLinksCollection = $sidenavLinksCollection->filter($this->getNoneEmptyItem());
        $sidenavLinksCollection->transform(function ($mainItem) {
            if ( ! empty($mainItem)) {
                $requestParams = \request()->toArray();
                $requestRouteName = \request()->route()->getName();
                $this->updateItemStatus($mainItem['children'], $requestParams, $requestRouteName);

                return $mainItem;
            }
        });

        return $sidenavLinksCollection->toArray();
    }

    private function getRouteValue(array &$sideNavItem): void
    {
        $params = [];
        if (isset($sideNavItem['buildInParams'])) {
            if (is_array($sideNavItem['buildInParams'])) {
                $params = array_merge($params, $sideNavItem['buildInParams']);
            } else {
                array_push($params, $sideNavItem['buildInParams']);
            }
        }
        if (isset($sideNavItem['params'])) {
            $params = array_merge($params, $sideNavItem['params']);
        }

        try {
            $sideNavItem['route'] = route($sideNavItem['routeName'], $params);
        } catch (Exception $e) {
            dd($sideNavItem);
        }
    }


    private function updateItemStatus(&$sidenavItem, array $requestParams, string $requestRouteName)
    {
        $sidenavItem = Arr::where($sidenavItem, $this->getNoneEmptyItem());
        foreach ($sidenavItem as $index => $item) {
            if (isset($item['subChildren']) && count($item['subChildren'])) {
                $this->updateItemStatus($sidenavItem[$index]['subChildren'], $requestParams, $requestRouteName);
            }
            if (isset($item['params'])) {
                $paramsKeysIsEqual = array_keys($item['params']) === array_Keys($requestParams);
                $paramsValuesIsEqual = array_values($item['params']) === array_values($requestParams);
                $paramsIsEqual = $paramsKeysIsEqual && $paramsValuesIsEqual;
                $sidenavItem[$index]['status'] = $paramsIsEqual ? 'active' : '';
            } else {
                $pathIsEqual = $item['routeName'] === $requestRouteName;
                $sidenavItem[$index]['status'] = $pathIsEqual ? 'active' : '';
                if (count(explode('.', $item['routeName'])) > 2) {
                    if (Str::afterLast($item['routeName'], '.') === 'index') {
                        $pathIsEqual = \request()->routeIs(Str::beforeLast($item['routeName'], '.').'.*');
                        $sidenavItem[$index]['status'] = $pathIsEqual ? 'active' : '';
                    }
                }
            }

            $this->getRouteValue($sidenavItem[$index]);
            unset($sidenavItem[$index]['routeName']);
            unset($sidenavItem[$index]['params']);
            unset($sidenavItem[$index]['buildInParams']);
            if (isset($sidenavItem[$index]['subChildren']) && ! count($sidenavItem[$index]['subChildren'])) {
                unset($sidenavItem[$index]);
            }
        }

    }

    private function getNoneEmptyItem(): Closure
    {
        return function ($item) {
            return ! empty($item);
        };
    }
}
