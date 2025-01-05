<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Page;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\OpenGraph;

class BlogController extends Controller
{
    // Blogs
    public function blogs()
    {
        // Queries
        $blogs = Blog::where('status', 1)->orderBy('created_at', 'desc')->paginate(6);
        $settings = Setting::first();
        $config = DB::table('config')->get();

        // Get page details
        $page = Page::where('page_name', 'home')->where('status', 'active')->get();
        $supportPage = DB::table('pages')->where('page_name', 'footer')->orWhere('page_name', 'contact')->get();

        // Seo Tools
        SEOTools::setTitle('Blogs' . ' - ' . $page[0]->title);
        SEOTools::setDescription('Blogs' . ' - ' . $page[0]->description);

        SEOMeta::setTitle('Blogs' . ' - ' . $page[0]->title);
        SEOMeta::setDescription('Blogs' . ' - ' . $page[0]->description);
        SEOMeta::addMeta('article:section', 'Blogs', 'property');
        SEOMeta::addKeyword([$page[0]->keywords]);

        OpenGraph::setTitle('Blogs' . ' - ' . $page[0]->title);
        OpenGraph::setDescription('Blogs' . ' - ' . $page[0]->description);
        OpenGraph::setUrl(URL::full());
        OpenGraph::addImage([asset($settings->site_logo), 'size' => 300]);

        JsonLd::setTitle('Blogs' . ' - ' . $page[0]->title);
        JsonLd::setDescription('Blogs' . ' - ' . $page[0]->description);
        JsonLd::addImage(asset($settings->site_logo));

        // Return values
        $returnValues = compact('blogs', 'config', 'settings', 'supportPage');

        return view("website.pages.blogs.index", $returnValues);
    }

    // View blog post
    public function viewBlog($slug)
    {
        // Queries
        $blogDetails = Blog::where('slug', $slug)->where('status', 1)->first();
        $settings = Setting::first();
        $config = DB::table('config')->get();

        if ($blogDetails) {
            // Get page details
            $supportPage = DB::table('pages')->where('page_name', 'footer')->orWhere('page_name', 'contact')->get();

            // Recent blogs (except current viewed blog)
            $recentBlogs = Blog::where('slug', '!=', $slug)->where('status', 1)->limit(2)->orderBy('created_at', 'desc')->get();

            // Seo Tools
            SEOTools::setTitle($blogDetails->title);
            SEOTools::setDescription($blogDetails->description);
            SEOTools::addImages(asset($blogDetails->cover_image));

            SEOMeta::setTitle($blogDetails->title);
            SEOMeta::setDescription($blogDetails->description);
            SEOMeta::addMeta('article:section', $blogDetails->title, 'property');
            SEOMeta::addKeyword([$blogDetails->keywords]);

            OpenGraph::setTitle($blogDetails->title);
            OpenGraph::setDescription($blogDetails->description);
            OpenGraph::addProperty('type', 'article');
            OpenGraph::setUrl(url("blog/" . $blogDetails->slug));

            JsonLd::setType('Article');
            JsonLd::setTitle($blogDetails->title);
            JsonLd::setDescription($blogDetails->description);

            // Return values
            $returnValues = compact('blogDetails', 'recentBlogs', 'config', 'settings', 'supportPage');

            return view("website.pages.blogs.view", $returnValues);
        } else {
            abort(404);
        }
    }
}
