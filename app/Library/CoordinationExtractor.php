<?php
/**
 * Created by PhpStorm.
 * User: soulg
 * Date: 25/9/2016
 * Time: 09:41
 */

namespace App\Library;


class CoordinationExtractor
{
    /**
     * Path to coordinates extraction script
     */
    const coordinates_extraction_script_path = DIRECTORY_SEPARATOR . "scripts". DIRECTORY_SEPARATOR . "coordinates_extraction.bat";

    /**
     * Extract the field coordinates from the image
     * @param $input_filepath
     * @param $output_filepath
     * @return bool
     * @internal param $output_path
     * @internal param $output_filename
     */
    public function extractInfo($input_filepath, $output_filepath)
    {
        exec(storage_path(self::coordinates_extraction_script_path).' '.
                                                    $input_filepath .' '.
                                                    $output_filepath);
        return $output_filepath;
    }

    /**
     * Extract the coordinates of the given file
     * @param $coordinates_json
     * @return mixed
     */
    public function extractCoordinates($coordinates_json)
    {
        $coordinates = $this->loadCoordinatesFromFile($coordinates_json);

        $found_coordinates['x_min'] = $coordinates[0][0];
        $found_coordinates['x_max'] = $coordinates[0][0];
        $found_coordinates['y_min'] = $coordinates[0][1];
        $found_coordinates['y_max'] = $coordinates[0][1];
        //array_shift($coordinates);
        foreach ($coordinates as $coordinate) {
            if($coordinate[0] < $found_coordinates['x_min']) {
                $found_coordinates['x_min'] = $coordinates[0];
            }
            if($coordinate[0] > $found_coordinates['x_max']) {
                $found_coordinates['x_max'] = $coordinate[0];
            }
            if($coordinate[1] < $found_coordinates['y_min']) {
                $found_coordinates['y_min'] = $coordinate[1];
            }
            if($coordinate[1] > $found_coordinates['y_max']) {
                $found_coordinates['y_max'] = $coordinate[1];
            }
        }

        return $found_coordinates;
    }

    /**
     * Load coordinates section from file
     * @param $file
     * @return mixed
     */
    protected function loadCoordinatesFromFile($file)
    {
        $json_string = file_get_contents($file);
        $json_array = json_decode($json_string, true);

        return array_shift($json_array['wgs84Extent']['coordinates']);
    }
}