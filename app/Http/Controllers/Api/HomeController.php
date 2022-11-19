<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use App\Models\Member;
use App\Models\Studio;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogPostResource;
use App\Http\Resources\Admin\StudioResource;
use App\Http\Resources\Admin\Course\CourseResource;

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
        // Random 6 posts
        $posts       = BlogPost::inRandomOrder()->take(6)->get();
        
        // Last 3 posts
        $mediacenter = BlogPost::orderBy('id', 'DESC')->take(3)->get();

        // Studio items
        $studio = Studio::all();

        // Last 2 events
        $events = Course::orderBy('id', 'DESC')->take(2)->get();

        // Statistics
        $stats = [
          'members' => Member::count(),
          'memberships' => Member::where([
            'active' => 1,
            'approved' => 1,
          ])->count(),
          'events' => Course::count(),
          'workshops' => Course::count(),
        ];

        return response()->json([
          'posts'       => BlogPostResource::collection($posts),
          'mediacenter' => BlogPostResource::collection($mediacenter),
          'studio'      => StudioResource::collection($studio),
          'events'      => CourseResource::collection($events),
          'stats'       => $stats
        ]);
    }

    
}
