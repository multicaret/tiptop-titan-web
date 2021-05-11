<?php

namespace App\Utilities;


use App\Models\Post;
use App\Models\Taxonomy;
use Database\Seeders\DatabaseSeeder;

class PermissionsGenerator
{

    public static function getAllRolesPermissions($roleName = null): array
    {
        $roles = config('defaults.roles');
        $defaultPermissions = config('defaults.permissions');
        // Work on role exception
        $rolesExceptions = self::getDefaultExceptions($roles);
        $rolesExceptions = self::addTaxonomiesTypesExceptions($rolesExceptions);
        $rolesExceptions = self::addPostTypesExceptions($rolesExceptions);
        $rolesExceptions = self::addPreferencesExceptions($rolesExceptions);
        $rolesExceptions = self::getCustomExceptions($rolesExceptions);
        // Work on default permissions with models types and preferences sections
        $defaultPermissions = self::addTaxonomiesTypesPermissions($defaultPermissions);
        $defaultPermissions = self::addPostTypesPermissions($defaultPermissions);
        $defaultPermissions = self::addUserTypesPermissions($defaultPermissions, \Arr::pluck($roles,
            'name'));
        $defaultPermissions = self::addPreferencesSectionsPermissions($defaultPermissions);

        $allRolesPermissions = [];
        foreach ($roles as $roleKey => $role) {
            if ( ! \in_array($roleKey, self::rolesWithoutAnyPermissions())) {
                $allRolesPermissions[$roleKey] = \Arr::except($defaultPermissions, $rolesExceptions[$roleKey]);
            } else {
                $allRolesPermissions[$roleKey] = [];
            }
        }

        return ! is_null($roleName) ? $allRolesPermissions[$roleName] : $allRolesPermissions;
    }


    private static function addTaxonomiesTypesPermissions($allRolesPermissions): array
    {
        $tempTaxonomiesTypes = [];
        foreach (\Arr::dot($allRolesPermissions) as $key => $value) {
            if (\Str::contains($key, 'typed_taxonomies')) {
                foreach (Taxonomy::getTypesArray() as $taxonomyType) {
                    $replaceKey = \str_replace('-', '_', $taxonomyType);
                    $tempKey = \str_replace('typed', $replaceKey, $key);
                    $tempValue = \str_replace('type-taxonomy', \Str::kebab($taxonomyType), $value);
                    $tempTaxonomiesTypes[$tempKey] = $tempValue;
                }
            }
        }
        unset($allRolesPermissions['typed_taxonomies']);

        return \array_merge($allRolesPermissions, self::dotArrayToAssociative($tempTaxonomiesTypes));
    }


    private static function addPostTypesPermissions($allRolesPermissions): array
    {
        $tempPostsTypes = [];
        foreach (\Arr::dot($allRolesPermissions) as $key => $value) {
            if (\Str::contains($key, 'typed_posts')) {
                foreach (Post::getTypesArray() as $postType) {
                    $replaceKey = \str_replace('-', '_', $postType);
                    $tempKey = \str_replace('typed', $replaceKey, $key);
                    $tempValue = \str_replace('type-post', \Str::kebab($postType), $value);
                    $tempPostsTypes[$tempKey] = $tempValue;
                }
            }
        }
        unset($allRolesPermissions['typed_posts']);

        return \array_merge($allRolesPermissions, self::dotArrayToAssociative($tempPostsTypes));
    }


    private static function addUserTypesPermissions($allRolesPermissions, $roles): array
    {
        $tempUsersTypes = [];
        foreach (\Arr::dot($allRolesPermissions) as $key => $value) {
            if (\Str::contains($key, 'typed_users')) {
                foreach ($roles as $userType) {
                    $replaceKey = \str_replace('-', '_', \Str::kebab($userType));
                    $tempKey = \str_replace('typed', $replaceKey, $key);
                    $tempValue = \str_replace('type-user', \Str::kebab($userType), $value);
                    $tempUsersTypes[$tempKey] = $tempValue;
                }
            }
        }
        unset($allRolesPermissions['typed_users']);

        return \array_merge($allRolesPermissions, self::dotArrayToAssociative($tempUsersTypes));
    }

    private static function addPreferencesSectionsPermissions($allRolesPermissions): array
    {
        $appHost = parse_url(config('app.url'))['host'];
        $preferences = \collect(DatabaseSeeder::getPreferences($appHost));
        $preferences = $preferences->pluck('group_name');

        $tempPreferencesSections = [];
        foreach (\Arr::dot($allRolesPermissions) as $key => $value) {
            if (\Str::contains($key, 'preferences')) {
                foreach ($preferences as $preferenceKey) {
                    $tempKey = $preferenceKey.'_'.$key;
                    $newValue = \Str::of($preferenceKey)->studly()->kebab()->singular();
                    $tempValue = \str_replace('preference', $newValue, $value);
                    $tempPreferencesSections[$tempKey] = $tempValue;
                }
            }
        }
//        unset($allRolesPermissions['preferences']);

        return \array_merge($allRolesPermissions, self::dotArrayToAssociative($tempPreferencesSections));
    }

