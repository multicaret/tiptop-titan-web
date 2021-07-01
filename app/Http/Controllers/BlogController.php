<?php

namespace App\Http\Controllers;


use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return Application|Factory|Response|View
     */
    public function index(Request $request)
    {
        $posts = Post::articles()->active()->paginate(8);

        return view('frontend.blog.index', compact('posts'));
    }


    /**
     * Display the specified resource.
     *
     * @param  Post  $post
     *
     * @return Application|Factory|Response|View
     */
    public function show(Post $post)
    {
        $post->viewed();
        $previous = Post::articles()->active()
                        ->where('id', '<', $post->id)
                        ->orderBy('id', 'asc')
                        ->first();
        $next = Post::articles()->active()
                    ->where('id', '>', $post->id)
                    ->orderBy('id', 'asc')
                    ->first();

        return view('frontend.blog.show', compact('post', 'previous', 'next'));
    }

}
