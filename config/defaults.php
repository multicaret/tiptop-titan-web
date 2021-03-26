<?php
return [
    'user' => [
        'mobile_app' => [
            'version' => "0.0.0",
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
    ],
    'region' => [
        'id' => 2,
        'english_name' => 'Erbil',
    ],
    'city' => [
        'id' => 1,
        'english_name' => 'Italian Village',
    ],
    'images' => [
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
        'address_work_icon' => '/images/address-work-icon.png',
        'address_work_marker_icon' => '/images/address-work-marker-icon.png',
        'address_other_icon' => '/images/address-other-icon.png',
        'address_other_marker_icon' => '/images/address-other-marker-icon.png',
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
        'generic_cover' => [
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
    'date' => [
        'short_format' => 'Y-m-d',
        'normal_format' => 'd M Y',
    ],
    'geolocation' => [
        'latitude' => 36.195238,
        'longitude' => 43.993914,
    ],
    'workingHours' => [
        'opensAt' => "09:00",
        'closesAt' => "23:00",
        'weekends' => [6, 0],
    ],
    'colors' => [
        'chain_primary_color' => '#d82518',
        'chain_secondary_color' => '#F9CB39',
        'qr_code_forecolor' => '#000000',
        'qr_code_backcolor' => '#FFFFFF',
    ],
    'social_media_providers' => [
        'facebook' => ['id' => null, 'url' => null],
        'instagram' => ['id' => null, 'url' => null],
        'twitter' => ['id' => null, 'url' => null],
        'google' => ['id' => null, 'url' => null],
    ],
    'all_permission' => [
        'super' => [
            'posts' => [
                'index' => 'post.permissions.index',
                'create' => 'post.permissions.create',
                'edit' => 'post.permissions.edit',
                'destroy' => 'post.permissions.destroy',
            ],
            'users' => [
                'index' => 'user.permissions.index',
                'create' => 'user.permissions.create',
                'edit' => 'user.permissions.edit',
                'destroy' => 'user.permissions.destroy',
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
            'taxonomies' => [
                'index' => 'taxonomy.permissions.index',
                'create' => 'taxonomy.permissions.create',
                'edit' => 'taxonomy.permissions.edit',
                'destroy' => 'taxonomy.permissions.destroy',
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
            'translations' => [
                'index' => 'translation.permissions.index',
                'edit' => 'translation.permissions.edit',
            ],
        ],
        'admin' => [],
        'user' => [],
        'supervisor' => [],
        'agent' => [],
        'content_editor' => [],
        'marketer' => [],
        'branch_owner' => [],
        'branch_manager' => [],
        'editor' => [],
        'translator' => [],
        'restaurant_driver' => [],
        'tiptop_driver' => [],
    ],
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
        'editor' => [
            'name' => 'Editor',
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
];
