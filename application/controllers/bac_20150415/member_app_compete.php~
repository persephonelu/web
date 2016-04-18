<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#用户app竞品管理页面
class Member_app_compete extends CI_Controller {
    
    //获得app的竞品app列表
    public function get_member_app_competes()
    {
        //登录检测
        $email = $this->check_user_login();
        
        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "728200220"; //默认一个id
        }

        //获得app的竞品列表，用户确认的精品
        $result = $this->member_app_compete_provider->get_member_app_competes($email, $app_id);
        $this->print_rest_json($result);

    }
        
    //添加用户app的竞品
    public function add_member_app_compete()
    {
        //登录检测
        $email = $this->check_user_login();

        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "728200220"; //默认一个id
        }
        
        if ( isset($_REQUEST["compete_app_id"] ) )
        {
            $compete_app_id = $_REQUEST["compete_app_id"];
        }
        else
        {
            $compete_app_id = "653350791"; //默认一个id
        }

        //添加竞品
        $result = $this->member_app_compete_provider->add_member_app_compete($email, $app_id, $compete_app_id);
        $this->print_success_json();
    }

    //删除用户的一个竞品app 
    public function del_member_app_compete()
    {
        //登录检测
        $email = $this->check_user_login();

        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "728200220"; //默认一个id
        }
        
        if ( isset($_REQUEST["compete_app_id"] ) )
        {
            $compete_app_id = $_REQUEST["compete_app_id"];
        }
        else
        {
            $compete_app_id = "653350791"; //默认一个id
        }

        //删除竞品
        $result = $this->member_app_compete_provider->del_member_app_compete($email, $app_id, $compete_app_id);
        $this->print_success_json();
     }

    //获得一个app的用户同时购买的app 列表，可以不登陆
    public function get_user_also_buy_apps()
    {
        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "728200220"; //默认一个id
        }
 
        //获得一个app的用户同时购买的app
        $result = $this->member_app_compete_provider->get_user_also_buy_apps($app_id);
        $this->print_rest_json($result);
    } 

    /***********************私有函数******************************/
    //rest json输出
    private function print_rest_json($result)
    {
        header("content-type:text/json;charset=utf-8");
        echo json_encode($result);
    }
    
    //输出update成功json数据
    private function print_success_json()
    {
        $result = ["status"=>200,"message"=>"update success"];
        $this->print_rest_json($result);
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
