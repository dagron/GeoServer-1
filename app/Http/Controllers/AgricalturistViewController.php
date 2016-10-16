<?php
/**
 * Created by PhpStorm.
 * User: soulg
 * Date: 1/10/2016
 * Time: 22:52
 */

namespace App\Http\Controllers;


use App\Field;
use Hashids\Hashids;

class AgricalturistViewController extends Controller
{
    /**
     * Id hasher
     * @var Hashids
     */
    protected $hashids;

    /**
     * AgricalturistViewController constructor.
     * @param Hashids $hashids
     */
    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }

    /**
     * Return view based on user id
     * @param $hashedId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userFields($hashedId)
    {
        $ids = $this->hashids->decode($hashedId);
        $id = array_shift($ids);
        $user_fields = Field::where('user_id',$id)->get();
        return view('/agriculturistFields',['fields' => $user_fields, 'id' => $hashedId]);
    }

    public function userFieldsPhases($hashedId, $fieldName)
    {
        $ids = $this->hashids->decode($hashedId);
        $id = array_shift($ids);

        $user_fields = Field::where('user_id',$id)->where('fieldName', $fieldName)->get();
        if($user_fields->count()) {
            return view('agriculturistFieldsPhases',['fields'=> $user_fields, 'id' => $hashedId]);
        } else {
            abort(404);
        }
    }

    public function showField($hashedId, $fieldName, $fieldDate)
    {
        $ids = $this->hashids->decode($hashedId);
        $id = array_shift($ids);

        $user_field = Field::where('user_id',$id)
            ->where('fieldName', $fieldName)
            ->where('date',$fieldDate)
            ->first();
        if($user_field) {
            return view('showfield',['field'=> $user_field]);
        } else {
            abort(404);
        }
    }
}