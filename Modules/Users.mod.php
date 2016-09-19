<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 23:02
 */

namespace Modules;

use Core\Utils\ChaCha20;
use Core\Utils\Database\Mysql;

class Users
{
    private $Db;
    private $ChaCha20;

    private $key = "ljuOjlADkFAjTppDsK3VzaVGORwv6tun";
    private $nonce = array( 0, 0, 0 );

    private $salt = 'd6x()!-F';

    public function __construct()
    {
        $this->Db = new Mysql();
        $this->key = str_split($this->key);
        $this->ChaCha20 = new ChaCha20($this->key, 1, $this->nonce);
    }

    public function Register($Username, $Pwd)
    {
        if ($this->HasUser($Username))
        {
            return false;
        } else
        {
            $Pwd = $this->GenPwdHash($Pwd);
            return $this->AddUser($Username, $Pwd);
        }
    }

    public function Login($Username, $Pwd)
    {
        $Pwd = $this->GenPwdHash($Pwd);
        if ($this->HasUserAndPwd($Username, $Pwd))
            return true;
        else return false;
    }

    public function IsLogin()
    {
        if (isset($_COOKIE['LOGIN']))
        {
            $LoginCookie = $_COOKIE['LOGIN'];
            $LoginCookie = base64_decode($LoginCookie);
            $LoginCookieInfo = $this->ChaCha20->ChaChaEncrypt($LoginCookie, strlen($LoginCookie));
            $LoginCookieInfo = json_decode($LoginCookieInfo, true);

            if ($LoginCookieInfo == null)
                return false;
            else
            {
                $Username = $LoginCookieInfo['user'];
                $Pwd = $this->GenPwdHash($LoginCookieInfo['pwd']);
                if ($this->HasUserAndPwd($Username, $Pwd))
                    return true;
                else return false;
            }
        }else return false;
    }

    public function GenCookie($Username, $Pwd)
    {
        //use chacha20 to encrypt cookie
        $cookie = array();
        $cookie['user'] = $Username;
        $cookie['pwd'] = $Pwd;
        $cookieText = json_encode($cookie);
        return base64_encode($this->ChaCha20->ChaChaEncrypt($cookieText, strlen($cookieText)));
    }

    private function GenPwdHash($Pwd)
    {
        return md5($Pwd . $this->salt);
    }

    private function HasUserAndPwd($Username, $Pwd)
    {
        if ($this->Db->has("user", [
            'AND' => [
                'user' => $Username,
                'pwd' => $Pwd
            ]
        ])
        )
            return true;
        else return false;
    }

    private function HasUser($User)
    {
        if ($this->Db->has("user", [ "user" => $User ]))
            return true;
        else return false;
    }

    private function AddUser($Username, $Pwd)
    {
        return $this->Db->insert('user', [ 'user' => $Username, 'pwd' => $Pwd ]);
    }

}