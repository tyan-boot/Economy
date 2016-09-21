<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/21
 * Time: 9:41
 */

namespace Controllers;

use Core\Controller\Controller;
use Modules\Users;

class API extends Controller
{
    private $User;

    public function __construct()
    {
        parent::__construct();
        $this->User = new Users();
        date_default_timezone_set('PRC');
    }

    public function index()
    {
        $msg['err'] = 1;
        $msg['msg'] = 'No input';
    }

    public function GetRecords()
    {
        if ($this->User->IsLogin())
        {
            $userid = $this->User->GetLoggedUserId();
            $data = $this->User->QueryRecordByUserId($userid);
            $Income = $this->User->CountTotalIn($userid);
            $Expenses = $this->User->CountTotalOut($userid);

            $msg['err'] = 0;
            $msg['Income'] = $Income;
            $msg['Expenses'] = $Expenses;
            $msg['data'] = $data;

            echo json_encode($msg);
        } else
        {
            $msg['err'] = 1;
            $msg['data'] = 'No Login';
        }
    }


    public function AddRecord()
    {
        $data = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
        if ($this->User->IsLogin())
        {
            $id = $this->User->AddRecord($this->User->GetLoggedUserId(), $data->type, $data->number, $data->title, $data->details, $data->time);
            $msg['err'] = 0;
            $msg['msg'] = 'Success';
            $msg['id'] = $id;
            echo json_encode($msg);
        } else
        {
            $msg['err'] = 1;
            $msg['msg'] = 'No Login';
            echo json_encode($msg);
        }
        //$data = $this->Input->Post('')
    }

    public function EditRecord()
    {
        $data = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
        if ($this->User->IsLogin())
        {
            $this->User->EditRecord($data->uid, $data->type, $data->number, $data->title, $data->details, $data->time);
            $msg['err'] = 0;
            $msg['msg'] = 'Success';
            echo json_encode($msg);
        } else
        {
            $msg['err'] = 1;
            $msg['msg'] = 'No Login';
            echo json_encode($msg);
        }
    }

    public function DelRecord($id = null)
    {
        if ($id == null || !is_numeric($id))
        {
            $msg['err'] = 0;
            $msg['msg'] = 'Index error';
            echo json_encode($msg);
            return false;
        }

        if ($this->User->IsLogin())
        {
            $this->User->DelRecord($id);
            $msg['err'] = 0;
            $msg['msg'] = 'Success';
            echo json_encode($msg);
        } else
        {
            $msg['err'] = 1;
            $msg['msg'] = 'No Login';
            echo json_encode($msg);
        }
    }


}