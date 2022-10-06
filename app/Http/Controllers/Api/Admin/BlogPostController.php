<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\BlogPostResource;

class BlogPostController extends Controller
{
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'title_ar'   => 'required',
          'title_en'   => 'required',
          'slug'       => 'required|unique:blog_posts,slug',
          'content_ar' => 'required',
          'content_en' => 'required'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        $post = BlogPost::create($request->all());

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
     * @param  \Illuminate\Http\Request  $request
     * @param  BlogPost  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlogPost $post)
    {
        $validator = Validator::make($request->all(), [
          'title_ar'         => 'required',
          'title_en'         => 'required',
          'blog_category_id' => 'required|exists:blog_categories,id',
          'post_date'        => 'required|date',
          'summary_ar'       => 'required',
          'summary_ar'       => 'required',
          'content_ar'       => 'required',
          'content_ar'       => 'required',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Update photos
        $photos = [];
        foreach ($request->photos as $photo) {
          if (str_starts_with($photo, 'data:image')) {
            // Save to disk
            $base64Image  = explode(";base64,", $photo);
            $explodeImage = explode("image/", $base64Image[0]);
            $imageType    = $explodeImage[1];
            $image_base64 = base64_decode($base64Image[1]);
            $imageName    = uniqid() . '.'.$imageType;
            Storage::disk('public')->put("blog/posts/{$post->id}/{$imageName}", $image_base64);
            array_push($photos, asset("storage/blog/posts/{$post->id}/{$imageName}"));
          } else {
            array_push($photos, $photo);
          }
        }

        $data = $request->all();
        $data['photos'] = $photos;

        $post->update($data);

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
