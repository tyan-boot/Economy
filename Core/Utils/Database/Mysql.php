<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 18:58
 */

namespace Core\Utils\Database;

use Config\Database;

class Mysql extends medoo
{
    public function __construct()
    {
        $options = array(
            'database_type'=>'mysql',
            'database_name'=>Database::$DB,
            'server'=>Database::$DB_HOST,
            'username'=>Database::$DB_USER,
            'password'=>Database::$DB_PWD,
            'charset'=>'utf8'
        );
        parent::__construct($options);
    }
}