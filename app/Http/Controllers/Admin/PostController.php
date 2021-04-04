<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Taxonomy;
use DB;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:post.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:post.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:post.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:post.permissions.destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return View
     */
    public function index(Request $request)
    {
        $typeName = Post::getCorrectTypeName($request->type, false);

        $columns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => 'ID',
                'width' => '100',
            ],
            [
                'data' => 'title',
                'name' => 'translations.title',
                'title' => 'Title',
                'orderable' => false,
                'searchable' => false
            ],
        ];

        if ($request->type == Post::getCorrectTypeName(Post::TYPE_ARTICLE, false)) {
            $columns = array_merge($columns, [
                [
                    'data' => 'notes',
                    'name' => 'translations.notes',
                    'title' => trans('strings.notes'),
                    'orderable' => false,
                    'searchable' => false
                ],
            ]);
        }

        $columns = array_merge($columns, [
            [
                'data' => 'status',
                'name' => 'status',
                'title' => 'Status',
            ],
            [
                'data' => 'created_at',
                'name' => 'created_at',
                'title' => trans('strings.create_date')
            ],
        ]);

        return view('admin.posts.index', compact('typeName', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     *
     * @return View
     */
    public function create(Request $request)
    {
        $data = $this->essentialData($request);
        $data['post'] = new Post();

        return view('admin.posts.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $defaultLocale = localization()->getDefaultLocale();
        $validationRules = [
            "{$defaultLocale}.title" => 'required',
        ];
        $request->validate($validationRules);

        $correctType = Post::getCorrectType($request->type);

        $previousOrderValue = Post::orderBy('order_column', 'ASC')->first();
        $order = is_null($previousOrderValue) ? 1 : $previousOrderValue->order_column + 1;
        DB::beginTransaction();
        $post = new Post();
        $post->creator_id = $post->editor_id = auth()->id();
        $post->type = $correctType;
        $post->category_id = $request->category_id;
        $post->order_column = $order;
        $post->save();

        // Filling translations
        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                $post->translateOrNew($key)->title = $request->input($key.'.title');
                $post->translateOrNew($key)->content = $request->input($key.'.content');
                $post->translateOrNew($key)->excerpt = $request->input($key.'.excerpt');
                $post->translateOrNew($key)->notes = $request->input($key.'.notes');
            }
        }

        $post->save();

        $post->tags()->sync($request->tags);

        $this->handleSubmittedSingleMedia('cover', $request, $post);

        $this->handleSubmittedMedia($request, 'gallery', $post, 'gallery');

        DB::commit();

        return redirect()
            ->route('admin.posts.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Added successfully',
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Post  $post
     *
     * @param  Request  $request
     *
     * @return Factory|View
     */
    public function edit(Post $post, Request $request)
    {
        $data = $this->essentialData($request);
        $data['post'] = $post;

        return view('admin.posts.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Post  $post
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Post $post)
    {
        $defaultLocale = localization()->getDefaultLocale();
        $validationRules = [
            "{$defaultLocale}.title" => 'required',
        ];
        $request->validate($validationRules);

        $correctType = Post::getCorrectType($request->type);

        DB::beginTransaction();
        $post->category_id = $request->category_id;

        // Filling translations
        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                $post->translateOrNew($key)->title = $request->input($key.'.title');
                $post->translateOrNew($key)->content = $request->input($key.'.content');
                $post->translateOrNew($key)->excerpt = $request->input($key.'.excerpt');
                $post->translateOrNew($key)->notes = $request->input($key.'.notes');
            }
        }

        $post->save();

        $post->tags()->sync($request->tags);

        $this->handleSubmittedSingleMedia('cover', $request, $post);

        $this->handleSubmittedMedia($request, 'gallery', $post, 'gallery');
        // Todo: Handle deleting the images after they've been uploaded if they didn't come back with the request
        DB::commit();

        return redirect()
            ->route('admin.posts.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Edited successfully',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post  $post
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Post $post)
    {
        /*todo: handle deleting the media files*/
        $post->delete();

        return back()->with('message', [
            'type' => 'Success',
            'text' => 'Successfully Deleted',
        ]);
    }

    /**
     * @param  Request  $request
     * @return array
     */
    private function essentialData(Request $request): array
    {
        $typeName = Post::getCorrectTypeName($request->type, false);
        $type = Post::getCorrectType($request->type);
        $categories = Taxonomy::postCategories()->get();
        $tags = Taxonomy::postTags()->get();

        return compact('typeName', 'type', 'categories', 'tags');
    }
}
