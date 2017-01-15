<?php
/**
 * Created by PhpStorm.
 * User: soulg
 * Date: 1/10/2016
 * Time: 22:52
 */

namespace App\Http\Controllers;


use App\Field;
use App\StandardField;
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
        $user_standard_fields = StandardField::distinct()->select('fieldName')->where('user_id',$id)->get();

        return view('/agriculturistFields',['fields' => $user_fields, 'id' => $hashedId, 'standard_fields' => $user_standard_fields]);
    }
    public function userStandardFieldsPhases($hashedid, $fieldname)
    {
        $ids = $this->hashids->decode($hashedid);
        $id = array_shift($ids);

        $user_fields = StandardField::where('user_id',$id)->where('fieldname', $fieldname)->get();
        if($user_fields->count()) {
            return view('agriculturistStandardFieldPhases',['fields'=> $user_fields, 'id' => $hashedid]);
        } else {
            abort(404);
        }
    }


    public function userfieldsphases($hashedid, $fieldname)
    {
        $ids = $this->hashids->decode($hashedid);
        $id = array_shift($ids);

        $user_fields = field::where('user_id',$id)->where('fieldname', $fieldname)->get();
        if($user_fields->count()) {
            return view('agriculturistfieldsphases',['fields'=> $user_fields, 'id' => $hashedid]);
        } else {
            abort(404);
        }
    }

     public function showStandardField($hashedId, $fieldName, $fieldDate)
     {
        $ids = $this->hashids->decode($hashedId);
        $id = array_shift($ids);
     
        $user_field = StandardField::where('user_id',$id)
                            ->where('fieldName', $fieldName)
                            ->where('date',$fieldDate)
                            ->with('comments.user')
                            ->get();

        if($user_field) {
            return view('agriculturistStandardShowField',['asset_folder' => url('uploads' . DIRECTORY_SEPARATOR . 'standard'), 'fields'=> $user_field]);
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
