<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//用户app管理rest
class User_app_manage extends CI_Controller {

    //首页 
    public function index()
    {
        //检查登陆,如果没有登录，则转到登录页面
        $data["email"] = $this->user_provider->check_login();

        $this->load->view('common/header_user', $data);
        $this->load->view('user/user_app', $data);
        $this->load->view('common/footer_user');
    }
    
    //获得用户的app列表
    public function get_user_apps()
    {
        //登录检测
        $email = $this->check_user_login();
 
        //获得用户的app列表
        $result = $this->user_app_provider->get_user_apps($email);
        
        //输出json数据
        $this->print_rest_json($result);
    }
    
    //获得用户信息
    public function get_user_info()
    {
        //登录检测
        $email = $this->check_user_login();

        //获得用户的app列表
        $result = $this->user_provider->get_user_info($email);

        //输出json数据
        $this->print_rest_json($result);
    } 


    //通过appid添加用户app
    public function add_user_app()
    {
        //登录检测
        $email = $this->check_user_login();
        
        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
            //如果不是空字符串，则添加数据
            if ( ""!=$app_id )
            {
                //添加用户数据
                $this->user_app_provider->add_user_app($email, $app_id);
                $this->print_success_json();
            }
        }
    }

    //通过appid删除用户的app
    public function del_user_app()
    {
        //登录检测
        $email = $this->check_user_login();
 
        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
            //如果不是空字符串，则添加数据
            if ( ""!=$app_id )
            {
                //删除用户app数据
                $this->user_app_provider->del_user_app($email, $app_id);
                $this->print_success_json();
            }
        }
    }

    //搜索app
    public function search_app()
    {
        //app搜索
        if ( isset($_REQUEST["q"]) )
        {
            $query = $_REQUEST["q"];
        }
        else
        {
            $query = "天天飞车";
        }

        //只要第一页结果，至多10个
        $result = $this->data_provider->search($query);
        
        //输出json数据
        $this->print_rest_json($result);
    }

    //获得app的类别,不包括游戏二级类别
    public function get_categories()
    {
        $result = $this->data_provider->get_category();
        $this->print_rest_json($result);
    }
    
    //获得游戏二级类别
    public function get_game_categories()
    {
        $result = $this->data_provider->get_game_category();
        $this->print_rest_json($result);
    } 
    
    //添加未提交市场的app
    public function add_user_new_app()
    {
        //登录检测
        $email = $this->check_user_login();

        if ( isset($_REQUEST["n"]) )
        {
            $name = $_REQUEST["n"];
        }
        else
        {
            $name = "测试应用";
        }    

        if ( isset($_REQUEST["c"]) )
        {
            $category = $_REQUEST["c"];
        }
        else
        {
            $category = "应用";
        }
        
        if ( isset($_REQUEST["d"]) )
        {
            $description = $_REQUEST["d"];
        }
        else
        {
            $description = "测试";
        }

        $app_id = $this->user_app_provider->add_user_new_app($email, $name, $category, $description);
        $result = ["app_id"=>$app_id];
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
