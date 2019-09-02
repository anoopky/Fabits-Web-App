<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupList extends Model

{


    protected $fillable = ['id','user_id','conversationsV2_id','status','lastseen','lastread','lastDeleted'];

    public function user()
    {
        return $this->belongsTo('Cartalyst\Sentinel\Users\EloquentUser','user_id');
    }
}