    public static function dotArrayToAssociative($dotArray): array
    {
        $array = [];
        foreach ($dotArray as $key => $value) {
            if (strpos($key, '*') === false) {
                \Arr::set($array, $key, $value);
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    public static function addTaxonomiesTypesExceptions(array $rolesExceptions): array
    {
        $taxonomiesTypeBuilder = [
            'super' => [
                Taxonomy::TYPE_POST_CATEGORY,
                Taxonomy::TYPE_RATING_ISSUE,
            ],
            'admin' => [
                Taxonomy::TYPE_POST_CATEGORY,
                Taxonomy::TYPE_RATING_ISSUE,
            ],
            'supervisor' => [
                Taxonomy::TYPE_POST_CATEGORY,
                Taxonomy::TYPE_RATING_ISSUE,
                Taxonomy::TYPE_FOOD_CATEGORY,
                Taxonomy::TYPE_ORDERS_CANCELLATION_REASONS,
                Taxonomy::TYPE_SEARCH_TAGS,
            ],
            'agent' => [
                Taxonomy::TYPE_POST_CATEGORY,
                Taxonomy::TYPE_RATING_ISSUE,
                Taxonomy::TYPE_GROCERY_CATEGORY,
                Taxonomy::TYPE_UNIT,
                Taxonomy::TYPE_FOOD_CATEGORY,
                Taxonomy::TYPE_INGREDIENT,
                Taxonomy::TYPE_INGREDIENT_CATEGORY,
                Taxonomy::TYPE_ORDERS_CANCELLATION_REASONS,
                Taxonomy::TYPE_SEARCH_TAGS,
            ],
            'content_editor' => [
                Taxonomy::TYPE_POST_CATEGORY,
                Taxonomy::TYPE_RATING_ISSUE,
                Taxonomy::TYPE_UNIT,
                Taxonomy::TYPE_ORDERS_CANCELLATION_REASONS
            ],
            'marketer' => [
                Taxonomy::TYPE_POST_CATEGORY,
                Taxonomy::TYPE_RATING_ISSUE,
                Taxonomy::TYPE_GROCERY_CATEGORY,
                Taxonomy::TYPE_UNIT,
                Taxonomy::TYPE_FOOD_CATEGORY,
                Taxonomy::TYPE_MENU_CATEGORY,
                Taxonomy::TYPE_INGREDIENT_CATEGORY,
                Taxonomy::TYPE_INGREDIENT,
                Taxonomy::TYPE_ORDERS_CANCELLATION_REASONS,
                Taxonomy::TYPE_TAG,
                Taxonomy::TYPE_SEARCH_TAGS,
            ],
            'branch_owner' => [
                Taxonomy::TYPE_POST_CATEGORY,
                Taxonomy::TYPE_RATING_ISSUE,
            ],
            'branch_manager' => [
                Taxonomy::TYPE_POST_CATEGORY,
                Taxonomy::TYPE_RATING_ISSUE,
            ],
            'translator' => [
                Taxonomy::TYPE_POST_CATEGORY,
                Taxonomy::TYPE_RATING_ISSUE,
            ],
            'restaurant_driver' => [
                Taxonomy::TYPE_POST_CATEGORY,
                Taxonomy::TYPE_RATING_ISSUE,
            ],
            'tiptop_driver' => [
                Taxonomy::TYPE_POST_CATEGORY,
                Taxonomy::TYPE_RATING_ISSUE,
            ],
            'user' => [
                Taxonomy::TYPE_POST_CATEGORY,
                Taxonomy::TYPE_RATING_ISSUE,
            ],
        ];
        $callback = function ($v) {
            if ( ! empty($v)) {
                return \Str::of(Taxonomy::getCorrectTypeName($v, false))->studly()->snake().'_taxonomies';
            }

            return $v;
        };
        $taxonomiesTypeBuilder = \collect(\Arr::dot($taxonomiesTypeBuilder))->transform($callback)->toArray();
        $taxonomiesTypeBuilder = self::dotArrayToAssociative($taxonomiesTypeBuilder);

        $updateExceptions = function ($v, $k) use ($taxonomiesTypeBuilder) {
            return [$k => \array_merge($v, $taxonomiesTypeBuilder[$k] ?? [])];
        };

        return \collect($rolesExceptions)->mapWithKeys($updateExceptions)->toArray();
    }

    public static function addPostTypesExceptions(array $rolesExceptions): array
    {
        $postTypeBuilder = [
            'super' => [
                Post::TYPE_TESTIMONIAL_USER,
                Post::TYPE_TESTIMONIAL_COMPANY,
                Post::TYPE_PORTFOLIO,
                Post::TYPE_SERVICE,
                Post::TYPE_NEWS,
            ],
            'admin' => [
                Post::TYPE_TESTIMONIAL_USER,
                Post::TYPE_TESTIMONIAL_COMPANY,
                Post::TYPE_PORTFOLIO,
                Post::TYPE_SERVICE,
                Post::TYPE_NEWS,
            ],
            'supervisor' => [
                Post::TYPE_TESTIMONIAL_USER,
                Post::TYPE_TESTIMONIAL_COMPANY,
                Post::TYPE_PORTFOLIO,
                Post::TYPE_SERVICE,
                Post::TYPE_NEWS,
                Post::TYPE_ARTICLE,
                Post::TYPE_PAGE,
                Post::TYPE_FAQ,
            ],
            'agent' => [
                Post::TYPE_TESTIMONIAL_USER,
                Post::TYPE_TESTIMONIAL_COMPANY,
                Post::TYPE_PORTFOLIO,
                Post::TYPE_SERVICE,
                Post::TYPE_NEWS,
                Post::TYPE_ARTICLE,
                Post::TYPE_PAGE,
                Post::TYPE_FAQ,
            ],
            'content_editor' => [
                Post::TYPE_TESTIMONIAL_USER,
                Post::TYPE_TESTIMONIAL_COMPANY,
                Post::TYPE_PORTFOLIO,
                Post::TYPE_SERVICE,
                Post::TYPE_NEWS,
                Post::TYPE_ARTICLE,
                Post::TYPE_PAGE,
                Post::TYPE_FAQ,
            ],
            'marketer' => [
                Post::TYPE_TESTIMONIAL_USER,
                Post::TYPE_TESTIMONIAL_COMPANY,
                Post::TYPE_PORTFOLIO,
                Post::TYPE_SERVICE,
                Post::TYPE_NEWS,
            ],
            'branch_owner' => [
                Post::TYPE_TESTIMONIAL_USER,
                Post::TYPE_TESTIMONIAL_COMPANY,
                Post::TYPE_PORTFOLIO,
                Post::TYPE_SERVICE,
                Post::TYPE_NEWS,
            ],
            'branch_manager' => [
                Post::TYPE_TESTIMONIAL_USER,
                Post::TYPE_TESTIMONIAL_COMPANY,
                Post::TYPE_PORTFOLIO,
                Post::TYPE_SERVICE,
                Post::TYPE_NEWS,
            ],
            'translator' => [
                Post::TYPE_TESTIMONIAL_USER,
                Post::TYPE_TESTIMONIAL_COMPANY,
                Post::TYPE_PORTFOLIO,
                Post::TYPE_SERVICE,
                Post::TYPE_NEWS,
            ],
            'restaurant_driver' => [
                Post::TYPE_TESTIMONIAL_USER,
                Post::TYPE_TESTIMONIAL_COMPANY,
                Post::TYPE_PORTFOLIO,
                Post::TYPE_SERVICE,
                Post::TYPE_NEWS,
            ],
            'tiptop_driver' => [
                Post::TYPE_TESTIMONIAL_USER,
                Post::TYPE_TESTIMONIAL_COMPANY,
                Post::TYPE_PORTFOLIO,
                Post::TYPE_SERVICE,
                Post::TYPE_NEWS,
            ],
            'user' => [
                Post::TYPE_TESTIMONIAL_USER,
                Post::TYPE_TESTIMONIAL_COMPANY,
                Post::TYPE_PORTFOLIO,
                Post::TYPE_SERVICE,
                Post::TYPE_NEWS,
            ],
        ];
        $callback = function ($v) {
            if ( ! empty($v)) {
                return \Str::of(Post::getCorrectTypeName($v, false))->studly()->snake().'_posts';
            }

            return $v;
        };
        $postTypeBuilder = \collect(\Arr::dot($postTypeBuilder))->transform($callback)->toArray();
        $postTypeBuilder = self::dotArrayToAssociative($postTypeBuilder);

        $updateExceptions = function ($v, $k) use ($postTypeBuilder) {
            return [$k => \array_merge($v, $postTypeBuilder[$k] ?? [])];
        };

        return \collect($rolesExceptions)->mapWithKeys($updateExceptions)->toArray();
    }

    public static function addPreferencesExceptions(array $rolesExceptions): array
    {
        $sectionsGroupsNames = [
            'super' => [],
            'admin' => [],
            'supervisor' => [],
            'agent' => [],
            'content_editor' => [],
            'marketer' => [],
            'branch_owner' => [],
            'branch_manager' => [],
            'translator' => [],
            'restaurant_driver' => [],
            'tiptop_driver' => [],
            'user' => [],
        ];
        $callback = function ($v) {
            if ( ! empty($v)) {
                return $v.'_preferences';
            }

            return $v;
        };
        $sectionsGroupsNames = \collect(\Arr::dot($sectionsGroupsNames))->transform($callback)->toArray();
        $sectionsGroupsNames = self::dotArrayToAssociative($sectionsGroupsNames);
        $updateExceptions = function ($v, $k) use ($sectionsGroupsNames) {
            return [$k => \array_merge($v, $sectionsGroupsNames[$k] ?? [])];
        };

        return \collect($rolesExceptions)->mapWithKeys($updateExceptions)->toArray();
    }

    private static function getDefaultExceptions(array $roles): array
    {
        $rolesExceptions = config('defaults.general_roles_exceptions');

        return \collect($roles)->transform(fn() => $rolesExceptions)->toArray();
    }

    private static function getCustomExceptions(array $rolesExceptions): array
    {
        $tempRolesExceptions = [
            'super' => [],
            'admin' => [],
            'supervisor' => [
                'grocery_chain_chains',
                'slides',
                'cities',
                'tookan_teams',
                'admin_users',
                'marketer_users',
                'roles',
                'payment_methods',
                'daily_report',
                'preferences',
                'general_settings_preferences',
                'contact_details_preferences',
                'social_media_preferences',
                'tools_integrations_preferences',
                'notification_settings_preferences',
                'operation_section_preferences',
                'support_section_preferences',
                'advanced_settings_preferences',
                'deep_links'
            ],
            'agent' => [
                'grocery_chain_chains',
                'slides',
                'regions',
                'cities',
                'tookan_teams',
                'admin_users',
                'supervisor_users',
                'agent_users',
                'content_editor_users',
                'marketer_users',
                'tiptop_driver_users',
                'roles',
                'translations',
                'payment_methods',
                'coupons',
                'daily_report',
                'preferences',
                'general_settings_preferences',
                'contact_details_preferences',
                'social_media_preferences',
                'tools_integrations_preferences',
                'notification_settings_preferences',
                'operation_section_preferences',
                'support_section_preferences',
                'advanced_settings_preferences',
                'deep_links'
            ],
            'content_editor' => [
                'orders',
                'grocery_chain_chains',
                'slides',
                'regions',
                'cities',
                'tookan_teams',
                'super_users',
                'admin_users',
                'supervisor_users',
                'agent_users',
                'content_editor_users',
                'marketer_users',
                'branch_owner_users',
                'branch_manager_users',
                'translator_users',
                'restaurant_driver_users',
                'tiptop_driver_users',
                'user_users',
                'roles',
                'payment_methods',
                'coupons',
                'daily_report',
                'preferences',
                'general_settings_preferences',
                'contact_details_preferences',
                'social_media_preferences',
                'tools_integrations_preferences',
                'notification_settings_preferences',
                'operation_section_preferences',
                'support_section_preferences',
                'advanced_settings_preferences',
                'deep_links'
            ],
            'marketer' => [
                'orders',
                'grocery_chain_chains',
                'food_chain_chains',
                'grocery_branch_branches',
                'food_branch_branches',
                'restaurants',
                'grocery_product_products',
                'food_product_products',
                'rating_grocery_orders',
                'regions',
                'cities',
                'tookan_teams',
                'super_users',
                'admin_users',
                'supervisor_users',
                'agent_users',
                'content_editor_users',
                'marketer_users',
                'branch_owner_users',
                'branch_manager_users',
                'translator_users',
                'restaurant_driver_users',
                'tiptop_driver_users',
                'user_users',
                'roles',
                'translations',
                'payment_methods',
                'general_settings_preferences',
                'contact_details_preferences',
                'social_media_preferences',
                'tools_integrations_preferences',
                'notification_settings_preferences',
                'operation_section_preferences',
                'support_section_preferences',
                'advanced_settings_preferences',
            ],
            'branch_owner' => [],
            'branch_manager' => [],
            'translator' => [],
            'restaurant_driver' => [],
            'tiptop_driver' => [],
            'user' => [],
        ];
        $callback = function ($item, $key) use ($tempRolesExceptions) {
            return [$key => \array_merge($item, $tempRolesExceptions[$key] ?? [])];
        };

        return \collect($rolesExceptions)->mapWithKeys($callback)->toArray();
    }

    public static function rolesWithoutAnyPermissions(): array
    {
        return [
            'branch_owner',
            'branch_manager',
            'translator',
            'restaurant_driver',
            'tiptop_driver',
            'user',
        ];
    }
}
