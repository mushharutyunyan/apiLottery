<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EuroMillions extends Model
{
    protected $fillable = ['date','luckystar','luckystar2','numbers','prize'];
}
