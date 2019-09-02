<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facematch extends Model
{
    protected $fillable = ['user_id1','user_id2','user_id','user_ids'];

}
