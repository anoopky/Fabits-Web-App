<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{

    protected $fillable = ['tag', 'post_id'];

    public function post()
    {
        return $this->belongsTo('App\Post');
    }


}
