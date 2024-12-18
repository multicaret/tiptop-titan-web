<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TranslationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:translation.permissions.index', ['only' => ['index', 'store']]);
    }

    public function index()
    {
        $columns = [
            [
                'sortBy' => 0,
                'data' => 'id',
                'name' => 'id',
                'title' => 'Id',
                'width' => '100',
            ],
            [
                'sortBy' => 2,
                'data' => 'group',
                'name' => 'group',
                'title' => 'Group',
            ],
            [
                'sortBy' => 1,
                'data' => 'key',
                'name' => 'key',
                'title' => 'Key',
            ],
        ];

        $sortBy = 3;
        $columnsLocales = [];
        foreach (localization()->getSupportedLocales() as $key => $locale) {
            $columnsLocales[] = [
                'sortBy' => $sortBy,
                'data' => $key.'_value',
                'name' => $key.'_value',
                'title' => $locale->name(),
                'searchable' => true,
                'orderable' => false,
            ];
            $sortBy++;
        }

        $columns = array_merge($columns, $columnsLocales);
        usort($columns, function ($a, $b): int {
            return $a['sortBy'] > $b['sortBy'];
        });

        return view('admin.translations.index', compact('columns'));
    }
}
