<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallHistory extends Model
{
    protected $fillable = ['user_id','calls'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
