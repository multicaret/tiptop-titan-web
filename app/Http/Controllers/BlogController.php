<?php

namespace App\Http\Controllers;


use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $posts = Post::articles()->paginate(8);

        return view('frontend.blog.index', compact('posts'));
    }


    /**
     * Display the specified resource.
     *
     * @param  Post  $post
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function show(Post $post)
    {
        $post->viewed();
        $previous = Post::articles()
                        ->where('id', '<', $post->id)
                        ->orderBy('id', 'asc')
                        ->first();
        $next = Post::articles()
                    ->where('id', '>', $post->id)
                    ->orderBy('id', 'asc')
                    ->first();

        return view('frontend.blog.show', compact('post', 'previous', 'next'));
    }

}
