<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#测试页
class Main extends CI_Controller {

    #公共首页
	public function index()
    {
        //获得用户email,如果没有登陆则返回空
        $data["email"] = $this->user_provider->get_login_user_email();
        $this->load->view('main', $data);
    }

    //各类排行榜，接收参数为排行榜类型，app类别，如果app类别是游戏，需要游戏子类别
    public function rank()
    {
        //获得用户email,如果没有登陆则返回空
        $data["email"] = $this->user_provider->get_login_user_email();
        $this->load->view('rank', $data);
    }

    //用户app管理中心首页 
    public function user_app()
    {
        //检查登陆,如果没有登录，则转到登录页面
        $data["email"] = $this->user_provider->check_login();
        $this->load->view('user_main', $data);
    }
    
    //用户app处理首页
    public function user_app_process()
    {
        //检查登陆,如果没有登录，则转到登录页面
        $data["app_id"] = $this->rest_provider->get_request("app_id");
        $data["email"] = $this->user_provider->check_login();
        $this->load->view('user_app_process', $data);
    }
    //注册页面 
    public function register()
    {
        $this->load->view('register');
    }

    //登录页面
    public function login()
    {
        $this->load->view('login');
    } 
    
    //微博账号登录
    //主要根据accesskey，获得微博授权链接，然后转向该连接
    public function weibo_login()
    {
        $login_url = $this->user_provider->get_weibo_login_url();
        header("location:$login_url");
    }

    #sina weibo callback页面，微博用户登录处理
    public function callback()
    {
        //获取sina api传回的code
        $code = $this->rest_provider->get_request("code");
        //用户登录处理
        $this->user_provider->weibo_user_login($code);
        //登录后直接跳转到用户app首页
        $url = base_url()."main/user_app";
        header("location:$url");
    }

    
    //退出登陆，返回到主页
    public function logout()
    {
        //退出，删除session
        $this->user_provider->logout();
        //跳转到主页
        $url = base_url();
        header("location:$url");
    }
}

?>
