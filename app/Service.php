<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'service_image',
        'service_name',
        'service_description',
        'enable_enquiry'
    ];
}
