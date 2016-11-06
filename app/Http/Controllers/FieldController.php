<?php

namespace App\Http\Controllers;

use App\Field;
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

class FieldController extends Controller
{
    /**
     * @var $gda2tiles
     */
    protected $gda2tiles;

    /**
     * @var $coordinatorExtractor
     */
    protected $coordinatorExtractor;

    /**
     * FieldController constructor.
     * @param Gdal2tiles $gda2tiles
     */
    public function __construct(Gdal2tiles $gda2tiles, CoordinationExtractor $coordinationExtractor)
    {
        $this->gda2tiles = $gda2tiles;
        $this->coordinatorExtractor = $coordinationExtractor;
    }

    public function uploadfield(Request $request)
    {
        $messages = [
            'required' => trans('errors.required') 
        ];

        $this->validate($request,[
            'fieldName' => 'required|max:255',
            'date' => 'required|date',
            'field_image' => 'required|mimes:tiff'
        ], $messages);

        $user_field = Field::where('user_id',Auth::user()->id)->where('fieldName', $request->input('fieldName'))->first();
        if($user_field) {
            return Redirect::back()->withErrors(['Field name already exists']);
        }

        $usrid =Auth::user()->id;
        $fieldName = $request->input('fieldName');
        $date = $request->input('date');

        $relative_path = 'uploads/'.
            hash('md5', $usrid) .'/'.
            hash('md5', $fieldName). '/' .
            hash('md5', $date);

        $store_path = public_path('uploads'). DIRECTORY_SEPARATOR .
            hash('md5', $usrid) . DIRECTORY_SEPARATOR .
            hash('md5', $fieldName). DIRECTORY_SEPARATOR .
            hash('md5', $date);

        $file =  $request->file('field_image');
        $file->move($store_path, 'demo.tif');

        $bandController = new BandController();
        $band_count = $bandController->bandCount($store_path . DIRECTORY_SEPARATOR . 'demo.tif');
        if($band_count >= 3) {
            $vrtfilepath = $this->gda2tiles->tifToVrt($store_path, 'demo.tif');
        } else {
            $vrtfilepath = $this->gda2tiles->tifToVrtRGBA($store_path, 'demo.tif');
        }

        
        
        $this->gda2tiles->generateTiles($vrtfilepath,$store_path);

        $coordinates_json_filepath = $this->coordinatorExtractor->extractInfo($store_path . DIRECTORY_SEPARATOR . 'demo.tif',
                                                               $store_path . DIRECTORY_SEPARATOR . 'coordinates.json');

        $coordinates = $this->coordinatorExtractor->extractCoordinates($coordinates_json_filepath);

        $field = new Field();
        $field->fieldFolder =   $relative_path;
        $field->fieldName   =   $fieldName;
        $field->date        =   $date;
        $field->user_id     =   $usrid;
        $field->x_min       =   $coordinates['x_min'];
        $field->x_max       =   $coordinates['x_max'];
        $field->y_min       =   $coordinates['y_min'];
        $field->y_max       =   $coordinates['y_max'];
        $field->save();

        File::makeDirectory($store_path . DIRECTORY_SEPARATOR . 'processes');


        // If field image is not already processed 
        if($request->input('is_processed') != 'is_processed') {
            // do processes 
            $processingController = new ImageProcessingController($store_path . DIRECTORY_SEPARATOR . 'demo.tif',$store_path.DIRECTORY_SEPARATOR);
            //call process
            $processingController->process();
      }
        return redirect('/');
    }

