<?php

namespace App;

use App\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategory extends Model
{
    protected $table = 'blog_categories';

    public function blogs()
    {
        return $this->hasOne(Blog::class, 'category', 'blog_category_id');
    }
}
