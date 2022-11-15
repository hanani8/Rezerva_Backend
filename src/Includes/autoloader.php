<?php

/**
 * Autoloader
 * php version 8.1
 *
 * @category Autoloader
 * @package  PHP_Experimentation
 * @author   Jai Hanani <jaihanani8@gmail.com>
 * @link     None
 */

spl_autoload_register('myAutoLoader');


/**
 * Function to Autoload Modules
 *
 * @param String $className Class that has to be Autoloaded
 *
 * @return none
 */
function myAutoLoader($className)
{

    $directorys = array(
        '/Classes/',
        '/Controllers/',
        '/Interfaces/'
    );

    $subdirectorys = array(
        'Database/',
        'Reservation/',
        'ReturnType/',
        'Session/',
        'User/',
        'Dashboard/',
        'Restaurant/'
    );

    foreach ($directorys as $directory) {
        foreach ($subdirectorys as $subdirectory) {
            $src_directory = getcwd();
            $path = $src_directory . $directory . $subdirectory . $className . '.php';

            //see if the file exsists
            if (file_exists($path)) {
                include_once $path;

                return;
            }
        }
    }
}
