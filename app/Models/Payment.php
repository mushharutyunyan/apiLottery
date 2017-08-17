<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    const PROCESSING = 1;
    const WRONG = 2;
    const SUCCESS = 3;
    public static $status = [ 1 => 'Processing','Wrong','Success'];
    protected $fillable = ['id','user_id','plan_id','calls','paymentId','cart','status'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function plan()
    {
        return $this->belongsTo('App\Models\Plan');
    }

}
