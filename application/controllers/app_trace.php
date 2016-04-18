<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/8
 * Time: 14:53
 *  app url跟踪功能,主要跟踪app的社区文章
 */
class App_trace extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("app_trace_provider");
    }

    //获得一个app的所有url和对应的content信息
    public function get_urls()
    {
        $email = $this->rest_provider->get_request("email");
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_trace_provider->get_urls($email,$app_id);
        $this->rest_provider->print_rest_json($result);
    }

    // 获得一个url的评论或回复信息
    public function get_url_comments()
    {
        $url = $this->rest_provider->get_request("url");
        $result = $this->app_trace_provider->get_url_comments($url);
        $this->rest_provider->print_rest_json($result);
    }

    // 为一个app增加监控的url
    public function add_url()
    {
        $email = $this->rest_provider->get_request("email");
        $url = $this->rest_provider->get_request("url");
        $result = $this->app_trace_provider->add_url($email,$url);
        $this->rest_provider->print_success_json();
    }


}
?>