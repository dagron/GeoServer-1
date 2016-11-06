<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    protected $fillable = [
        'field_id',
        'title', 
        'description',
        'lat',
        'lng'
    ];
} 
