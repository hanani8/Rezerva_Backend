<?php

/**
 * Header File to autoload dependencies
 * php version 8.1
 *
 * @category Header
 * @package  PHP_Experimentation
 * @author   Jai Hanani <jaihanani8@gmail.com>
 * @link     None
 */

require_once "Includes/autoloader.php";
$current_directory = getcwd();
$level_up = dirname($current_directory, 1);
$vendor_autloader_path = $level_up . "/vendor/autoload.php";
require_once $vendor_autloader_path;
