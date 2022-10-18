<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogPostResource;

class HomeController extends Controller
{
    /**
     * Display a listing of the navbar menus.
     *
     * @return \Illuminate\Http\Response
     */
    public function menus()
    {
        $menus = Menu::orderBy('order', 'ASC')->get();
        return response()->json(['menus' => $menus]);
    }

    /**
     * Display a listing of different sections in home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Last 6 posts
        $posts       = BlogPost::inRandomOrder()->take(6)->get();
        $mediacenter = BlogPost::orderBy('id', 'DESC')->take(3)->get();
        return response()->json([
          'posts'       => BlogPostResource::collection($posts),
          'mediacenter' => BlogPostResource::collection($mediacenter)
        ]);
    }

    
}
