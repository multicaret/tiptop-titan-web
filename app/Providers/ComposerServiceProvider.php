<?php

namespace App\Providers;

use App\Models\Currency;
use App\Models\Language;
use App\Models\Post;
use App\Models\Preference;
use App\Scopes\ActiveScope;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param  Request  $request
     *
     * @return void
     * @throws \Exception
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

        view()->composer('admin.partials._sidenav', function ($view) {
            $sidenavLinks = [
                [
                    'title' => '',
                    'children' => [
                        [
                            'title' => 'Dashboard',
                            'icon' => 'fas fa-home',
                            'route' => route('admin.index'),
                        ],
                    ]
                ],
                [
                    'title' => 'Taxonomies',
                    'children' => [
                        [
                            'title' => 'Categories',
                            'icon' => 'fas fa-tag',
                            'route' => route('admin.taxonomies.index', [
                                'type' => \App\Models\Taxonomy::getCorrectTypeName(\App\Models\Taxonomy::TYPE_POST_CATEGORY,
                                    false)
                            ]),
                        ],
                        [
                            'title' => 'Tags',
                            'icon' => 'fas fa-tag',
                            'route' => route('admin.taxonomies.index', [
                                'type' => \App\Models\Taxonomy::getCorrectTypeName(\App\Models\Taxonomy::TYPE_TAG,
                                    false)
                            ]),
                        ],
                    ]
                ],
                [
                    'title' => 'Posts',
                    'children' => [
                        [
                            'title' => 'Articles',
                            'icon' => 'fas fa-pencil-alt',
                            'route' => route('admin.posts.index', ['type' => 'article']),
                        ],
                        [
                            'title' => 'Portfolio',
                            'icon' => 'fas fa-image',
                            'route' => route('admin.posts.index', ['type' => 'portfolio']),
                        ],
                        [
                            'title' => 'News',
                            'icon' => 'fas fa-newspaper',
                            'route' => route('admin.posts.index',
                                ['type' => Post::getCorrectTypeName(Post::TYPE_NEWS, false)]),
                        ],
                        [
                            'title' => 'Pages',
                            'icon' => 'fas fa-file',
                            'route' => route('admin.posts.index',
                                ['type' => Post::getCorrectTypeName(Post::TYPE_PAGE, false)]),
                        ],
                        [
                            'title' => 'FAQ',
                            'icon' => 'fas fa-question-circle',
                            'route' => route('admin.posts.index',
                                ['type' => Post::getCorrectTypeName(Post::TYPE_FAQ, false)]),
                        ],
                        [
                            'title' => 'Services',
                            'icon' => 'fas fa-suitcase',
                            'route' => route('admin.posts.index', ['type' => 'service']),
                        ],
                        [
                            'title' => 'Testimonials',
                            'icon' => 'far fa-grin-stars',
                            'route' => route('admin.posts.index',
                                ['type' => Post::getCorrectTypeName(Post::TYPE_TESTIMONIAL_USER, false)]),
                        ],
                    ]
                ],
                [
                    'title' => 'Logistics',
                    'children' => [
                        [
                            'title' => trans('strings.regions'),
                            'icon' => 'fas fa-map-signs',
                            'route' => route('admin.regions.index'),
                        ],
                        [
                            'title' => trans('strings.cities'),
                            'icon' => 'fas fa-city',
                            'route' => route('admin.cities.index'),
                        ],
                    ]
                ],
                [
                    'title' => 'Accounts',
                    'children' => [
                        [
                            'title' => 'Users',
                            'icon' => 'fas fa-user-alt',
                            'route' => route('admin.users.index', ['type' => \App\Models\User::ROLE_USER])
                        ],
                        [
                            'title' => 'Admins',
                            'icon' => 'fas fa-user-astronaut',
                            'route' => route('admin.users.index', ['type' => \App\Models\User::ROLE_ADMIN])
                        ],
                        [
                            'title' => 'Roles',
                            'icon' => 'fas fa-user-cog',
                            'route' => route('admin.roles.index')
                        ]
                    ]
                ],
                [
                    'title' => '',
                    'children' => [
                        [
                            'title' => 'Slides',
                            'icon' => 'fas fa-images',
                            'route' => route('admin.slides.index'),
                        ],
                        [
                            'title' => 'Translations',
                            'icon' => 'fas fa-language',
                            'route' => route('admin.translations.index'),
                        ],
                        [
                            'title' => 'Preferences',
                            'icon' => 'fas fa-cog',
                            'route' => route('admin.preferences.index'),
                        ],
                    ]
                ]
            ];

            $view->with([
                'sidenavLinks' => $sidenavLinks,
                'count' => [
//                    'user_testimonial' => Post::usersTestimonials()->count(),
                ]
            ]);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
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

        $classes[] = str_replace('.', '-', \Route::currentRouteName());

        return implode(' ', $classes);
    }
}
