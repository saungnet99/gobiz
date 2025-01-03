<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Sitemap\SitemapGenerator;

class SitemapController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    // Generating a sitemap
    public function index()
    {
        // Generating a sitemap
        $path = public_path('sitemap.xml');
        SitemapGenerator::create(url('/'))->writeToFile($path);

        return redirect()->route('admin.settings')->with('success', trans('Sitemap generating successfully!'));
    }
}
