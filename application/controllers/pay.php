<?php
/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2016/1/22
 * Time: 15:24
 * App站内支付相关服务
 */
class Pay extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("pay_provider");
    }

    //测试
    public function test()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->pay_provider->test($app_id);
        header("content-type:text/json;charset=utf-8");
        echo $result; //发送支付凭证
        //$this->rest_provider->print_rest_json($result);
    }

    //接收webhook实践
    public function webhook()
    {

    }
}