    public function uploadfieldDate(Request $request)
    {
        $messages = [
            'required' => trans('errors.required')
        ];

        $this->validate($request,[
            'date' => 'required|date',
            'field_image' => 'required|mimes:tiff'
        ], $messages);

        $user_fields = Field::where('user_id',Auth::user()->id)->where('fieldName', $request->input('fieldName'))->get();
        foreach ($user_fields as $user_field) {
            if($user_field['date'] == $request->input('date')) {
                return Redirect::back()->withErrors(['Field with that date exists']);
            }
        }

        $usrid =Auth::user()->id;
        $fieldName = $request->input('fieldName');
        $date = $request->input('date');

        $relative_path = 'uploads/'.
            hash('md5', $usrid) .'/'.
            hash('md5', $fieldName). '/' .
            hash('md5', $date);

        $store_path = public_path('uploads'). DIRECTORY_SEPARATOR .
            hash('md5', $usrid) . DIRECTORY_SEPARATOR .
            hash('md5', $fieldName). DIRECTORY_SEPARATOR .
            hash('md5', $date);

        $file =  $request->file('field_image');
        $file->move($store_path, 'demo.tif');

        $bandController = new BandController();
        $band_count = $bandController->bandCount($store_path . DIRECTORY_SEPARATOR . 'demo.tif');
        if($band_count >= 3) {
            $vrtfilepath = $this->gda2tiles->tifToVrt($store_path, 'demo.tif');
        } else {
            $vrtfilepath = $this->gda2tiles->tifToVrtRGBA($store_path, 'demo.tif');
        }



        $this->gda2tiles->generateTiles($vrtfilepath,$store_path);

        $coordinates_json_filepath = $this->coordinatorExtractor->extractInfo($store_path . DIRECTORY_SEPARATOR . 'demo.tif',
            $store_path . DIRECTORY_SEPARATOR . 'coordinates.json');

        $coordinates = $this->coordinatorExtractor->extractCoordinates($coordinates_json_filepath);

        $field = new Field();
        $field->fieldFolder =   $relative_path;
        $field->fieldName   =   $fieldName;
        $field->date        =   $date;
        $field->user_id     =   $usrid;
        $field->x_min       =   $coordinates['x_min'];
        $field->x_max       =   $coordinates['x_max'];
        $field->y_min       =   $coordinates['y_min'];
        $field->y_max       =   $coordinates['y_max'];
        $field->save();

        File::makeDirectory($store_path . DIRECTORY_SEPARATOR . 'processes');

        // If field image is not already processed 
        if($request->input('is_processed') != 'is_processed') {
            // do processes 
            $processingController = new ImageProcessingController($store_path . DIRECTORY_SEPARATOR . 'demo.tif',$store_path.DIRECTORY_SEPARATOR);
            //call process
            $processingController->process();
      }

        return redirect('/fieldPhases/'. $fieldName);
    }

    public function addProcessedField( Request $request){
        $fieldName = $request->input('fieldName');
        $fieldDate = $request->input('fieldDate');

        $messages = [
             'required' => trans('errors.required')
        ];

        $this->validate($request,[
            'processName' => 'required|max:255',
            'field_image' => 'required|mimes:tiff'
        ], $messages);

         $usrid =Auth::user()->id;

        $store_path = public_path('uploads'). DIRECTORY_SEPARATOR .
            hash('md5', $usrid) . DIRECTORY_SEPARATOR .
            hash('md5', $fieldName). DIRECTORY_SEPARATOR .
            hash('md5', $fieldDate);

        $file =  $request->file('field_image');
        $file->move($store_path, 'demo.tif');

        $bandController = new BandController();
        $band_count = $bandController->bandCount($store_path . DIRECTORY_SEPARATOR . 'demo.tif');
        if($band_count >= 3) {
            $vrtfilepath = $this->gda2tiles->tifToVrt($store_path, 'demo.tif');
        } else {
            $vrtfilepath = $this->gda2tiles->tifToVrtRGBA($store_path, 'demo.tif');
        }

        $this->gda2tiles->generateTiles($vrtfilepath,$store_path . '/processes/'.$request->input('processName').'/');
        return redirect('/fieldPhases/'. $fieldName);
 
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
        $user_field = Field::where('user_id',Auth::user()->id)->where('id', $request->input('fieldId'))->delete();
        //if deleted successfully
        if($user_field) {
            //check if other fields in the group exist
            $fields = Field::where('user_id',Auth::user()->id)->where('fieldName', $request->input('fieldName'))->get();
            //redirect approprietly
            if($fields->count()) {
                return redirect()->back();
            } else {
                return redirect('/home');
            }
        } else {
            return redirect()->back();
        }
    }

    public function deleteField(Request $request)
    {
        //delete field
        Field::where('user_id',Auth::user()->id)->where('fieldName', $request->input('fieldName'))->delete();
        return redirect()->back();
    }
    public function searchField($name)
    {
        $user_fields = Field::distinct()->select('fieldName')
                                        ->where('user_id',Auth::user()->id)
                                        ->where('fieldName', 'like', '%'.$name.'%')
                                        ->get();
        return json_encode($user_fields);
    }


    public function download($user_id, $field_name, $date)
    {

         $store_path = public_path('uploads'). DIRECTORY_SEPARATOR .
            hash('md5', $user_id) . DIRECTORY_SEPARATOR .
            hash('md5', $field_name). DIRECTORY_SEPARATOR .
            hash('md5', $date);
         
        $filenames = glob($store_path . DIRECTORY_SEPARATOR . '*.tif');

        $zip = new ZipArchive();
        $zip->open($store_path . DIRECTORY_SEPARATOR .'geoserver.zip', ZipArchive::CREATE);
        foreach($filenames as $filename) {
            $zip->addFile($filename, basename($filename)); 
        }
        $zip->close();

        return response()->download($store_path . DIRECTORY_SEPARATOR .'geoserver.zip');
    }
}
