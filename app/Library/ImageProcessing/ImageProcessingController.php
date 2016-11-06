<?php
namespace App\Library\ImageProcessing;

use App\Library\Gdal2tiles;

/*
 * Class that handles all the image processes
 */
class ImageProcessingController
{
    /*
     * Field image filepath
     */
    protected $file_path;

    /*
     * Processes Output Path
     */
    protected $out_path;

    /*
     * Band controller
     */
    protected $bandController;

    /*
     * GDAL 2 tiles 
     */
    protected $gdal2tiles;
    /**
     * Store errors here
     */
    protected $error;

   /*
    *
    */
   const extraction_path = 'processes'.DIRECTORY_SEPARATOR;

    /*
     * Constuctor
     */
    public function __construct($file_path, $out_path)
    {
        $this->bandController = new BandController();
        $this->gdal2tiles = new Gdal2tiles();
        $this->file_path = $file_path;
        $this->out_path = $out_path;
    }

    /**
     * Start the Image processing
     */
    public function process()
    {
       $bands = $this->bandExtraction();
       if ($bands >= 3) {
            $this->bandController->generate_blue_green_red($this->out_path);
            $vrt_filepath = $this->gdal2tiles->tifToVrt($this->out_path, BandController::blue_green_red_filename);
            $this->gdal2tiles->generateTiles($vrt_filepath, $this->out_path .self::extraction_path. 'blue_green_red_tiles');


                if($bands >= 4) {
                    //do the 4 band processing
                    $result = $this->bandController->extractBands(4, $this->file_path, $this->out_path);
                    // Extract processes
                    $this->bandController->generate_ndvi($this->out_path);
                    $vrt_filepath = $this->gdal2tiles->tifToVrt($this->out_path, BandController::ndvi_filename);
                    $this->gdal2tiles->generateTiles($vrt_filepath, $this->out_path .self::extraction_path. 'ndvi_tiles');
            
                    $this->bandController->generate_color_infrared($this->out_path);
                    $vrt_filepath = $this->gdal2tiles->tifToVrt($this->out_path, BandController::cir_filename);
                    $this->gdal2tiles->generateTiles($vrt_filepath, $this->out_path .self::extraction_path. 'cir_tiles');
 
                    $this->bandController->generate_nir_green_blue($this->out_path);
                    $vrt_filepath = $this->gdal2tiles->tifToVrt($this->out_path, BandController::nir_green_blue_filename);
                    $this->gdal2tiles->generateTiles($vrt_filepath, $this->out_path . self::extraction_path.'nir_green_blue_tiles');
 
                    $this->bandController->generate_gndvi($this->out_path);
                    $vrt_filepath = $this->gdal2tiles->tifToVrt($this->out_path, BandController::gndvi_filename);
                    $this->gdal2tiles->generateTiles($vrt_filepath, $this->out_path . self::extraction_path.'gndvi_tiles');
            
            } 
       }else {
            $this->error[] = 'Bands could not be extracted';
       }
    }

    /*
     * Extract bands of image
     */
    public function bandExtraction()
    {
        return $this->bandController->bandCount($this->file_path);
    }
}
