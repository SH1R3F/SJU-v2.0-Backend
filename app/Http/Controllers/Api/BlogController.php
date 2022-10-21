<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogPostResource;

class BlogController extends Controller
{
    public function categories()
    {
      $categories = BlogCategory::where('active', 1)->orderBy('order', 'ASC')->get();

      return response()->json([
        'categories' => $categories
      ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function posts(Request $request)
    {
      $page = $request->page ? intval($request->page) : 1;
      $posts = BlogPost::filter($request)->orderBy('id', 'DESC')->offset($request->perPage * $page)->paginate($request->perPage);

      return BlogPostResource::collection($posts);
    }

    /**
     * Display the specified resource.
     *
     * @param  BlogPost  $post
     * @return \Illuminate\Http\Response
     */
    public function post(BlogPost $post)
    {
      // Post and Related Posts

      $posts = $post->category ? $post->category->posts()->where('id', '!=', $post->id)->orderBy('id', 'DESC')->take(3)->get() : [];

      return response()->json([
        'post' => new BlogPostResource($post),
        'posts' => BlogPostResource::collection($posts)
      ]);
    }

}
