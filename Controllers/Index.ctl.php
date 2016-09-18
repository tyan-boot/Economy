<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 23:33
 */

namespace Controllers;

use Core\Controller\Controller;

class Index extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($var1 = null)
    {
        $vars = array( 'title' => 'Index', 'ViewUrl' => 'http://' . \Config\Config::$SiteUrl . '/Views/' );

        $this->LoadView('index', $vars);
    }
}