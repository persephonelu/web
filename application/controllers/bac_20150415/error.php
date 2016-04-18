<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//错误处理控制器
class  Error extends CI_Controller {
    
    //关键词排行榜
    public function index()
    {
        //获得error code
        if ( isset($_REQUEST["ec"]) )
        {
            $error_code = $_REQUEST["ec"];
        }
        else
        {
            $error_code = "-1";
        }

        //获得error info
        if ( isset($_REQUEST["ei"]) )
        {
            $error_info = $_REQUEST["ei"];
        }
        else
        {
            $error_info = "unknown error";
        }
        
        $result = ["error_code"=>$error_code,"error_info"=>$error_info];
        header("content-type:text/json;charset=utf-8");
        echo json_encode($result);
    }
}
?>
