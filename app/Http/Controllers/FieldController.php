<?php

namespace App\Http\Controllers;

use App\Field;
use App\Library\CoordinationExtractor;
use App\Library\Gdal2tiles;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\View\View;

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
        $usrid =1;
        $fieldName = "kati";
        $date = "2/5/2016";
      $file =  $request->file('field_image');
      $store_path = public_path('uploads') . DIRECTORY_SEPARATOR .
                        hash('md5', $usrid) . DIRECTORY_SEPARATOR .
                        hash('md5', $fieldName). DIRECTORY_SEPARATOR .
                        hash('md5', $date);

      $file->move($store_path, 'demo.tif');
      $vrtfilepath = $this->gda2tiles->tifToVrt($store_path, 'demo.tif');
      $this->gda2tiles->generateTiles($vrtfilepath,$store_path);

      $coordinates_json_filepath = $this->coordinatorExtractor->extractInfo($store_path . DIRECTORY_SEPARATOR . 'demo.tif',
                                                               $store_path . DIRECTORY_SEPARATOR . 'coordinates.json');

      $coordinates = $this->coordinatorExtractor->extractCoordinates($coordinates_json_filepath);

        $field = new Field();
        $field->fieldFolder =   $store_path;
        $field->fieldName   =   $fieldName;
        $field->date        =   $date;
        $field->user_id     =   1;
        $field->x_min       =   $coordinates['x_min'];
        $field->x_max       =   $coordinates['x_max'];
        $field->y_min       =   $coordinates['y_min'];
        $field->y_max       =   $coordinates['y_max'];
        $field->save();

    }
}
