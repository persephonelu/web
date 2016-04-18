<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/14
 * Time: 23:20
 * 百度搜索广告优化系统
 */

class Baidu_ad extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("baidu_ad_provider");
    }

    /*****************百度api用户信息读取相关服务*******************/
    //百度api callback
    public function callback()
    {
        //auth code，用来获得access token
        $code = $this->rest_provider->get_request("code");

        /*
         * 1 根据auth code，获得 access token，更新用户信息
         * 2 生成用户登录token，转向到和html页面
        */
        $url = $this->baidu_ad_provider->get_token($code);
        echo $url;
        //转向到url
        //header('Location:'.$url);
    }

    //根据login token，获得用户基础信息
    public function get_user()
    {
        $login_token = $this->rest_provider->get_request("token");
        $result = $this->baidu_ad_provider->get_user($login_token);
        $this->rest_provider->print_rest_json($result);
    }

    /*****************用户App管理服务*******************/
    //添加用户app
    public function add_user_app()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->baidu_ad_provider->add_user_app($email,$app_id);
        $this->rest_provider->print_success_json();
    }

    //删除用户app
    public function del_user_app()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->baidu_ad_provider->del_user_app($email,$app_id);
        $this->rest_provider->print_success_json();
    }

    //获得用户的app
    public function get_user_app()
    {
        //登录检测
        $email = $this->check_login_state();
        $result = $this->baidu_ad_provider->get_user_app($email);
        $this->rest_provider->print_rest_json($result);
    }

    /*****************用户App广告关键词相关*******************/
    //获得App的初始化推荐词
    public function get_app_recommend_word()
    {
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");
        $result = $this->baidu_ad_provider->get_app_recommend_word($email, $app_id, $start, $limit);
        $this->rest_provider->print_rest_json($result);
    }

    //添加用户关键词
    public function add_user_word()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $type = $this->rest_provider->get_request("type");//关键词类型，0，种子词，1，ad 关键词
        $n = $this->rest_provider->get_request("n");//关键词或者关键词列表
        $result = $this->baidu_ad_provider->add_user_word($email,$app_id,$n,$type);
        $this->rest_provider->print_success_json();
    }

    //获得用户关键词
    public function get_user_words()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $type = $this->rest_provider->get_request("type");//关键词类型，0，种子词，1，ad 关键词
        $result = $this->baidu_ad_provider->get_user_words($email,$app_id,$type);
        $this->rest_provider->print_rest_json($result);
    }

    /************私有函数***************/
    //登陆态检测
    private function check_login_state()
    {
        $email = $this->rest_provider->get_request("email");
        //检查用户是否登录,如果未登录则直接跳转到错误页面
        $this->baidu_ad_provider->check_login_restful($email);
        return $email;
    }
}
?>