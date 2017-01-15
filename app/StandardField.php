<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StandardField extends Model
{
        /**
        * Get the comments for the field.
        */
        public function comments()
        {
           return $this->hasMany('App\Comment','standard_field_id' , 'id');
        }
}

