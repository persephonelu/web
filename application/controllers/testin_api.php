<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/14
 * Time: 11:36
 * 云测的api接入
 */
class Testin_api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("testin_api_provider");
    }

    /*
    根据token获得用户的基本信息,给testin提供
    请求参数如下：
    authtoken: #String, //第三方平台用户的登录凭证,testin规定的参数，不能变
    clientid: #String, // 第三方平台提供的ClientId信息
    timestamp: #Long, //时间戳
    sig: #String //数字签名中使用的secretkey为第三方平 台 提供的 secretkey
    */
    public function get_user_info()
    {
        $token = $this->rest_provider->get_request("authtoken");
        $result = $this->testin_api_provider->get_user_info($token);
        $this->rest_provider->print_rest_json($result);
    }

    /*
     *appbk用户登录testin站点
     * 接收用户传来的token信息,返回testin用户登录链接
     */
    public function get_testin_url()
    {
        $token = $this->rest_provider->get_request("token");
        $result = $this->testin_api_provider->testin_login($token);
        $this->rest_provider->print_rest_json($result);
    }



}