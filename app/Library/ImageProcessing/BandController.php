<?php
namespace App\Library\ImageProcessing;

/*
 * Band extraction controller
 */
class BandController
{
   /*
    * PAth for band count scripts
    */
   const bands_count_path = DIRECTORY_SEPARATOR.'scripts'.DIRECTORY_SEPARATOR.'band_count';  

   /*
    * Path for band extraction scripts
    */
   const bands_extract_path = DIRECTORY_SEPARATOR . 'scripts'.DIRECTORY_SEPARATOR.'band_extraction';

   /*
    * Path for band merge script
    */
   const merge_bands_path = DIRECTORY_SEPARATOR . 'scripts'.DIRECTORY_SEPARATOR.'merge_bands';

   /*
    * Path for calc scrirt
    */
   const calc_path =  DIRECTORY_SEPARATOR . 'scripts'.DIRECTORY_SEPARATOR.'calc';

   const ndvi_filename = 'ndvi_colored.tif';
   const cir_filename = 'cir.tif';
   const nir_red_blue_filename = 'nir_red_blue.tif';
   const nir_green_blue_filename = 'nir_green_blue.tif';
  /**
    * Count available bands in image
    */
   public function bandCount($file_path)
   {
        exec(storage_path(self::bands_count_path . ' ' . $file_path),$output,$return_var);
        return $return_var;
   }

   /*
    * Extract Bands
    */
   public function extractBands($bands, $file_path, $output_path)
   {
       for ($i = 1 ; $i<=$bands; $i++) {
             exec(storage_path(self::bands_extract_path  . ' ' .
                                            $i           . ' ' .
                                            $file_path   . ' ' .
                                            $output_path.'band'.$i.'.tif'),$output,$return_var);
       }
        return $return_var;
   }

   /*
    * Generate NDVI image
    */
   public function generate_ndvi($bands_path)
   {
       $command = storage_path(self::calc_path          .' '.
                            $bands_path .'band4.tif'    .' '.
                            $bands_path .'band1.tif'    .' '.
                            $bands_path .'ndvi_gray.tif'.' '.
                            $bands_path . self::ndvi_filename);
        exec($command,$output,$return_var);
       return $return_var;
   }

   /*
    * Generate color_infrared image
    */
   public function generate_color_infrared($bands_path)
   {
        $command = storage_path(self::merge_bands_path .' '.
                                $bands_path . 'band4.tif'.' '.
                                $bands_path . 'band1.tif'.' '.
                                $bands_path . 'band2.tif'.' '.
                                $bands_path . self::cir_filename);
        exec($command,$output,$return_var);
        return $return_var;
 
   }

   /*
    * Generate nir green blue image
    */
   public function generate_nir_green_blue($bands_path)
   {
        $command = storage_path(self::merge_bands_path .' '.
                                $bands_path . 'band4.tif'.' '.
                                $bands_path . 'band2.tif'.' '.
                                $bands_path . 'band3.tif'.' '.
                                $bands_path . self::nir_green_blue_filename);
        exec($command,$output,$return_var);
        return $return_var;
 
   }



   /*
    * Generate nir red blue color image
    */
   public function generate_nir_red_blue($bands_path)
   {
       $command = storage_path(self::merge_bands_path .' '.
                                $bands_path . 'band4.tif'.' '.
                                $bands_path . 'band1.tif'.' '.
                                $bands_path . 'band3.tif'.' '.
                                $bands_path . self::nir_red_blue_filename);
        exec($command,$output,$return_var);
        return $return_var;
   }
}
