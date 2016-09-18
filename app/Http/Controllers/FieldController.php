<?php

namespace App\Http\Controllers;

use App\Library\Gdal2tiles;
use Illuminate\Http\Request;
use App\Http\Requests;

class FieldController extends Controller
{
    /**
     * @var $gda2tiles
     */
    protected $gda2tiles;

    /**
     * FieldController constructor.
     * @param Gdal2tiles $gda2tiles
     */
    public function __construct(Gdal2tiles $gda2tiles)
    {
        $this->gda2tiles = $gda2tiles;
    }

    public function uploadfield(Request $request){

      $file =  $request->file('field_image');
      $store_path = public_path('uploads') . DIRECTORY_SEPARATOR . 'user2' . DIRECTORY_SEPARATOR . 'imerominia';

      $file->move($store_path, 'demo.tif');
      $vrtfilepath = $this->gda2tiles->tifToVrt($store_path, 'demo.tif');
      $this->gda2tiles->generateTiles($vrtfilepath,$store_path);
        dd('generated');
    }
}
