<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColumbiaBaloto extends Model
{
    protected $fillable = ['date','numbers','prize','extra_number'];
}
