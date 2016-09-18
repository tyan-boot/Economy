<?php

/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 14:47
 */

namespace Controllers;

use Core\Controller\Controller;

class test extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($var1 = null)
    {
        $vars = array( 'ViewUrl' => 'http://' . \Config\Config::$SiteUrl . '/Views/' );

        $this->LoadView('testView', $vars);
    }
}