<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['activity_type','type','source_id', 'user_id', 'created_at','id'];


    public function user()
    {
        return $this->belongsTo('Cartalyst\Sentinel\Users\EloquentUser');
    }
}
