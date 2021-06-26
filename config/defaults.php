<?php
return [
    'user' => [
        'default_otp_dummy_host' => 'otp.com',
        'mobile_app_details' => [
            'version' => '0.0.0',
            'buildNumber' => 0,
            'device' => [
                'manufacturer' => '',
                'name' => '',
                'model' => '',
                'platform' => '',
                'serial' => '',
                'uuid' => '',
                'version' => '',
            ],
        ],
        'settings' => [
            'notifications' => [
                'db' => 1,
                'mail' => 1,
                'push_mobile_all' => 1,
                'push_mobile_ads' => 1,
                'push_web_all' => 1,
            ],
        ],
    ],
    'language' => [
        'id' => 1,
        'code' => 'en',
    ],
    'currency' => [
        'id' => 57,
        'code' => 'IQD',
    ],
    'currency_usd' => [
        'id' => 54,
        'code' => 'USD',
    ],
    'country' => [
        'id' => 107,
        'alpha3_code' => 'IRQ',
        'phone_code' => '964',
    ],
    'region' => [
        'id' => 6,
        'english_name' => 'Erbil',
    ],
    'city' => [
        'id' => 4,
        'english_name' => 'Italian Village',
        'erbil_city_other_id' => 140,
        'baghdad_city_other_id' => 141,
    ],
    'images' => [
        'brand_icon' => '/favicon.png',
        'brand_logo' => '/images/logo.jpeg',
        'region_cover' => '/images/region-cover.png',
        'chain_cover' => '/images/region-cover.png',
        'user_avatar' => '/images/user-avatar.png',
        'user_cover' => '/images/user-cover.png',
        'post_cover' => '/images/post-cover.png',
        'taxonomy_cover' => '/images/taxonomy-cover.png',
        'product_cover' => '/images/product-cover.png',
        'slider_image' => '/images/slide.jpeg',
        'slider_image_1' => '/images/slide-1.png',
        'slider_image_2' => '/images/slide-2.png',
        'manufacturer_logo' => '/images/manufacturer-logo.png',
        'barcode_image' => '/images/barcode-image.png',
        'chain_logo' => '/images/chain-logo.png',
        'payment_method_logo' => '/images/payment-method-logo.png',
        'grocery_category_cover' => '/images/grocery-category-cover.png',
        'food_category_cover' => '/images/grocery-category-cover.png',
        'address_home_icon' => '/images/address-home-icon.png',
        'address_home_marker_icon' => '/images/address-home-marker-icon.png',
        'address_home_marker_icon_small' => '/images/address-home-marker-icon-sm.png',
        'address_work_icon' => '/images/address-work-icon.png',
        'address_work_marker_icon_small' => '/images/address-work-marker-icon-sm.png',
        'address_other_icon' => '/images/address-other-icon.png',
        'address_other_marker_icon_small' => '/images/address-other-marker-icon-sm.png',
        'tiptop_marker_icon' => '/images/tiptop-marker-icon.png',
        'tiptop_marker_icon_small' => '/images/tiptop-marker-icon-sm.png',
    ],
    'product_gallery' => [
        "/images/potato.jpeg",
        "/images/strawberry.jpeg",
        "/images/lettuce.jpeg",
    ],
    'image_conversions' => [
        'generic_logo' => [
            '512' => [
                'width' => 512,
                'height' => 512,
            ],
            '1K' => [
                'width' => 1024,
                'height' => 1024,
            ],
        ],
        'icon' => [
            '128' => [
                'width' => 128,
                'height' => 128,
            ],
            '256' => [
                'width' => 256,
                'height' => 256,
            ],
            '512' => [
                'width' => 512,
                'height' => 512,
            ],
        ],
        'generic_cover' => [
            'LD' => [
                'width' => 320,
                'height' => 240,
            ],
            'SD' => [
                'width' => 640,
                'height' => 480,
            ],
            'HD' => [
                'width' => 1280,
                'height' => 720,
            ],
            '1K' => [
                'width' => 1920,
                'height' => 1080,
            ],
            '2K' => [
                'width' => 2560,
                'height' => 1440,
            ],
        ],
        'product_grocery_cover' => [
            'LD' => [
                'width' => 240,
                'height' => 240,
            ],
            'SD' => [
                'width' => 480,
                'height' => 480,
            ],
            'HD' => [
                'width' => 720,
                'height' => 720,
            ],
            '1K' => [
                'width' => 1080,
                'height' => 1080,
            ],
            '2K' => [
                'width' => 1440,
                'height' => 1440,
            ],
        ]
    ],
    'datetime' => [
        'short_format' => 'Y-m-d H:i',
        'normal_format' => 'd M Y H:i',
    ],
    'date' => [
        'short_format' => 'Y-m-d',
        'normal_format' => 'd M Y',
    ],
    'time' => [
        'normal_format' => 'H:i',
    ],
    'geolocation' => [
        'latitude' => 36.195238,
        'longitude' => 43.993914,
        'max_distance_for_food_branches_to_order_from_in_erbil' => 22,
    ],
    'workingHours' => [
        'opensAt' => '09:00:00',
        'closesAt' => '23:00:00',
        'weekends' => [6, 7],
    ],
    'colors' => [
        'chain_primary_color' => '#ffb200',
        'chain_secondary_color' => '#293351',
        'qr_code_forecolor' => '#000000',
        'qr_code_backcolor' => '#FFFFFF',
    ],
    'social_media_providers' => [
        'facebook' => ['id' => null, 'url' => null],
        'instagram' => ['id' => null, 'url' => null],
        'twitter' => ['id' => null, 'url' => null],
        'google' => ['id' => null, 'url' => null],
    ],
    'all_permission' => [],
    'roles' => [
        'super' => [
            'name' => 'Super',
        ],
        'admin' => [
            'name' => 'Admin',
        ],
        'supervisor' => [
            'name' => 'Supervisor',
        ],
        'agent' => [
            'name' => 'Agent',
        ],
        'content_editor' => [
            'name' => 'Content Editor',
        ],
        'marketer' => [
            'name' => 'Marketer',
        ],
        'branch_owner' => [
            'name' => 'Branch Owner',
        ],
        'branch_manager' => [
            'name' => 'Branch Manager',
        ],
        'translator' => [
            'name' => 'Translator',
        ],
        'restaurant_driver' => [
            'name' => 'Restaurant Driver',
        ],
        'tiptop_driver' => [
            'name' => 'Tiptop Driver',
        ],
        'user' => [
            'name' => 'User',
        ],
    ],
    'db_column_types' => [
        'bigint' => 'number',
        'decimal' => 'number',
        'datetime' => 'datetime-local',
        'float' => 'number',
        'boolean' => 'checkbox',
        'integer' => 'number',
        'string' => 'text',
        'text' => 'editor',
        'smallint' => 'number',
    ],
    /*
        adj_fallback=https%3A%2F%2Ftrytiptop.app
        adj_redirect_macos=https%3A%2F%2Ftrytiptop.app
         * */
    'adjust_trackers' => [
        'blog_index' => [
            'params' => [
                'adj_t' => 'sek5use',
                'campaign' => null,
                'adgroup' => null,
                'creative' => null,
            ],
        ],
        'blog_show' => [
            'params' => [
                'adj_t' => '6j6msza',
                'campaign' => null,
                'adgroup' => null,
                'creative' => null,
                'id' => '1',
            ],
        ],
        'addresses' => [
            'params' => [
                'adj_t' => 'vt24507',
                'campaign' => null,
                'adgroup' => null,
                'creative' => null,
            ],
        ],
        'favorites' => [
            'params' => [
                'adj_t' => '793c8og',
                'campaign' => null,
                'adgroup' => null,
                'creative' => null,
                'channel' => config('app.app-channels.grocery'),
            ],
        ],
        'home_screen_by_channel' => [
            'params' => [
                'adj_t' => 'wtk63gn',
                'campaign' => null,
                'adgroup' => null,
                'creative' => null,
                'channel' => config('app.app-channels.grocery'),
            ],
        ],
        'market_food_category_show' => [
            'params' => [
                'adj_t' => '657i79u',
                'campaign' => null,
                'adgroup' => null,
                'creative' => null,
                'channel' => config('app.app-channels.grocery'),
                'parent_id' => 1,
                'id' => 1,
            ],
        ],
        'food_category_show' => [
            'params' => [
//                'adj_t' => '657i79u',
                'campaign' => null,
                'adgroup' => null,
                'creative' => null,
                'channel' => config('app.app-channels.food'),
//                'parent_id' => 1,
                'id' => 1,
            ],
        ],
        'order_rating' => [
            'params' => [
                'adj_t' => 'et3lk95',
                'campaign' => null,
                'adgroup' => null,
                'creative' => null,
                'channel' => config('app.app-channels.grocery'),
                'id' => 1,
            ],
        ],
        'order_tracking' => [
            'params' => [
                'adj_t' => 'd3aoyg1',
                'campaign' => null,
                'adgroup' => null,
                'creative' => null,
                'channel' => config('app.app-channels.grocery'),
                'id' => 1,
            ],
        ],
        'previous_orders' => [
            'params' => [
                'adj_t' => 'ivl1knb',
                'campaign' => null,
                'adgroup' => null,
                'creative' => null,
                'channel' => config('app.app-channels.food'),
            ],
        ],
        'product_show' => [
            'params' => [
                'adj_t' => '8b7qe4e',
                'campaign' => null,
                'adgroup' => null,
                'creative' => null,
                'channel' => config('app.app-channels.food'),
                'id' => 1,
            ],
        ]
    ],
    'permissions' => [
        'grocery_chain_chains' => [
            'index' => 'grocery-chain.permissions.index',
            'create' => 'grocery-chain.permissions.create',
            'edit' => 'grocery-chain.permissions.edit',
            'destroy' => 'grocery-chain.permissions.destroy',
        ],
        'food_chain_chains' => [
            'index' => 'food-chain.permissions.index',
            'create' => 'food-chain.permissions.create',
            'edit' => 'food-chain.permissions.edit',
            'destroy' => 'food-chain.permissions.destroy',
        ],
        'grocery_branch_branches' => [
            'index' => 'grocery-branch.permissions.index',
            'create' => 'grocery-branch.permissions.create',
            'edit' => 'grocery-branch.permissions.edit',
            'destroy' => 'grocery-branch.permissions.destroy',
        ],
        'food_branch_branches' => [
            'index' => 'food-branch.permissions.index',
            'create' => 'food-branch.permissions.create',
            'edit' => 'food-branch.permissions.edit',
            'destroy' => 'food-branch.permissions.destroy',
        ],
        'restaurants' => [
            'create' => 'restaurants.permissions.create',
        ],
        'grocery_product_products' => [
            'index' => 'grocery-product.permissions.index',
            'create' => 'grocery-product.permissions.create',
            'edit' => 'grocery-product.permissions.edit',
            'destroy' => 'grocery-product.permissions.destroy',
        ],
        'food_product_products' => [
            'index' => 'food-product.permissions.index',
            'create' => 'food-product.permissions.create',
            'edit' => 'food-product.permissions.edit',
            'destroy' => 'food-product.permissions.destroy',
        ],
        'typed_taxonomies' => [
            'index' => 'type-taxonomy.permissions.index',
            'create' => 'type-taxonomy.permissions.create',
            'edit' => 'type-taxonomy.permissions.edit',
            'destroy' => 'type-taxonomy.permissions.destroy',
        ],
        'rating_grocery_orders' => [
            'index' => 'rating-grocery-order.permissions.index',
            'create' => 'rating-grocery-order.permissions.create',
            'edit' => 'rating-grocery-order.permissions.edit',
            'destroy' => 'rating-grocery-order.permissions.destroy',
        ],
        'slides' => [
            'index' => 'slide.permissions.index',
            'create' => 'slide.permissions.create',
            'edit' => 'slide.permissions.edit',
            'destroy' => 'slide.permissions.destroy',
        ],
        'typed_posts' => [
            'index' => 'type-post.permissions.index',
            'create' => 'type-post.permissions.create',
            'edit' => 'type-post.permissions.edit',
            'destroy' => 'type-post.permissions.destroy',
        ],
        'typed_users' => [
            'index' => 'type-user.permissions.index',
            'create' => 'type-user.permissions.create',
            'edit' => 'type-user.permissions.edit',
            'destroy' => 'type-user.permissions.destroy',
        ],
        'coupons' => [
            'index' => 'coupon.permissions.index',
            'create' => 'coupon.permissions.create',
            'edit' => 'coupon.permissions.edit',
            'destroy' => 'coupon.permissions.destroy',
        ],
        'daily_report' => [
            'index' => 'daily-report.permissions.index',
            'create' => 'daily-report.permissions.create',
            'edit' => 'daily-report.permissions.edit',
            'destroy' => 'daily-report.permissions.destroy',
        ],
        'countries' => [
            'index' => 'country.permissions.index',
            'create' => 'country.permissions.create',
            'edit' => 'country.permissions.edit',
            'destroy' => 'country.permissions.destroy',
        ],
        'cities' => [
            'index' => 'city.permissions.index',
            'create' => 'city.permissions.create',
            'edit' => 'city.permissions.edit',
            'destroy' => 'city.permissions.destroy',
        ],
        'regions' => [
            'index' => 'region.permissions.index',
            'create' => 'region.permissions.create',
            'edit' => 'region.permissions.edit',
            'destroy' => 'region.permissions.destroy',
        ],
        'offers' => [
            'index' => 'offer.permissions.index',
            'create' => 'offer.permissions.create',
            'edit' => 'offer.permissions.edit',
            'destroy' => 'offer.permissions.destroy',
        ],
        'payment_methods' => [
            'index' => 'payment_method.permissions.index',
            'create' => 'payment_method.permissions.create',
            'edit' => 'payment_method.permissions.edit',
            'destroy' => 'payment_method.permissions.destroy',
        ],
        'preferences' => [
            'index' => 'preference.permissions.index',
            'create' => 'preference.permissions.create',
            'edit' => 'preference.permissions.edit',
            'destroy' => 'preference.permissions.destroy',
        ],
        'roles' => [
            'index' => 'role.permissions.index',
            'create' => 'role.permissions.create',
            'edit' => 'role.permissions.edit',
            'destroy' => 'role.permissions.destroy',
        ],
        'tookan_teams' => [
            'index' => 'tookan-team.permissions.index',
            'create' => 'tookan-team.permissions.create',
            'edit' => 'tookan-team.permissions.edit',
            'destroy' => 'tookan-team.permissions.destroy',
        ],
        'translations' => [
            'index' => 'translation.permissions.index',
            'edit' => 'translation.permissions.edit',
        ],
        'orders' => [
            'index' => 'order.permissions.index',
            'show' => 'order.permissions.show',
        ],
        'deep_links' => [
            'index' => 'deep-link.permissions.index',
        ],
    ],
    'general_roles_exceptions' => [
        'typed_taxonomies',
        'typed_posts',
        'typed_users',
        'countries',
        'offers',
    ],
    'order_column_models' => [
        'cities',
        'regions',
        'slides',
    ]
];
