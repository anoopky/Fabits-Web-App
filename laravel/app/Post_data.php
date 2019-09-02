<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post_data extends Model
{
    protected $fillable = ['source','type','data','post_id'];



    public function post()
  {
    return $this->belongsTo('App\Post');
  }
}
