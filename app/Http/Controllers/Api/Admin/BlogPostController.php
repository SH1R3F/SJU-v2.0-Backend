<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogPostRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Admin\BlogPostResource;

class BlogPostController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-post', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-post', ['only' => 'store']);
        $this->middleware('permission:update-post', ['only' => 'update']);
        $this->middleware('permission:delete-post', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = BlogPost::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);
        return response()->json([
            'total' => BlogPost::filter($request)->count(),
            'posts' => BlogPostResource::collection($posts)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BlogPostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogPostRequest $request)
    {
        $post = BlogPost::create($request->validated());

        // Upload photos
        $photos = [];
        foreach ($request->photos as $photo) {
            if (str_starts_with($photo, 'data:image')) {
                $name = upload_base64_image($photo, "blog/posts/{$post->id}");
                array_push($photos, asset("storage/blog/posts/{$post->id}/{$name}"));
            } else {
                array_push($photos, $photo);
            }
        }
        $post->update(['photos' => $photos]);

        return response()->json([
            'message' => __('messages.successful_create')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  BlogPost  $post
     * @return \Illuminate\Http\Response
     */
    public function show(BlogPost $post)
    {
        return new BlogPostResource($post);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  BlogPostRequest  $request
     * @param  BlogPost  $post
     * @return \Illuminate\Http\Response
     */
    public function update(BlogPostRequest $request, BlogPost $post)
    {
        // Update photos
        $photos = [];
        foreach ($request->photos as $photo) {
            if (str_starts_with($photo, 'data:image')) {
                $name = upload_base64_image($photo, "blog/posts/{$post->id}");
                array_push($photos, asset("storage/blog/posts/{$post->id}/{$name}"));
            } else {
                array_push($photos, $photo);
            }
        }
        $request->merge(['photos' => $photos]);
        $post->update($request->all());

        return response()->json([
            'message' => __('messages.successful_update')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BlogPost  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogPost $post)
    {
        Storage::disk('public')->deleteDirectory("blog/posts/{$post->id}");
        $post->delete();
        return response()->json([
            'message' => __('messages.successful_delete')
        ]);
    }
}
