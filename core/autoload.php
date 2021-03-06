<?php

/*
 * This file is part of Pulse.
 *
 * (c) Kwame Oteng Appiah-Nti <me@developerkwame.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// require config file
require_once __DIR__ . "/../config.php";
require_once "debug.php";

/**
 * Encode an array and retrieve as an object
 *
 * @param array $array
 * @return void
 */
function objectify(array $array)
{
    if (is_array($array)) {
        $array = json_encode($array, JSON_THROW_ON_ERROR);
        return json_decode($array, null, 512, JSON_THROW_ON_ERROR);
    }

    throw new Exception("Parameter must be array", 1);
    
    return false;
}

/**
 * Initialize configuration
 * Set $config variable globally
 *
 * @return array
 */
function init_config(): array
{
    // Get $config variable from config/config.php file
    // globally
    global $config;

    return $config;
}

/**
 * Function to scan and clean directories
 * from ./ and ../  
 *
 * @param string $directory
 * @return array
 */
function filter_directory($directory = '')
{
    $files = scandir($directory);
    
    unset($files[0]);
    
    unset($files[1]);
    
    return $files;
}

/**
 * Core_config function to get config values 
 * for doing core functionalities
 * 
 * @param [type] $key
 * @param [type] $config
 * @return void
 */
function core_config($key = null)
{
    $object = '';
    
    $config = init_config() ?? [];

    $object = objectify($config);

    if ($key === null) {
        return $object;
    }

    if (! $object->$key) {
        throw new Exception("Object key does not exist");
    }

    return $object->$key;

}

// Get and load all available core files
$core_path = core_config('core_path');
$core = filter_directory($core_path);
foreach ($core as $file) {
    require_once($core_path . DIRECTORY_SEPARATOR . $file);
}

// Get and load all helper files 
$helpers_path = core_config('helpers_path');
$helpers = filter_directory($helpers_path);
foreach ($helpers as $helper) {
    require_once($helpers_path . DIRECTORY_SEPARATOR . $helper);
}

// Get and load all config files 
$config_path = core_config('config_path');
$configs = filter_directory($config_path);
foreach ($configs as $config) {
    require_once($config_path . DIRECTORY_SEPARATOR . $config);
}