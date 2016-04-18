<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/14
 * Time: 11:36
 * 用户相关服务，主要处理用户登录，注册等逻辑
 */
class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("user_provider");
    }

    //用户输入的登录信息是否正确
    public function check_user_login_input()
    {
        $email = $this->rest_provider->get_request("email");
        $password = $this->rest_provider->get_request("password");
        $result = $this->user_provider->check_user_login_input($email, $password);
        //如果正确，返回一个token
        $this->rest_provider->print_rest_json($result);
    }

    //检测用户输入的注册信息是否正确
    //主要检查email是否已经注册
    public function check_user_register_input()
    {
        $email = $this->rest_provider->get_request("email");
        $result = $this->user_provider->check_user_register_input($email);
        $this->rest_provider->print_rest_json($result);
    }

    //用户注册
    public function reg_user()
    {
        $data["email"] = $this->rest_provider->get_request("email");
        $data["password"] = $this->rest_provider->get_request("password");  
        //用户注册
        $result = $this->user_provider->reg_user($data);
        $this->rest_provider->print_rest_json($result);
    }

    //获得用户基本信息
    public function get_user_info()
    {
        $email = $this->rest_provider->get_request("email");
        
        //检查用户是否登录,如果未登录则直接跳转到错误页面
        $this->user_provider->check_login_restful($email); 
        //获得用户信息
        $result = $this->user_provider->get_user_info($email);
        $this->rest_provider->print_rest_json($result);
    }

    //短信验证,给某个手机号码发送验证短信
    //暂时不进行用户账号验证,注册/修改密码 时也可使用
    public function request_sms_code()
    {
        //获得手机号码
        $phone_num = $this->rest_provider->get_request("phone_num");
        $result = $this->user_provider->request_sms_code($phone_num);
        $this->rest_provider->print_rest_json($result);
    }

    //验证收到的 6 位数字验证码是否正确
    //如果正确,目前直接更新用户个人档的手机信息
    public function verify_sms_code()
    {
        //获得手机号码
        $email = $this->rest_provider->get_request("email");
        $phone_num = $this->rest_provider->get_request("phone_num");
        //获得的6位数字验证码
        $code = $this->rest_provider->get_request("code");
        $result = $this->user_provider->verify_sms_code($email, $phone_num, $code);
        $this->rest_provider->print_rest_json($result);
    }
}

?>
