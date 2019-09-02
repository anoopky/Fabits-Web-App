<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{

    protected $fillable = ['user_id1','user_id2','type','status_1','status_2'];


    public function userFrom()
    {
        return $this->belongsTo('Cartalyst\Sentinel\Users\EloquentUser','user_id1');
    }

    public function userTo()
    {
        return $this->belongsTo('Cartalyst\Sentinel\Users\EloquentUser','user_id2');
    }

    public function message()
    {
        return $this->hasMany('App\Message', 'conversation_id');
    }
}
