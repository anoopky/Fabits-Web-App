<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Otp extends Model
{

    protected $fillable = ['user_id','phone','otp','status'];

    public function user()
    {
      return $this->belongsTo('Cartalyst\Sentinel\Users\EloquentUser');
    }

}
