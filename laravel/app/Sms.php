<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
    protected $table = 'smss';
    protected $fillable = ['user_id','login','message','notification', 'anonymous'];

}
