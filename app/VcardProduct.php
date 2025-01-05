<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VcardProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge',
        'currency',
        'product_image',
        'product_name',
        'product_subtitle',
        'regular_price',
        'sales_price',
        'product_status'
    ];
}
