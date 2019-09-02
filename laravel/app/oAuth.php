<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class oAuth extends Model
{
    protected $table = 'oAuths';

    protected $fillable = ['user_id','token','device_id'];


    public function user()
    {
        return $this->belongsTo('Cartalyst\Sentinel\Users\EloquentUser');
    }
}
