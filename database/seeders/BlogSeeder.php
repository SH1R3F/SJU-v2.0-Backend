<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Blog Categories Seeding From .sql File
        BlogCategory::unguard();
        $sql = base_path('database/data/blog_categories.sql');
        DB::unprepared(file_get_contents($sql));


        // Blog Posts Seeding From .sql File
        BlogPost::unguard();
        $sql = base_path('database/data/blog_posts.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
