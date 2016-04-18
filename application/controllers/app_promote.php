<?php
/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2016/1/19
 * Time: 10:39
 * App年度营销活动,主要用于微信活动
 */

class App_promote extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("app_promote_provider");
    }

    //获得App的年度最好排名
    public function get_app_best_rank()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_promote_provider->get_app_best_rank($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    /*
     * 功能：获得App的热门搜索词
     * 热度大于5000，排名top5的，按照热度排序
     */
    public function get_app_top_word()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_promote_provider->get_app_top_word($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    /*
    * 功能：App用户印象，获得App的热门用户标签
    * 要正面的，热度最高的
    */
    public function get_app_top_tag()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_promote_provider->get_app_top_tag($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    /*
    * 功能：获得新年寄语
    * 返回某个领域的排名情况，如果是应用就是一级类别，如果是游戏
    * 就是二级类别，选择一个。主要根据下载量预估计算。
    * 同时根据排名情况，给出新年寄语。
    */
    public function get_app_send_word()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_promote_provider->get_app_send_word($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //获得微信配置
    public function get_wexin_config()
    {
        $web_url = $this->rest_provider->get_request("web_url");
        $result = $this->app_promote_provider->get_wexin_config($web_url);
        $this->rest_provider->print_rest_json($result);
    }
}
?>