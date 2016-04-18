<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//用户账号管理控制器
//
class User extends CI_Controller {
    
    //注册页面 
    public function register()
    {
        $this->load->view('member/header');
        $this->load->view('member/web_register');
        $this->load->view('member/footer_index');
    }

    //系统账号登录页面
    public function login()
    {
        $this->load->view('member/header');
        $this->load->view('member/web_login');
        $this->load->view('member/footer_index');
    }

    //微博账号登录
    #根据accesskey，获得微博授权链接，导向该连接
    public function weibo_login()
    {
        $login_url = $this->user_provider->get_login_url();
        $this->load->view('member/header');
        //echo $login_url;
        //重新定向到callback页面
        header("location:$login_url");
    }

    #sina weibo callback页面，微博用户登录处理
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
         //用户登录处理
        $this->user_provider->weibo_user_login($code);
        //登录后直接跳转到用户app首页
        $url = base_url()."user_app";
        header("location:$url");
    }
 
    //用户输入的登录信息检测
    //用户提交账号密码后由该函数处理
    public function login_check()
    {
        $data["email"] = $_REQUEST["email"];//email
        $data["password"] = $_REQUEST["password"];//密码
        //检测用户输入
        $ret = $this->user_provider->login_check($data);
        if ( $ret!="0" ) //如果检测有问题,继续返回到登录页面
        {
            $data["error"] = $ret;
            $this->load->view('member/header_index');
            $this->load->view('member/web_login',$data);
            $this->load->view('member/footer_index');
        }
        else
        {
            //登录后直接跳转到用户app首页
            $url = base_url()."user_app";
            header("location:$url"); 
        }
    }

    //用户注册信息处理 
    //记录用户信息,如果没有问题，则填入数据库并写入session
    //如果有问题，则重新返回注册页面
    public function write_user_info()
    {
        $data["email"] = $_REQUEST["email"];//email
        $data["password"] = $_REQUEST["password"];//密码
        $data["password_check"] = $_REQUEST["password_check"];//密码确认 
        
        //检测用户输入
        $ret = $this->user_provider->check_input($data); 
        if ( "0"!=$ret ) //如果检测有问题,继续返回到注册页面
        {
            $data["error"] = $ret;
            $this->load->view('member/header');
            $this->load->view('member/web_register', $data);
            $this->load->view('member/footer_index');
            return -1;
        }

        //如果注册信息正确，则记录用户注册信息
        //注册用户，记录登录状态
        $user_id = $this->user_provider->reg_user($data);
        //注册后直接跳转到用户个人中心
        $url = base_url() . "user_app";
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
        return 0;
    }    
}
