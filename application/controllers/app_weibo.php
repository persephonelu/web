<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/26
 * Time: 11:01
 * 提供app微博的相关服务.
 */
class  App_weibo extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("app_weibo_provider");
    }


    //获得每个类别下的用户热门标签
    public function get_tag_rank()
    {
        //接收类别参数
        $category = $this->rest_provider->get_request("c");
        $result = $this->app_weibo_provider->get_tag_rank($category);
        
        //输出rest_json格式的数据
        $this->rest_provider->print_rest_json($result); 
    }

    //获得一个app的用户标签
    public function get_app_user_tags()
    {
        //登录检测
        $email = $this->check_login_state();
        //获得app id
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_weibo_provider->get_app_user_tags($app_id,$email);
        //输出rest json格式数据
        $this->rest_provider->print_rest_json($result);
    }

    //获得某个app的用户性别分布
    public function get_app_user_gender()
    {
        //登录检测
        $email = $this->check_login_state();
        //获得app id
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_weibo_provider->get_app_user_gender($app_id,$email);
        //输出rest json格式数据
        $this->rest_provider->print_rest_json($result);
    }

    //获得某个app的用户的地域分布
    public function get_app_user_area()
    {
        //登录检测
        $email = $this->check_login_state();
        //获得app id
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_weibo_provider->get_app_user_area($app_id,$email);
        //输出rest json格式数据
        $this->rest_provider->print_rest_json($result); 
    }

    //获得某个app的用户上网时段分布 
    public function get_app_user_time()
    {
        //登录检测
        $email = $this->check_login_state();
        //获得app id
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_weibo_provider->get_app_user_time($app_id,$email);
        //输出rest json格式数据
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
