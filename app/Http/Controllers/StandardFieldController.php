<?php

namespace App\Http\Controllers;

use App\StandardField;
use Hashids\Hashids;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Library\CoordinationExtractor;
use App\Library\Gdal2tiles;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Library\ImageProcessing\ImageProcessingController;
use App\Library\ImageProcessing\BandController;

class StandardFieldController extends Controller
{
    public function uploadfield(Request $request)
    {
        $messages = [
            'required' => trans('errors.required') 
        ];

        $rules = [
            'fieldName' => 'required|max:255',
            'date' => 'required|date',
        ];
        
        $files =  $request->file('field_image');

        $nbr = count($files);
        if($nbr > 0) {
            foreach(range(0, $nbr) as $index) {
                $rules['field_image.' . $index] = 'image';
            }
        } else {
            $rules['field_image'] = 'required|image';
        }

        $this->validate($request,$rules, $messages);
        
        $user_field = StandardField::where('user_id',Auth::user()->id)->where('fieldName', $request->input('fieldName'))->first();
        if($user_field) {
            return Redirect::back()->withErrors(['Field name already exists']);
        }

        $usrid =Auth::user()->id;
        $fieldName = $request->input('fieldName');
        $date = $request->input('date');
        
        $store_path = public_path('uploads') . DIRECTORY_SEPARATOR . 'standard';
       
        $file_index = 0;
        foreach($files as $file) {
            $filename = hash('md5', $usrid . $fieldName . $date . '_' . $file_index ) . '.' . $file->extension();
            $file->move($store_path, $filename);

            $field = new StandardField();
            $field->fieldName = $fieldName;
            $field->date = $date;
            $field->user_id = $usrid;
            $field->image = $filename; 
            $field->save();

            $file_index++;
        }
 
        return redirect('/home-standard');
    }
    public function deleteField(Request $request)
    {
        //delete field
        StandardField::where('user_id',Auth::user()->id)->where('fieldName', $request->input('fieldName'))->delete();
        return redirect()->back();
    }
 
    public function searchField($name)
    {
        $user_fields = StandardField::distinct()->select('fieldName')
                                        ->where('user_id',Auth::user()->id)
                                        ->where('fieldName', 'like', '%'.$name.'%')
                                        ->get();
        return json_encode($user_fields);
    }
    
    /**
     * Delete a field Date
     *
     * @param Request $request
     * @return Redirect
     */
    public function deletefieldDate(Request $request)
    {
        //delete field
        $user_field = StandardField::where('user_id',Auth::user()->id)
                                   ->where('fieldName', $request->input('fieldName'))
                                   ->where('date', $request->input('fieldDate'))
                                   ->delete();
        //if deleted successfully
        if($user_field) {
            //check if other fields in the group exist
            $fields = StandardField::where('user_id',Auth::user()->id)->where('fieldName', $request->input('fieldName'))->get();
            //redirect approprietly
            if($fields->count()) {
                return redirect()->back();
            } else {
                return redirect('/home-standard');
            }
        } else {
            return redirect()->back();
        }
    }
 
    public function uploadfieldDate(Request $request)
    {

        $messages = [
            'required' => trans('errors.required') 
        ];

        $rules = [
            'date' => 'required|date',
        ];
        
        $files =  $request->file('field_image');

        $nbr = count($files);
        if($nbr > 0) {
            foreach(range(0, $nbr) as $index) {
                $rules['field_image.' . $index] = 'image';
            }
        } else {
            $rules['field_image'] = 'required|image';
        }

        $this->validate($request,$rules, $messages);
        
        $user_fields = StandardField::where('user_id',Auth::user()->id)->where('fieldName', $request->input('fieldName'))->get();
       foreach ( $user_fields as $user_field) {
            if($user_field['date'] == $request->input('date')) {
                return Redirect::back()->withErrors(['Field with that date already exists']);
            }
       }

        $usrid =Auth::user()->id;
        $fieldName = $request->input('fieldName');
        $date = $request->input('date');
        
        $store_path = public_path('uploads') . DIRECTORY_SEPARATOR . 'standard';
       
        $file_index = 0;
        foreach($files as $file) {
            $filename = hash('md5', $usrid . $fieldName . $date . '_' . $file_index);
            $file->move($store_path, $filename);

            $field = new StandardField();
            $field->fieldName = $fieldName;
            $field->date = $date;
            $field->user_id = $usrid;
            $field->image = $filename; 
            $field->save();

            $file_index++;
        }
 
        return redirect('/standard/fieldPhases/'.$fieldName);
    }
 

}
