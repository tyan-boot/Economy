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
}