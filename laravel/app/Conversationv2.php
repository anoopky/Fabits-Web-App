<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversationv2 extends Model
{

    protected $table = 'conversationsV2';

    protected $fillable = ['id','type','name','image'];
}
