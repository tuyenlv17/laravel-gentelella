<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'movies';
    
    protected $fillable = array('title', 'year', 'plot', 'price', 'dis_price');
}
