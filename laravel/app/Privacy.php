<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privacy extends Model
{
    protected $fillable = ['facematch','following','followers','profile_image','phone','message' ,'tags','user_id'];

}
