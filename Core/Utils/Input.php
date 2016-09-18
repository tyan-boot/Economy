<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 21:49
 */

namespace Core\Utils;


class Input
{
    private $GetArr = array();
    private $PostArr = array();

    public function __construct()
    {
        if(isset($_GET))
            $this->GetArr = $_GET;
        if(isset($_POST))
            $this->PostArr = $_POST;
    }

    public function Get($key)
    {
        if(isset($this->GetArr[$key]))
            return $this->GetArr[$key];
        else return null;
    }

    public function Post($key)
    {
        if(isset($this->PostArr[$key]))
            return $this->PostArr[$key];
        else return null;
    }
}