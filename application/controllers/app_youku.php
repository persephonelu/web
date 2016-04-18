<?php
/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2015/10/13
 * Time: 10:16
 * app 视频相关文章
 */
class App_youku extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('app_youku_provider');
    }

    //获得某个类别下的排行榜
    public function get_app_rank()
    {
        $category = $this->rest_provider->get_request("c");
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");
        $result = $this->app_youku_provider->get_app_rank($category,$start,$limit);
        $this->rest_provider->print_rest_json($result);
    }

    //根据app id，获取app的相关视频列表
    public function get_app_videos()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_youku_provider->get_app_videos($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //根据关键词，获取app的相关视频列表
    public function get_app_videos_by_search()
    {
        $n = $this->rest_provider->get_request("n");
        $result = $this->app_youku_provider->get_app_videos_by_search($n);
        $this->rest_provider->print_rest_json($result);
    }
}
?>