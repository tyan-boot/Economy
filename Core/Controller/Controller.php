<?php

/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 14:50
 */

namespace Core\Controller;

use Core\Loader;
use Core\Utils\Input;
use Config\Config;

class Controller
{
    private $Loader;
    protected $Input;

    public function __construct()
    {
        $this->Loader = Loader::GetInstance();
        $this->Input = new Input();
    }

    public function LoadView($View, $Vars = null)
    {
        //load view
        $this->Loader->View($View, $Vars);
    }

    protected function ReDirect($Url, $OtherSite = false)
    {
        if(!$OtherSite)
        {
            $NewUrl = '';
            if(Config::$UseHttps)
                $NewUrl = 'https://';
            else $NewUrl = 'http://';

            $NewUrl = $NewUrl.Config::$SiteUrl.'/'.$Url;

            header("Location: ".$NewUrl);
        }else
            header("Location: ".$Url);
    }
}