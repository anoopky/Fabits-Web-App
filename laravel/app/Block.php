<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = ['user_id1','user_id2'];

    public function user()
    {
        return $this->belongsTo('Cartalyst\Sentinel\Users\EloquentUser','user_id2');
    }
}
