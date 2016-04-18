<?php
/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2015/10/13
 * Time: 10:16
 * app评论相关服务
 */

class App_comment extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("app_comment_provider");
    }
    //根据app_id或者app名称，获得相关app的评论
    public function get_relate_app_comments()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $n = $this->rest_provider->get_request("n");
        //如果没有输入app的id，则根据app全名，或者正标题，获得app id
        if ($app_id=="728200220")//这个app id是没有输入时给的默认app id
        {
            $app_info = $this->app_comment_provider->get_app_info($n); //根据app名，获得app id
            $app_id = $app_info["app_id"];
        }
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");
        $result = $this->app_comment_provider->get_relate_app_comments($app_id,$start,$limit);
        $this->rest_provider->print_rest_json($result);
    }

    //根据app_id，获得该app的评论
    public function get_app_comments()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");
        $result = $this->app_comment_provider->get_app_comments($app_id,$start,$limit);
        $this->rest_provider->print_rest_json($result);
    }

    //根据app_id，获得该app的评论数趋势图
    public function get_app_comment_trend()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $limit = $this->rest_provider->get_request("limit");
        $result = $this->app_comment_provider->get_app_comment_trend($app_id,$limit);
        $this->rest_provider->print_rest_json($result);
    }

}

?>