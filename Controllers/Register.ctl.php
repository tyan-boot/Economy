<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 22:39
 */

namespace Controllers;

use Core\Controller\Controller;
use Modules\Users;

class Register extends Controller
{
    private $User;

    public function __construct()
    {
        parent::__construct();
        $this->User = new Users();
    }

    public function index()
    {
        $vars = array( 'title' => 'Sign in', 'ViewUrl' => 'http://' . \Config\Config::$SiteUrl . '/Views/' );
        $this->LoadView('register', $vars);
    }

    public function Register()
    {
        $User = $this->Input->Post('Username');
        $Pwd = $this->Input->Post('Password');

        $r = $this->User->Register($User, $Pwd);
        if($r != false)
        {
            $msg['err'] = 0;
            $msg['msg'] = 'Register Success!';
            echo json_encode($msg);
        }else
        {
            $msg['err'] = 1;
            $msg['msg'] = 'Register Failed! User has exist!';
            echo json_encode($msg);
        }
    }
}