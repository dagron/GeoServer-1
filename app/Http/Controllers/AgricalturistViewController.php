<?php
/**
 * Created by PhpStorm.
 * User: soulg
 * Date: 1/10/2016
 * Time: 22:52
 */

namespace App\Http\Controllers;


use App\Field;

class AgricalturistViewController extends Controller
{

    public function userFields($id)
    {
        $user_fields = Field::where('user_id',$id)->get();
        return view('/agriculturistFields',['fields' => $user_fields]);
    }
}