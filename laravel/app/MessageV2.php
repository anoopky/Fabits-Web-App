<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageV2 extends Model
{
    protected $table = 'messagesV2';

    protected $fillable = ['id','user_id','message','conversationsV2_id'];

}
