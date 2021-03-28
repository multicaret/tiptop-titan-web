<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\BranchTranslation;
use App\Models\Chain;
use App\Models\ChainTranslation;
use App\Models\PaymentMethod;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\Preference;
use App\Models\PreferenceTranslation;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Slide;
use App\Models\SlideTranslation;
use App\Models\Taxonomy;
use App\Models\TaxonomyTranslation;
use App\Models\User;
use App\Models\WorkingHour;
use DB;
use File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Str;

class DatabaseSeeder extends Seeder
{
    const DEFAULT_USERS_NUMBER = 50;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->rolesAndPermissions();

        $files = File::allFiles(storage_path('seeders'));
        foreach ($files as $table) {
            $tableName = explode('.', $table->getFilename())[0];
            if (Schema::hasTable($tableName)) {
                DB::table($tableName)->truncate();
                $tableContent = json_decode(file_get_contents($table->getPathname()), 1);
                foreach ($tableContent as $model) {
                    DB::table($tableName)->insert($model);
                }
            }
        }
        Preference::truncate();
        PreferenceTranslation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $appHost = parse_url(config('app.url'))['host'];

        [$super, $admin] = $this->users();
        $this->preferences($appHost);
        $this->posts($super);
        $this->slides($super);
        $this->taxonomies($super);
        $this->chains($super);
        $this->branches($super);
        $this->products($super);
        $this->paymentMethods($super);

        /*
            Todo: This line needs root user privileges,
             so please copy and paste it in your machine of you don't have it already
        DB::unprepared("
            CREATE FUNCTION `DISTANCE_BETWEEN`(lat1 DOUBLE, lon1 DOUBLE, lat2 DOUBLE, lon2 DOUBLE) RETURNS double DETERMINISTIC RETURN ACOS( SIN(lat1*PI()/180)*SIN(lat2*PI()/180) + COS(lat1*PI()/180)*COS(lat2*PI()/180)*COS(lon2*PI()/180-lon1*PI()/180) ) * 6371;
        ");
        */

//        factory(App\Models\User::class, 10)->create();
//        factory(App\Models\Location::class, 10)->create();

