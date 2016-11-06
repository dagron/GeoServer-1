<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Polygon extends Model
{
    protected $fillable = [
        'field_id',
        'polygon_data', 
    ];
}
