<?php

namespace App\Library;

class Gdal2tiles
{
    /**
     * Result name of vrt file
     */
    const vrt_filename = "field.vrt";
    /**
     * Path to tif-to-vrt script
     */
    const tif_to_vrt_script_path = DIRECTORY_SEPARATOR . "scripts". DIRECTORY_SEPARATOR . "extract_vrt";

    /**
     * Path to generate Tiles script
     */
    const generate_tiles_sript_path = DIRECTORY_SEPARATOR . "scripts". DIRECTORY_SEPARATOR . "generate_tiles";

   /*
    * Path to tif-to-vrt-rgba script
    */
    const tif_to_vrt_rgba_script_path =  DIRECTORY_SEPARATOR . "scripts". DIRECTORY_SEPARATOR . "extract_vrt_rgba";

    /**
     * Generate vrt from tiff file
     * @param $path
     * @param $filename
     * @return string
     */
    public function tifToVrt($path, $filename)
    {
        //command: gdal.bat %1:tifFilepath %2:vrtFilepath
        exec(storage_path(self::tif_to_vrt_script_path) .' '.
              $path . DIRECTORY_SEPARATOR . $filename .' ' .
              $path . DIRECTORY_SEPARATOR . self::vrt_filename);

        return $path . DIRECTORY_SEPARATOR . self::vrt_filename;
    }

    /**
     * Generate vrt as rgba from tiff file
     * @param $path
     * @param $filename
     * @return string
     */
    public function tifToVrtRGBA($path, $filename)
    {
        //command: gdal.bat %1:tifFilepath %2:vrtFilepath
        exec(storage_path(self::tif_to_vrt_rgba_script_path) .' '.
              $path . DIRECTORY_SEPARATOR . $filename .' ' .
              $path . DIRECTORY_SEPARATOR . self::vrt_filename);

        return $path . DIRECTORY_SEPARATOR . self::vrt_filename;
    }

    /**
     * Generate tiles of image based on the vrt file
     * @param $vrt_file_path
     * @param $output
     */
    public function generateTiles($vrt_file_path, $output)
    {
        $re = [];
        exec(storage_path(self::generate_tiles_sript_path.' '.$vrt_file_path.' '.$output),$re);
    }
}
