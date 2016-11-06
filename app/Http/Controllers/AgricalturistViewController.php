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
use App\Library\ImageProcessing\ImageProcessingController;
use Illuminate\Support\Facades\File;



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

 
        $store_path = public_path('uploads'). DIRECTORY_SEPARATOR .
            hash('md5', $id ) . DIRECTORY_SEPARATOR .
            hash('md5', $fieldName). DIRECTORY_SEPARATOR .
            hash('md5', $fieldDate);
        $store_path .= DIRECTORY_SEPARATOR.ImageProcessingController::extraction_path;

        $folder_list =  array_map('basename',File::directories($store_path)); 



        if($user_field) {
            return view('showField',['field'=> $user_field,'processes' => $folder_list]);
        } else {
            abort(404);
        }
    }
}
