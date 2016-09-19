<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 23:33
 */

namespace Controllers;

use Core\Controller\Controller;
use Modules\Users;

class Index extends Controller
{
    private $User;

    public function __construct()
    {
        parent::__construct();
        $this->User = new Users();
    }

    public function index($var1 = null)
    {
        if(!$this->User->IsLogin())
        {
            echo 'No login';
            //$this->ReDirect('Login/index');
        }
        $vars = array( 'title' => 'Index', 'ViewUrl' => 'http://' . \Config\Config::$SiteUrl . '/Views/' );

        $this->LoadView('index', $vars);
    }
}