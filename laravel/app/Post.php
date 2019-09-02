<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sentinel;


class post extends Model
{

    protected $fillable = ['text', 'type_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('Cartalyst\Sentinel\Users\EloquentUser');
    }


    public function post_data()
    {
        return $this->hasMany('App\Post_data', 'post_id');
    }

    public function hashtag()
    {
        return $this->hasMany('App\Hashtag', 'post_id');
    }

    public function comment()
    {
        return $this->hasMany('App\Comment', 'post_id');
    }

    public function like()
    {
        return $this->hasMany('App\Like', 'post_id');
    }

}
