<?php
/**
 * Created by PhpStorm.
 * User: soulg
 * Date: 27/9/2016
 * Time: 21:59
 */

namespace App\Http\Controllers;


use App\Field;
use Illuminate\Support\Facades\Auth;

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
     * Return View with the field phases
     *
     * @return \Illuminate\Http\Response
     */
    public function index($fieldName)
    {
        $user_fields = Field::where('user_id',Auth::user()->id)->where('fieldName', $fieldName)->get();
        if($user_fields->count()) {
            return view('fieldPhases',['fields'=> $user_fields]);
        } else {
            abort(404);
        }

    }

    /**
     * Return create Field date view with field data
     * @param $fieldName
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($fieldName)
    {
        $user_field = Field::where('user_id',Auth::user()->id)->where('fieldName', $fieldName)->first();
        if($user_field) {
            return view('createFieldDate',['field'=> $user_field]);
        } else {
            abort(404);
        }
    }

    /**
     * Return show field template with the field data
     * @param $fieldName
     * @param $fieldDate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($fieldName, $fieldDate)
    {
        $user_field = Field::where('user_id',Auth::user()->id)
                            ->where('fieldName', $fieldName)
                            ->where('date',$fieldDate)
                            ->first();
        if($user_field) {
            return view('showField',['field'=> $user_field]);
        } else {
            abort(404);
        }
    }
}
