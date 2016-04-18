<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/26
 * Time: 11:01
 * aso相关工具
 */

class App_tools extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("app_tools_provider");
    }

    /*
    * 获得关键词的分词结果
    */
    public function get_word_segments()
    {
        $n= $this->rest_provider->get_request("n");
        $result = $this->app_tools_provider->get_word_segments($n);
        $this->rest_provider->print_rest_json($result);
    }

    /*
    * 获得最近一周的appstore搜索热词
    */
    public function get_appstore_hotwords()
    {
        $result = $this->app_tools_provider->get_appstore_hotwords();
        $this->rest_provider->print_rest_json($result);
    }

    /*
     * 获得最新的appstore搜索热词
    */
    public function get_appstore_hotwords_new()
    {
        $result = $this->app_tools_provider->get_appstore_hotwords_new();
        $this->rest_provider->print_rest_json($result);
    }

    /*
    *  获得aso检测信息,主要是检测关键词，将标题分词后，当作关键词
    */
    public function get_aso_check()
    {
        $n= $this->rest_provider->get_request("n");//标题
        $words = $this->rest_provider->get_request("words"); //关键词，逗号分开
        $result = $this->app_tools_provider->get_aso_check($n, $words);
        $this->rest_provider->print_rest_json($result);
    }

    /*
     *  获得关键词的拓展,主要根据协同过滤做
    */
    public function get_word_expand()
    {
        $n= $this->rest_provider->get_request("n");//关键词,一个或者多个
        $result = $this->app_tools_provider->get_word_expand($n);
        $this->rest_provider->print_rest_json($result);
    }

    /*
    *  获得榜单更新周期
    */
    public function get_app_rank_update()
    {
        $day= $this->rest_provider->get_request("day");//关键词,一个或者多个
        $result = $this->app_tools_provider->get_app_rank_update($day);
        $this->rest_provider->print_rest_json($result);
    }
}
?>