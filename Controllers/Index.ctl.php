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
        date_default_timezone_set('PRC');
    }

    public function index($var1 = null)
    {
        if (!$this->User->IsLogin())
        {
            $this->ReDirect('Login/index');
        }
        $id = $this->User->GetLoggedUserId();
        $data = $this->User->QueryRecordByUserId($id);

        $Income = $this->User->CountTotalIn($id);
        $Outcome = $this->User->CountTotalOut($id);
        $Balance = $Income -$Outcome;

        global $starttime;

        $endtime = explode(' ',microtime());
        $RunTime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
        $RunTime = round($RunTime,5);

        $vars = array(  'title' => 'Index',
                        'ViewUrl' => 'http://' . \Config\Config::$SiteUrl . '/Views/',
                        'Records'=>$data,
                        'Income'=>$Income,
                        'Outcome'=>$Outcome,
                        'Balance'=>$Balance,
                        'RunTime'=>$RunTime
            );
        //echo 'Run time  '.$thistime;
        $this->LoadView('index', $vars);
    }

    public function AddRecord()
    {
        $data = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
        if ($this->User->IsLogin())
        {
            $this->User->AddRecord($this->User->GetLoggedUserId(), $data->type, $data->number, $data->title, $data->details, $data->time);
            $msg['err'] = 0;
            $msg['msg'] = 'Success';
            echo json_encode($msg);
        } else
        {
            $msg['err'] = 1;
            $msg['msg'] = 'No Login';
            echo json_encode($msg);
        }
        //$data = $this->Input->Post('')
    }
}