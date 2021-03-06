<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 20:01
 */

namespace Controllers;

use Core\Controller\Controller;
use Modules\Users;

class Login extends Controller
{
    private $User;

    public function __construct()
    {
        parent::__construct();
        $this->User = new Users();
    }

    public function index()
    {
        global $starttime;

        $endtime = explode(' ', microtime());
        $RunTime = $endtime[0] + $endtime[1] - ($starttime[0] + $starttime[1]);
        $RunTime = round($RunTime, 5);

        $vars = array( 'title' => 'Login in', 'RunTime' => $RunTime, 'ViewUrl' => $this->GetSiteUrl() . 'Views/', 'SiteUrl' => $this->GetSiteUrl() );
        $this->LoadView('login', $vars);
    }

    public function Login()
    {
        $User = $this->Input->Post('Username');
        $Pwd = $this->Input->Post('Password');
        if ($this->User->Login($User, $Pwd))
        {
            //set cookie
            setcookie('LOGIN', $this->User->GenCookie($User, $Pwd), time() + 3600, '/');
            $msg['err'] = 0;
            $msg['msg'] = 'Login Success';
            echo json_encode($msg);
        } else
        {
            $msg['err'] = 1;
            $msg['msg'] = 'Login Failed';
            echo json_encode($msg);
        }
    }
}