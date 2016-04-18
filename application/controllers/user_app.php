<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/14
 * Time: 11:36
 * 用户app相关功能,包括管理,删除等.
 */
class User_app extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("user_app_provider");
    }

    /*********************用户app*******************************/
    //获得用户的app列表
    public function get_user_apps()
    {
        //检查用户登陆信息和登陆态
        $email = $this->check_login_state();

        //获得用户的app列表
        $result = $this->user_app_provider->get_user_apps($email);

        //输出json数据
        $this->rest_provider->print_rest_json($result);
    }  

    //通过appid添加用户app
    public function add_user_app()
    {
        //登录检测
        $email = $this->check_login_state();
        
        $app_id = $this->rest_provider->get_request("app_id");
        
        //添加用户数据
        $this->user_app_provider->add_user_app($email, $app_id);
        $this->rest_provider->print_success_json();
    }
    
    //通过appid删除用户的app
    public function del_user_app()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");

        //删除用户数据
        $this->user_app_provider->del_user_app($email, $app_id);
        $this->rest_provider->print_success_json();
    }

    //替换所有用户的app
    //将用户账号中的app先清空,然后填入下的app列表
    //app id 是希望填入的新的app id,可以多个,用英文逗号分开
    public function replace_all_user_app()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");

        //删除用户数据
        $this->user_app_provider->replace_all_user_app($email, $app_id);
        $this->rest_provider->print_success_json();
    }

   //添加未提交市场的app
    public function add_user_new_app()
    {
        //登录检测
        $email = $this->check_login_state();
        $name = $this->rest_provider->get_request("n");
        $category = $this->rest_provider->get_request("c");  
        $description = $this->rest_provider->get_request("d"); 

        $app_id = $this->user_app_provider->add_user_new_app($email, $name, $category, $description);
        $result = array("app_id"=>$app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个用户所有app的所有信息
    public function get_all_user_app_info()
    {
        //登录检测
        $email = $this->check_login_state();
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");

        $result = $this->user_app_provider->get_all_user_app_info($email, $start, $limit);
        $this->rest_provider->print_rest_json($result);
    }

    /*********************用户竞品app*******************************/
    //获得app的竞品app列表,用户添加的
    public function get_user_app_competes()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");

        //获得app的竞品列表，用户确认的精品
        $result = $this->user_app_provider->get_user_app_competes($email, $app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //添加用户app的竞品
    public function add_user_app_compete()
    {
        //登录检测
        $email = $this->check_login_state();
        
        $app_id = $this->rest_provider->get_request("app_id");
        $compete_app_id = $this->rest_provider->get_request("compete_app_id");

        //添加竞品
        $result = $this->user_app_provider->add_user_app_compete($email, $app_id, $compete_app_id);
        $this->rest_provider->print_success_json();
    }

    //删除用户的一个竞品app
    public function del_user_app_compete()
    {
        //登录检测
        $email = $this->check_login_state();

        $app_id = $this->rest_provider->get_request("app_id");
        $compete_app_id = $this->rest_provider->get_request("compete_app_id");

        //添加竞品
        $result = $this->user_app_provider->del_user_app_compete($email, $app_id, $compete_app_id);
        $this->rest_provider->print_success_json();
    }

    //获得推荐app，根据用户关键词推荐
    public function get_user_app_recommend_competes()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");

        //获得推荐的竞品
        $result = $this->user_app_provider->get_user_app_recommend_competes($email, $app_id);
        $this->rest_provider->print_rest_json($result);
    }

    /************私有函数***************/
    //登陆态检测
    private function check_login_state()
    {
        $email = $this->rest_provider->get_request("email");
        
        //检查用户是否登录,如果未登录则直接跳转到错误页面
        $this->user_provider->check_login_restful($email);
        return $email;
    }
}

?>
