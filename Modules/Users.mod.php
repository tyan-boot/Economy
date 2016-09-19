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
            $this->ChaCha20->ResetCount(1);
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
        } else return false;
    }

    public function GetLoggedUserId()
    {
        if (!$this->IsLogin())
        {
            return null;
        }
        $LoginCookie = $_COOKIE['LOGIN'];
        $LoginCookie = base64_decode($LoginCookie);
        $this->ChaCha20->ResetCount(1);
        $LoginCookieInfo = $this->ChaCha20->ChaChaEncrypt($LoginCookie, strlen($LoginCookie));
        $LoginCookieInfo = json_decode($LoginCookieInfo, true);
        $Username = $LoginCookieInfo['user'];

        return $this->FindUserIdByName($Username);
    }

    private function FindUserIdByName($name)
    {
        $r = $this->Db->select('user', '*', [ 'user' => $name ]);
        if ($r != null)
        {
            return $r[0]['uid'];
        } else return null;
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


    /**
     * @param $type
     * @param $number
     * @param $details
     * @param $time
     * @return array|mixed
     */
    public function AddRecord($userid, $type, $number, $title, $details, $time)
    {
        return $this->Db->insert('money', [
            'type' => $type,
            'number' => $number,
            'time' => $time,
            'userid' => $userid,
            'title' => $title,
            'details' => $details
        ]);
    }

    public function QueryRecordByUserId($id)
    {
        return $this->Db->select('money', '*', [ 'userid' => $id, 'ORDER' => [ 'time' => 'DESC' ] ]);
    }

    public function CountMoney($uid)
    {
        return $this->CountTotalIn($uid) - $this->CountTotalOut($uid);
    }

    public function CountTotalOut($uid)
    {
        $r = $this->Db->select('money', [ 'userid', 'type', 'number' ], [ 'userid' => $uid ]);
        if($r != null)
        {
            $TotalOut = 0;

            foreach($r as $eachmoney)
            {
                if($eachmoney['type'] == 1)
                    $TotalOut += $eachmoney['number'];
            }

            return $TotalOut;
        }else return null;

    }

    public function CountTotalIn($uid)
    {
        $r = $this->Db->select('money', [ 'userid', 'type', 'number' ], [ 'userid' => $uid ]);
        if($r != null)
        {
            $TotalOut = 0;

            foreach($r as $eachmoney)
            {
                if($eachmoney['type'] == 2)
                    $TotalOut += $eachmoney['number'];
            }
            return $TotalOut;
        }else return null;
    }
}