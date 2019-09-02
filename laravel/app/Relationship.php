<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    protected $fillable = ['name'];


    public function user()
    {
        return $this->hasMany('App\Post_data', 'post_id');
    }

}