        $this->apiAccessTokensSeeder($super, $admin);
        Artisan::call('translation:import');
        echo 'Done 🤤 '.PHP_EOL;
    }

    private function createPreferenceItem($key, $data): void
    {
        $preference = new Preference;
        $preference->key = $key;
        $preference->type = $data['type'];
        if (isset($data['value'])) {
            $preference->translateOrNew(app()->getLocale())->value = $data['value'];
        }
        if (isset($data['notes'])) {
            $preference->notes = $data['notes'];
        }
        if (isset($data['icon'])) {
            $preference->icon = $data['icon'];
        }
        if (isset($data['group_name'])) {
            $preference->group_name = $data['group_name'];
        }
        $preference->save();
    }

    private function taxonomies(User $super)
    {
        $taxonomies = [
            [
                'type' => Taxonomy::TYPE_POST_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'General',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'العام',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Genal',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Fruits & Veggies',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'خضروات وفواكه',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'خضروات وفواكه',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Beverages',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'مشاريب',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'مشاريب',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Baked Goods',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'منتجات فرن',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'منتجات فرن',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Food',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'طعام',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'طعام',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Snacks',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'Snacks',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Snacks',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Ice Cream',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'Ice Cream',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Ice Cream',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Ready to eat',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'جاهز للأكل',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'جاهز للأكل',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Dairy & Deli',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'ألبان و بيض',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Dairy & Deli',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Personal Care',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'عناية شخصية',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'عناية شخصية',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Home Care',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'Home Care',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Home Care',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Home & Living',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'Home & Living',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Home & Living',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Tech',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'Tech',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Tech',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Pet Food',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'Pet Food',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Pet Food',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Baby Care',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'Baby Care',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Baby Care',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Apparel',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'Apparel',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Apparel',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'parent_id' => 2,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Fruits',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'فواكة',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'فواكة',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'parent_id' => 2,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Veges',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'خضروات',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'خضروات',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'parent_id' => 3,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Water',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'ماء',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'ماء',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'parent_id' => 3,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Soda',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'صودا',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'صودا',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'parent_id' => 3,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Juice',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'عصائر',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'عصائر',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'parent_id' => 3,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Ayran & Kefir',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'ألبان وكفر',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'ألبان وكفر',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'parent_id' => 3,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Tea',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'شاي',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'شاي',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'parent_id' => 3,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Coffee',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'قهوة',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'قهوة',
                    ]
                ]
            ],
            [
                'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                'parent_id' => 3,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => '',
                    ],
                    [
                        'locale' => 'ar',
                        'title' => '',
                    ],
                    [
                        'locale' => 'ku',
                        'title' => '',
                    ]
                ]
            ],
            /*            [
                            'type' => Taxonomy::TYPE_GROCERY_CATEGORY,
                            'parent_id' => 4,
                            'translations' => [
                                [
                                    'locale' => 'en',
                                    'title' => '',

                                ],
                                [
                                    'locale' => 'ar',
                                    'title' => '',
                                ],
                                [
                                    'locale' => 'ku',
                                    'title' => '',
                                ]
                            ]
                        ],*/
        ];


        foreach ($taxonomies as $item) {
            $taxonomy = new Taxonomy();
            $taxonomy->type = $item['type'];
            if (array_key_exists('parent_id', $item)) {
                $taxonomy->parent_id = $item['parent_id'];
            } else {
                if ($item['type'] == Taxonomy::TYPE_GROCERY_CATEGORY) {
                    $imageName = str_replace('&_', '_&_', Str::snake($item['translations'][0]['title']));
                    if (File::exists(public_path("/images/product-categories/{$imageName}.png"))) {
                        $taxonomy->addMediaFromUrl(asset("/images/product-categories/{$imageName}.png"))
                                 ->toMediaCollection("cover");
                    } else {
                        var_dump("The image: $imageName not found");
                    }
                }
            }
            $taxonomy->creator_id = $super->id;
            $taxonomy->editor_id = $super->id;
            $taxonomy->status = Taxonomy::STATUS_PUBLISHED;

            $taxonomy->save();
            foreach ($item['translations'] as $translation) {
                $taxonomyTranslation = new TaxonomyTranslation();
                $taxonomyTranslation->taxonomy_id = $taxonomy->id;
                foreach ($translation as $column => $value) {
                    $taxonomyTranslation->$column = $value;
                }
                $taxonomyTranslation->save();
            }
        }
    }

    private function posts(User $super)
    {
        $posts = [
            [
                'type' => Post::TYPE_PAGE,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'About Us',
                        'content' => file_get_contents(storage_path('seeders/static-pages/about-en.html')),
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'من نحن',
                        'content' => file_get_contents(storage_path('seeders/static-pages/about-ar.html')),
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'من نحن',
                        'content' => file_get_contents(storage_path('seeders/static-pages/about-ku.html')),
                    ],
                ]
            ],
            [
                'type' => Post::TYPE_PAGE,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Contact Us',
                        'content' => file_get_contents(storage_path('seeders/static-pages/contact-en.html'))
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'إتصل بنا',
                        'content' => file_get_contents(storage_path('seeders/static-pages/contact-ar.html'))
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Bize Ulaşın',
                        'content' => file_get_contents(storage_path('seeders/static-pages/contact-ku.html'))
                    ],
                ],
            ],
            [
                'type' => Post::TYPE_PAGE,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Privacy Policy',
                        'content' => file_get_contents(storage_path('seeders/static-pages/privacy-en.html'))
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'سياسة الخصوصية',
                        'content' => file_get_contents(storage_path('seeders/static-pages/privacy-ar.html'))
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Gizlilik Politikası',
                        'content' => file_get_contents(storage_path('seeders/static-pages/privacy-ku.html'))
                    ],
                ]
            ],
            [
                'type' => Post::TYPE_PAGE,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Terms & Conditions',
                        'content' => file_get_contents(storage_path('seeders/static-pages/terms-en.html')),
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'الشروط والأحكام',
                        'content' => file_get_contents(storage_path('seeders/static-pages/terms-ar.html')),
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'Şartlar ve Koşullar',
                        'content' => file_get_contents(storage_path('seeders/static-pages/terms-ku.html')),
                    ],
                ]
            ],
            [
                'type' => Post::TYPE_ARTICLE,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'Blog',
                        'content' => file_get_contents(storage_path('seeders/static-pages/blog-en.html')),
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'المدونة',
                        'content' => file_get_contents(storage_path('seeders/static-pages/blog-ar.html')),
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'المدونة',
                        'content' => file_get_contents(storage_path('seeders/static-pages/blog-ku.html')),
                    ],
                ]
            ],
            //FAQ. Put any other posts behind it because its postition doesn't matter
            [
                'type' => Post::TYPE_FAQ,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'adipisicing elit. Ad atque beatae eos impedit quaerat sequi?',
                        'content' => "eligendi hic illo libero maiores pariatur porro vel voluptatem voluptates"
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور؟',
                        'content' => "دو أيوسمود تيمبور ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير أكس أيا كوممودو"
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور؟',
                        'content' => "دو أيوسمود تيمبور ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير أكس أيا كوممودو"
                    ],
                ]
            ],
            [
                'type' => Post::TYPE_FAQ,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'adipisicing elit. Ad atque beatae eos impedit quaerat sequi?',
                        'content' => "eligendi hic illo libero maiores pariatur porro vel voluptatem voluptates"
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور؟',
                        'content' => "دو أيوسمود تيمبور ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير أكس أيا كوممودو"
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور؟',
                        'content' => "دو أيوسمود تيمبور ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير أكس أيا كوممودو"
                    ],
                ]
            ],
            [
                'type' => Post::TYPE_FAQ,
                'translations' => [
                    [
                        'locale' => 'en',
                        'title' => 'adipisicing elit. Ad atque beatae eos impedit quaerat sequi?',
                        'content' => "eligendi hic illo libero maiores pariatur porro vel voluptatem voluptates"
                    ],
                    [
                        'locale' => 'ar',
                        'title' => 'كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور؟',
                        'content' => "دو أيوسمود تيمبور ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير أكس أيا كوممودو"
                    ],
                    [
                        'locale' => 'ku',
                        'title' => 'كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور؟',
                        'content' => "دو أيوسمود تيمبور ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير أكس أيا كوممودو"
                    ],
                ]
            ],
        ];


        foreach ($posts as $item) {
            $post = new Post();
            $post->type = $item['type'];
            $post->creator_id = $super->id;
            $post->editor_id = $super->id;
            $post->status = Post::STATUS_PUBLISHED;
            $post->save();
            foreach ($item['translations'] as $translation) {
                $postTranslation = new PostTranslation();
                $postTranslation->post_id = $post->id;
                foreach ($translation as $column => $value) {
                    $postTranslation->$column = $value;
                }
                $postTranslation->save();
            }
        }
    }

    private function slides(User $super)
    {
        $slides = [
            [
                'title' => 'Slide 1',
                'description' => file_get_contents(storage_path('seeders/slides/slide-en.html')),
                'link_value' => 'https://example.com',
                'link_type' => Slide::TYPE_DEEPLINK,
                'image' => config('defaults.images.slider_image'),
                'translations' => [
                    [
                        'locale' => 'en',
                        'alt_tag' => 'slide-tag',
                    ],
                    [
                        'locale' => 'ar',
                        'alt_tag' => 'slide-tag',
                    ],
                    [
                        'locale' => 'ku',
                        'alt_tag' => 'slide-tag',
                    ],
                ]
            ],
            [
                'title' => 'Slide 2',
                'description' => file_get_contents(storage_path('seeders/slides/slide-en.html')),
                'link_value' => 'https://example.com',
                'link_type' => Slide::TYPE_EXTERNAL,
                'image' => config('defaults.images.slider_image_2'),
                'translations' => [
                    [
                        'locale' => 'en',
                        'alt_tag' => 'slide-tag2',
                    ],
                    [
                        'locale' => 'ar',
                        'alt_tag' => 'slide-tag2',
                    ],
                    [
                        'locale' => 'ku',
                        'alt_tag' => 'slide-tag2',
                    ],
                ]
            ],
        ];


        foreach ($slides as $item) {
            $slide = new Slide();
            $slide->title = $item['title'];
            $slide->description = $item['description'];
            $slide->link_value = $item['link_value'];
            $slide->link_type = $item['link_type'];
            $slide->creator_id = $super->id;
            $slide->editor_id = $super->id;
            $slide->save();
            foreach ($item['translations'] as $translation) {
                $slideTranslation = new SlideTranslation();
                $slideTranslation->slide_id = $slide->id;
                foreach ($translation as $column => $value) {
                    $slideTranslation->$column = $value;
                }
                $slideTranslation->save();
            }

            /*if (isset($item['image'])) {
                $slide->addMediaFromUrl(asset($item['image']))->toMediaCollection("image");
            }*/
        }
    }

    private function rolesAndPermissions(): void
    {
        $roles = config('defaults.roles');
        $allRolesPermissions = config('defaults.all_permission');
        $allRolesPermissions['admin'] = $allRolesPermissions['super'];
        $allRolesPermissions['user'] = Arr::except($allRolesPermissions['super'], ['payment_methods', 'roles']);
        foreach ($allRolesPermissions as $permissionKey => $rolePermissions) {
            $roleName = $roles[$permissionKey];
            $role = Role::create($roleName);
            $allRolesPermissions = [];
            foreach ($rolePermissions as $itemKey => $item) {

                foreach ($item as $permissionName) {
                    if ($permissionKey == 'super') {
                        Permission::create(['name' => $permissionName]);
                    }
                    $permissionId = Permission::where('name', $permissionName)->get()->pluck('id');
                    $allRolesPermissions[] = $permissionId;
                }
            }
            $role->syncPermissions($allRolesPermissions);
        }
    }

    private function preferences($host)
    {
        $preferences = [
            'General Settings' => [
                'type' => 'section',
                'group_name' => 'general_settings',
                'icon' => 'lnr lnr-cog',
                'notes' => 'Website name and description',
                'children' => [
                    'app_title' => [
                        'type' => 'text',
                        'value' => config('app.name'),
                        'notes' => 'This field affects the SEO'
                    ],
                    'app_description' => [
                        'type' => 'textarea',
                        'value' => '',
                        'notes' => 'This field affects the SEO'
                    ],
                ]
            ],
            /*'Appearance Settings' => [
                'type' => 'section',
                'group_name' => 'appearance_settings',
                'icon' => 'lnr lnr-picture',
                'notes' => 'Logo, favicon and appearance settings',
                'children' => [
                    'logo_light' => [
                        'type' => 'file',
                        'value' => '/images/logo-light.png',
                        'notes' => ''
                    ],
                    'logo_dark' => [
                        'type' => 'file',
                        'value' => '/images/logo-dark-2.png',
                        'notes' => ''
                    ],
                    'app_favicon' => [
                        'type' => 'file',
                        'value' => '/assets/images/favicon.png',
                        'notes' => 'Recommended Size 64x64'
                    ],
                ]
            ],*/
            /*'Homepage Related' => [
                'type' => 'section',
                'group_name' => 'home_settings',
                'icon' => 'lnr lnr-home',
                'notes' => 'All Homepage details & settings',
                'children' => [
                    'homepage_youtube_video_id' => [
                        'type' => 'text',
                        'value' => 'n_Cn8eFo7u8',
                        'notes' => ''
                    ],
                ]
            ],*/
            'Contact Details' => [
                'type' => 'section',
                'group_name' => 'contact_details',
                'icon' => 'lnr lnr-phone-handset',
                'notes' => 'Phone, email and other contact settings',
                'children' => [
                    'contact_mobile' => [
                        'type' => 'tel',
                        'value' => '',
                        'notes' => ''
                    ],
                    'contact_phone' => [
                        'type' => 'tel',
                        'value' => '',
                        'notes' => ''
                    ],
                    'contact_phone_whatsApp' => [
                        'type' => 'tel',
                        'value' => '',
                        'notes' => ''
                    ],
                    'contact_email' => [
                        'type' => 'email',
                        'value' => 'info@'.$host,
                        'notes' => ''
                    ],
                    'address' => [
                        'type' => 'text',
                        'value' => '',
                        'notes' => ''
                    ],
                ]
            ],
            'Social Media' => [
                'type' => 'section',
                'group_name' => 'social_media',
                'icon' => 'lnr lnr-select',
                'notes' => 'Facebook, Twitter and other social media settings',
                'children' => [
                    'social_media_facebook' => [
                        'type' => 'url',
                        'value' => 'https://facebook.com/'.strstr($host, '.', true),
                        'notes' => ''
                    ],
                    'social_media_instagram' => [
                        'type' => 'url',
                        'value' => 'https://instagram.com/'.strstr($host, '.', true),
                        'notes' => ''
                    ],
                    'social_media_linkedin' => [
                        'type' => 'url',
                        'value' => 'https://linkedin.com/'.strstr($host, '.', true),
                        'notes' => ''
                    ],
                    'social_media_twitter' => [
                        'type' => 'url',
                        'value' => 'https://twitter.com/'.strstr($host, '.', true),
                        'notes' => ''
                    ]
                ]
            ],
            'Tools & 3rd parties integrations' => [
                'type' => 'section',
                'group_name' => 'tools_integrations',
                'icon' => 'lnr lnr-briefcase',
                'notes' => 'Google Search Engine, Google Analytics & Others',
                'children' => [
                    'google_site_verification' => [
                        'type' => 'text',
                        'value' => '',
                        'notes' => 'Google Webmasters Site Verification'
                    ],
                    'google_analytics' => [
                        'type' => 'text',
                        'value' => '',
                        'notes' => 'Google Analytics ID'
                    ],
                ]
            ],
            /*'Notifications Settings' => [
                'type' => 'section',
                'notes' => 'SMS, Email and other related settings',
                'group_name' => 'notification_settings',
                'icon' => 'lnr lnr-bullhorn',
                'children' => [
                    'sms_verification_content' => [
                        'type' => 'text',
                        'value' => 'Hello and Welcome here is your code %code%',
                        'notes' => 'Please use the following variable within your text message, %code%',
                    ],
                    'mobile_app_needs_approval_message' => [
                        'type' => 'text',
                        'value' => 'Hi %name%, welcome your account is not approved yet, please check back soon :)',
                        'notes' => 'Please use the following variable within your text message, %name%'
                    ],
                    'mobile_app_account_is_suspended' => [
                        'type' => 'text',
                        'value' => 'Dear %name%, your account is suspended @_@',
                        'notes' => 'Please use the following variable within your text message, %name%'
                    ],
                ]
            ],*/
            'Advanced Settings' => [
                'type' => 'section',
                'notes' => 'CSS, JS & codes area',
                'group_name' => 'advanced_settings',
                'icon' => 'lnr lnr-construction',
                'children' => [
                    'custom_css_head' => [
                        'type' => 'textarea',
                        'value' => '',
                        'notes' => 'ONLY css code to be placed at the end of the head tag',
                    ],
                    'custom_code_head' => [
                        'type' => 'textarea',
                        'value' => '',
                        'notes' => 'JS or any other code to be placed at the end of the head tag',
                    ],
                    'custom_code_body' => [
                        'type' => 'textarea',
                        'value' => '',
                        'notes' => 'JS or any other code to be placed at the top of the body tag',
                    ],
                ]
            ],
            'Support' => [
                'type' => 'section',
                'notes' => 'Support section',
                'group_name' => 'support_section',
                'icon' => 'lnr lnr-user',
                'children' => [
                    'support_number' => [
                        'type' => 'text',
                        'value' => '',
                    ],
                ]
            ],
        ];

        echo PHP_EOL.'Inserting Preferences'.PHP_EOL;
        foreach ($preferences as $key => $data) {
            $this->createPreferenceItem($key, $data);
            foreach ($data['children'] as $childKey => $child) {
                $child['group_name'] = $data['group_name'];
                $this->createPreferenceItem($childKey, $child);
                echo '.';
            }
        }
        echo PHP_EOL.'J\'ai fini 🤖 Préférences';
    }

    /**
     * @param $appHost
     *
     * @return array
     */
    private function users()
    {
        $super = User::create([
            'first' => 'Super',
            'last' => 'Admin',
            'username' => 'tiptop',
            'email' => 'info@trytiptop.app',
            'password' => '$2y$10$SPDC0YxllzKLDmehC31ZseuKMa3j4npAf0OSEn0L67Autm2cDbBuK', // tipTitantop
            'language_id' => config('defaults.language.id'),
            'country_id' => config('defaults.country.id'),
            'region_id' => config('defaults.region.id'),
            'city_id' => config('defaults.city.id'),
            'currency_id' => config('defaults.currency.id'),
            'remember_token' => Str::random(10),
            'approved_at' => now(),
            'phone_verified_at' => now(),
            'email_verified_at' => now(),
            'last_logged_in_at' => now(),
        ]);
        $super->assignRole('Super');

        $admin = User::create([
            'first' => 'Admin',
            'last' => 'Demo',
            'username' => 'admin',
            'email' => 'demo@trytiptop.app',
            'password' => '$2y$10$6c61PAC4QYS.45dEgBxGaOgpfOdfg33LyG1OorGSvjOyRCVw.gy6i', // secret
            'language_id' => config('defaults.language.id'),
            'country_id' => config('defaults.country.id'),
            'region_id' => config('defaults.region.id'),
            'city_id' => config('defaults.city.id'),
            'currency_id' => config('defaults.currency.id'),
            'remember_token' => Str::random(10),
            'approved_at' => now(),
            'phone_verified_at' => now(),
            'email_verified_at' => now(),
            'last_logged_in_at' => now(),
        ]);
        $admin->assignRole('Admin');

        return [$super, $admin];
    }


    /**
     * @param $super
     * @param $admin
     */
    private function apiAccessTokensSeeder($super, $admin): void
    {
        exec('php artisan optimize:clear');

        echo PHP_EOL.PHP_EOL.'Use the following access token for '.$super->email.':'.PHP_EOL;
        echo 'Bearer '.$super->createToken('DB seeder device')->plainTextToken.PHP_EOL.PHP_EOL;

        echo PHP_EOL.PHP_EOL.'Use the following access token for '.$admin->email.':'.PHP_EOL;
        echo 'Bearer '.$admin->createToken('DB seeder device')->plainTextToken.PHP_EOL.PHP_EOL;
    }

    private function chains($super)
    {
        $chain = new Chain();
        $chain->creator_id = $super->id;
        $chain->editor_id = $super->id;
        $chain->region_id = config('defaults.region.id');
        $chain->city_id = config('defaults.city.id');
        $chain->currency_id = config('defaults.currency.id');
        $chain->type = Chain::TYPE_GROCERY;
        $chain->primary_phone_number = "+964539551234";
        $chain->secondary_phone_number = "+964539551234";
        $chain->whatsapp_phone_number = "+964539551234";
        $chain->status = Chain::STATUS_PUBLISHED;
        $chain->save();

        foreach (config('localization.supported-locales') as $locale) {
            $translation = new ChainTranslation();
            $translation->chain_id = $chain->id;
            $translation->locale = $locale;
            $translation->title = "TipTop Market";
            $translation->save();
        }
    }

    private function branches($super)
    {
        $branches = [
            'TipTop Market branch 1',
            'TipTop Market branch 2',
            'TipTop Market branch 3',
        ];
        foreach ($branches as $branchIndex => $branchName) {
            $item = new Branch();
            $item->chain_id = 1;
            $item->creator_id = $super->id;
            $item->editor_id = $super->id;
            $item->region_id = config('defaults.region.id');
            $item->city_id = config('defaults.city.id');
            $item->type = Branch::TYPE_GROCERY_BRANCH;

            $item->minimum_order = 500 * ($branchIndex + 1);
            $item->under_minimum_order_delivery_fee = 200 * ($branchIndex + 1);
            $item->fixed_delivery_fee = 2000;
            $item->primary_phone_number = "+964539551234";
            $item->secondary_phone_number = "+964539551234";
            $item->whatsapp_phone_number = "+964539551234";
            $item->latitude = config('defaults.geolocation.latitude') + ($branchIndex / 50);
            $item->longitude = config('defaults.geolocation.longitude') + ($branchIndex / 50);
            $item->status = Branch::STATUS_PUBLISHED;
            $item->save();

            foreach (config('localization.supported-locales') as $locale) {
                $translation = new BranchTranslation();
                $translation->branch_id = $item->id;
                $translation->locale = $locale;
                $translation->title = $branchName;
                $translation->save();
            }

            foreach (Branch::getDefaultWorkingHours() as $defaultWorkingHour) {
                $doctorWorkHour = new WorkingHour();
                $doctorWorkHour->day = $defaultWorkingHour->day;
                $doctorWorkHour->workable_id = $item->id;
                $doctorWorkHour->workable_type = Branch::class;
                $doctorWorkHour->opens_at = $defaultWorkingHour->opens_at;
                $doctorWorkHour->closes_at = $defaultWorkingHour->closes_at;
                $doctorWorkHour->is_day_off = $defaultWorkingHour->is_day_off;
                $doctorWorkHour->save();
            }
        }
    }

    private function products($super)
    {
        $products = [
            [
                'name' => 'Kuzeydan 5L',
                'category_id' => 19,
                'gallery' => config("defaults.product_gallery"),
            ],
            [
                'name' => 'Kuzeydan 1.5L',
                'category_id' => 19,
            ],
            [
                'name' => 'Erikli 5L',
                'category_id' => 19,
            ],
            [
                'name' => 'Erikli 1L',
                'category_id' => 19,
            ],
            [
                'name' => 'Coca-Cola',
                'category_id' => 20,
            ],
            [
                'name' => 'Coca-Cola Sugar-Free',
                'category_id' => 20,
            ],
            [
                'name' => 'Coca-Cola Light',
                'category_id' => 20,
            ],
            [
                'name' => 'Coca-Cola Energy',
                'category_id' => 20,
            ],
            [
                'name' => 'Coca-Cola Lime',
                'category_id' => 20,
            ],
            [
                'name' => 'Coca-Cola 1L',
                'category_id' => 20,
            ],
            [
                'name' => 'Coca-Cola Light 1L',
                'category_id' => 20,
            ],
            [
                'name' => 'Pepsi Coke',
                'category_id' => 20,
            ],
            [
                'name' => 'Pepsi Max',
                'category_id' => 20,
            ],
            [
                'name' => 'Pepsi Twist',
                'category_id' => 20,
            ],
            [
                'name' => 'Cappy Puply Orange 1L',
                'category_id' => 21,
            ],
            [
                'name' => 'Exotic Orange',
                'category_id' => 21,
            ],
            [
                'name' => 'Exotic Lemonada',
                'category_id' => 21,
            ],
            [
                'name' => 'Exotic Orange & Pomegrande',
                'category_id' => 21,
            ],
            [
                'name' => 'Eker Ayran',
                'category_id' => 22,
            ],
            [
                'name' => 'Ayran Glass Bottle',
                'category_id' => 22,
            ],
            [
                'name' => 'Ayran Young Bottle',
                'category_id' => 22,
            ],
            [
                'name' => 'Eker Ayran',
                'category_id' => 22,
            ],
            [
                'name' => 'Activia Papaya & Pumpkin Kefir',
                'category_id' => 22,
            ],
            [
                'name' => 'Imported Bananas',
                'category_id' => 17,
            ],
            [
                'name' => 'Strawberry',
                'category_id' => 17,
            ],
            [
                'name' => 'Tangerines',
                'category_id' => 17,
            ],
            [
                'name' => 'Oranges',
                'category_id' => 17,
            ],
            [
                'name' => 'Juice Oranges',
                'category_id' => 17,
            ],
            [
                'name' => 'Red Apples',
                'category_id' => 17,
            ],
            [
                'name' => 'Granny Smith Apples',
                'category_id' => 17,
            ],
            [
                'name' => 'Santa Maria Pear',
                'category_id' => 17,
            ],
            [
                'name' => 'Gold Kiwi',
                'category_id' => 17,
            ],
            [
                'name' => 'Kiwi',
                'category_id' => 17,
            ],
            [
                'name' => 'Pomegranta',
                'category_id' => 17,
            ],
            [
                'name' => 'Pomegranta Seed',
                'category_id' => 17,
            ],
            [
                'name' => 'Blueberries',
                'category_id' => 17,
            ],
            [
                'name' => 'Cocktail Tomatoes',
                'category_id' => 18,
            ],
            [
                'name' => 'Grape Tomatoes',
                'category_id' => 18,
            ],
            [
                'name' => 'Cucumbers',
                'category_id' => 18,
            ],
            [
                'name' => 'Lemons',
                'category_id' => 18,
            ],
            [
                'name' => 'Thin Peppers',
                'category_id' => 18,
            ],
            [
                'name' => 'Sweet Red Pepper',
                'category_id' => 18,
            ],
            [
                'name' => 'Onions',
                'category_id' => 18,
            ],
        ];

        // foreach filling products
        foreach ($products as $product) {
            $item = new Product();
            $item->creator_id = $item->editor_id = 1;
            $item->chain_id = 1;
            $item->branch_id = rand(1, 3);
            $item->category_id = $product['category_id'];
            $item->unit_id = 1;
            $item->price = rand(1000, 20000);
            $item->price_discount_amount = rand(0, 100);
            $item->price_discount_by_percentage = rand(0, 1);
            $item->available_quantity = rand(1, 5);
            $item->sku = "000SKU123".rand(1, 000);
//            $item->upc = "123456789012";
            $item->is_storage_tracking_enabled = true;
            $item->width = 20.5;
            $item->height = 10.0;
            $item->depth = 5;
            $item->weight = 5.5;
            $item->avg_rating = 4.6;
            $item->rating_count = 3.5;
            $item->view_count = 400;
            $item->status = Product::STATUS_PUBLISHED;
//            $item->price_discount_began_at = ;
//            $item->price_discount_finished_at = ;
//            $item->custom_banner_began_at = ;
//            $item->custom_banner_ended_at = ;
//            $item->on_mobile_grid_tile_weight = ;
            $item->save();
            DB::table('category_product')->insert([
                'category_id' => $product['category_id'],
                'product_id' => $item->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach (config('localization.supported-locales') as $locale) {
                $translation = new ProductTranslation();
                $translation->product_id = $item->id;
                $translation->locale = $locale;
                $translation->title = $product['name'];
                $translation->description = file_get_contents(storage_path('seeders/product_description.html'));
                $translation->save();
            }
            if (array_key_exists("gallery", $product)) {
                $gallery = $product["gallery"];
                foreach ($gallery as $image) {
                    $item->addMediaFromUrl(asset($image))->toMediaCollection("gallery");
                }
            }
        }
    }


    private function paymentMethods($user)
    {
        $paymentMethod = new PaymentMethod();
        $paymentMethod->creator_id = $user->id;
        $paymentMethod->editor_id = $user->id;
        $paymentMethod->title = 'Cash on Delivery';
        $paymentMethod->description = null;
        $paymentMethod->instructions = null;
        $paymentMethod->status = PaymentMethod::STATUS_PUBLISHED;
        $paymentMethod->save();
        $paymentMethod->translateOrNew('ar')->title = 'دفع عند الباب';
        $paymentMethod->save();
        $paymentMethod->addMediaFromUrl(asset('/images/payment-methods/cod.png'))->toMediaCollection('logo');

        $paymentMethod = new PaymentMethod();
        $paymentMethod->creator_id = $user->id;
        $paymentMethod->editor_id = $user->id;
        $paymentMethod->title = 'Credit Card on Delivery';
        $paymentMethod->description = null;
        $paymentMethod->instructions = null;
        $paymentMethod->status = PaymentMethod::STATUS_PUBLISHED;
        $paymentMethod->save();
        $paymentMethod->translateOrNew('ar')->title = 'بطاقة بنك عند الباب';
        $paymentMethod->save();
        $paymentMethod->addMediaFromUrl(asset('/images/payment-methods/ccod.png'))->toMediaCollection('logo');


        $paymentMethod = new PaymentMethod();
        $paymentMethod->creator_id = $user->id;
        $paymentMethod->editor_id = $user->id;
        $paymentMethod->title = 'Mobile Wallet (FastPay)';
        $paymentMethod->description = null;
        $paymentMethod->instructions = null;
        $paymentMethod->status = PaymentMethod::STATUS_INACTIVE;
        $paymentMethod->save();
        $paymentMethod->translateOrNew('ar')->title = 'محفظة فاست باي';
        $paymentMethod->save();
        $paymentMethod->addMediaFromUrl(asset('/images/payment-methods/mobile-gateway-payment.png'))->toMediaCollection('logo');
    }
}
