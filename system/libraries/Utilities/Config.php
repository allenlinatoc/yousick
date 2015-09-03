<?php

namespace Utilities;

/**
 * Utility class for Config files
 */
class Config {


    /**
     * Get the corresponding value of a config key
     *
     * @param string $keyword The config key to search for
     * @param string $path The location of the config file
     *
     * @return mixed The string value of a config key, otherwise, NULL
     */
    public static function get($keyword, $path) {
        $contents = self::read($path);
        if ( !isset($contents[$keyword]) )
        {
            RETURN NULL;
        }

        RETURN $contents[$keyword];
    }


    /**
     * Returns the content of a configuration file
     *
     * @param string $path Path to the configuration file
     *
     * @return array
     */
    public static function read($path) {
        $ret = parse_ini_file($path);
        RETURN $ret;
    }



    /**
     * Returns an associative-array containing the JSON contents
     *
     * @param string $path Location of the JSON file
     *
     * @return array The associative array containing the JSON contents
     */
    public static function ReadJSON($path) {
        RETURN json_decode(file_get_contents($path), true);
    }

}
