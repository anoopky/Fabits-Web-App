<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = ['conversation_id','message','user_id','status'];

    protected $hidden = [
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo('Cartalyst\Sentinel\Users\EloquentUser');
    }

    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }
}
