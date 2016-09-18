<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 14:51
 */

namespace Core;

class Loader
{
    protected $ViewDir;
    private static $ThisLoader;

    public function __construct()
    {
        Loader::$ThisLoader = $this;
        $this->ViewDir = ROOT_DIR . DIRECTORY_SEPARATOR . 'Views/';
        spl_autoload_register(array($this,'Autoload'));
    }

    public static function GetInstance()
    {
        return Loader::$ThisLoader;
    }

    public function View($View,$Vars = null)
    {
        //import vars
        if($Vars !=null)
        {
            extract($Vars);
        }
        if (is_file($this->ViewDir . $View . '.php'))
        {
            include $this->ViewDir . $View . '.php';
            return true;
        } else return false;
    }

    public function Autoload($Class)
    {
        $Class = str_replace('\\','/',$Class);
        $FilePrefix = ROOT_DIR.$Class;
        foreach (array('.php','.ctl.php','.class.php','.mod.php') as $ext)
        {
            $File = $FilePrefix.$ext;
            if(is_file($File))
                require_once $File;
        }
    }
}