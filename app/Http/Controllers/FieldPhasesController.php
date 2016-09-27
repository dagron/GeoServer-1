<?php
/**
 * Created by PhpStorm.
 * User: soulg
 * Date: 27/9/2016
 * Time: 21:59
 */

namespace App\Http\Controllers;


use App\Field;

class FieldPhasesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($fieldName)
    {
        $user_fields = Field::where('user_id',1)->where('fieldName', $fieldName)->get();
        if($user_fields->count()) {
            return view('fieldPhases',['fields'=> $user_fields]);
        } else {
            abort(404);
        }

    }
}