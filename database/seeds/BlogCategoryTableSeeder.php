<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('blog_categories')->insert([
            "published_by" => "1",
            "blog_category_id" => "6610f1bad965c",
            "blog_category_title" => "Uncategory",
            "blog_category_slug" => "uncategory"
        ]);
    }
}
