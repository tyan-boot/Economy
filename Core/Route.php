<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 18:40
 */

namespace Core;


class Route
{
    private $class;
    private $entryPoint;
    private $Parameter;
    private $Controller = null;

    private static $ThisRoute = null;

    public function __construct()
    {
        if(Route::$ThisRoute !=null)
        {
            return Route::$ThisRoute;
        }
        Route::$ThisRoute = $this;
        //default route
        $this->class = \Config\RouteCfg::$DefaultRoute;
        $this->entryPoint = 'index';
        $this->Parameter = array();

        //analyze path info
        if (isset($_SERVER['PATH_INFO']))
        {
            $pathinfo = $_SERVER['PATH_INFO'];
            $pathinfo = explode('/', $pathinfo);

            if (isset($pathinfo[1]))
            {
                $this->class = $pathinfo[1];

                if (isset($pathinfo[2]))
                {
                    $this->entryPoint = $pathinfo[2];
                }
            }
            //pop first 3 item
            array_shift($pathinfo);
            array_shift($pathinfo);
            array_shift($pathinfo);

            //parameter to pass
            $this->Parameter = $pathinfo;
        }
    }

    public function Start()
    {
        $ControllerFile = 'Controllers/' . $this->class . '.ctl.php';
        if (is_file($ControllerFile))
        {
            require $ControllerFile;
            if (class_exists('Controllers\\' . $this->class, false))
            {
                $ClassName = 'Controllers\\' . $this->class;
                $this->Controller = new $ClassName();
                if (method_exists($ClassName, $this->entryPoint))
                {
                    //Call function and pass parameter
                    call_user_func_array(array( $this->Controller, $this->entryPoint ), $this->Parameter);
                }else
                {
                    die('Unable find method');
                }

            } else die('Unable find class  ' . $this->class);

        } else die('Unable to load file');
    }
}