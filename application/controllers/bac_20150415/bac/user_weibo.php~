<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#用户相关的函数
class  User_weibo extends CI_Controller {
    
    public function __construct()
    {   
        parent::__construct();
        $this->load->model('user_model');
    }    
    #登录函数
    #根据accesskey，获得微博授权链接，导向该连接
    public function login()
    {
        $login_url = $this->user_model->get_login_url();
        //重新定向到callback页面
        header("location:$login_url");
    }
    
    #退出登录，主要是删除session
    public function logout()
    {
        $login_url = $this->user_model->logout();
        $url = base_url();
        header("location:$url");
    }

    #callback页面，保存token到session里面
    public function callback()
    {
        //获取sina api传回的code
        if ( isset($_REQUEST["code"]) )
        {
            $code = $_REQUEST["code"];
        }
        else
        {
            $code = "";
        }
         //获得token,存入session
        $this->user_model->get_token($code);
        $url = base_url();
        $this->load->view("app2/header_index");
        header("location:$url");
    }
}
