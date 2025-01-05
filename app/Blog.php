<?php

namespace App;

use App\User;
use App\BlogCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    protected $table = 'blogs';

    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'category', 'blog_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

}
