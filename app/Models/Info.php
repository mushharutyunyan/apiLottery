<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $fillable = [
        'id',
        'name',
        'country',
        'description',
        'country',
        'state',
        'main_min',
        'main_max',
        'main_drawn',
        'bonus_min',
        'bonus_max',
        'bonus_drawn',
        'same_balls',
        'digits',
        'drawn',
        'is_option',
        'option_desc',
    ];

}
