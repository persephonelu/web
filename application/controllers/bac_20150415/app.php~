<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//app相关信息
class  App extends CI_Controller {
    
    //根据app id获得app信息,不需要登陆
    public function get_app_info()
    {
        if ( isset($_REQUEST["app_id"]) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id ="891186836";
        }
        
        //根据id获得app信息
        $result = $this->user_app_provider->get_app_info($app_id);
        $this->print_rest_json($result);
    }

    //根据app id获得app的排名趋势
    public function get_app_rank_trend()
    {
        if ( isset($_REQUEST["app_id"]) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id ="891186836";
        }

        //根据id获得app信息
        $result = $this->trend_provider->get_rank_trend($app_id);
        header("content-type:text/json;charset=utf-8");
        echo $result;
    } 
    /***********************私有函数******************************/
    //rest json输出
    private function print_rest_json($result)
    {
        header("content-type:text/json;charset=utf-8");
        echo json_encode($result);
    }
    
    //用户登录检测
    private function check_user_login()
    {
        if ( isset($_REQUEST["email"] ) )
        {
            $email = $_REQUEST["email"];
        }
        else
        {
            $email = "unkown"; //没有此用户
        }

        //检查用户是否登录,如果未登录则直接跳转到错误页面
        $this->user_provider->check_login_restful($email);
        return $email;
    }

}
?>
