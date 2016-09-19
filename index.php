<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 14:02
 */

/**
 * Load Autoloader
 */

$starttime = explode(' ',microtime());


define('ROOT_DIR', __DIR__.'/');
require_once 'Core/Loader.php';
$Loader = new \Core\Loader();

/**
 * Route
 */
use \Core\Route;

$Route = new Route();
$Route->Start();
