<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    // Allow mass assignment for these attributes
    protected $fillable = [
        'card_id',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'is_always_open',
        'is_display',
    ];
}
