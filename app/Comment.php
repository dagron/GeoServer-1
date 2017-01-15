<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
       public function field()
       {
            return $this->belongsTo('App\StandardField', 'standard_field_id', 'id');
       }
       public function user()
       {
           return $this->belongsTo('App\User');
       }

} 
