<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2015/10/13
 * Time: 10:16
 * appbase相关服务
 */


class Appbase extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("appbase_provider");
    }

    //获得一个领域的app最新排行榜
    public function get_category_apps()
    {
        $c = $this->rest_provider->get_request("c");
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");
        $store_ratio = $this->rest_provider->get_request("store_ratio"); //安卓 vs ios 市场比例
        $rd_ratio = $this->rest_provider->get_request("rd_ratio");//研发 vs 市场 比例

        $result = $this->appbase_provider->get_category_apps($c,$start,$limit,$store_ratio,$rd_ratio);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个领域的app最新排行榜，专供媒体使用，不展示没有安卓对应的app
    public function get_category_media_apps()
    {
        $c = $this->rest_provider->get_request("c");
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");
        $order = $this->rest_provider->get_request("order");
        $result = $this->appbase_provider->get_category_media_apps($c,$start,$limit,$order);
        $this->rest_provider->print_rest_json($result);
    }

    //获取一个app的完整信息
    public function get_app()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->appbase_provider->get_app($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个类别的文章列表,包括文章标题等基础信息
    public function get_category_reports()
    {
        $c = $this->rest_provider->get_request("c");
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");
        $result = $this->appbase_provider->get_category_reports($c,$start,$limit);
        $this->rest_provider->print_rest_json($result);
    }

    //获得appbase的所有类别
    public function get_categories()
    {
        $result = $this->appbase_provider->get_categories();
        $this->rest_provider->print_rest_json($result);
    }

    //获得appbase的所有类别
    public function get_child_categories()
    {
        $c = $this->rest_provider->get_request("c");
        $result = $this->appbase_provider->get_child_categories($c);
        $this->rest_provider->print_rest_json($result);
    }

    //获得appbase一个类别的类别信息
    public function get_category_info()
    {
        $c = $this->rest_provider->get_request("c");
        $result = $this->appbase_provider->get_category_info($c);
        $this->rest_provider->print_rest_json($result);
    }


    //获得一个类别标签下的竞争图，highcharts图
    public function get_category_apps_compete()
    {
        $c = $this->rest_provider->get_request("c");
        $result = $this->appbase_provider->get_category_apps_compete($c);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个类别标签下的竞争图，highcharts图,专供媒体使用，没有无对应的app
    public function get_category_media_apps_compete()
    {
        $c = $this->rest_provider->get_request("c");
        $result = $this->appbase_provider->get_category_media_apps_compete($c);
        $this->rest_provider->print_rest_json($result);
    }

    //领域top5 app的各项指标对比图。highcharts图
    public function get_top_app_feature_competes()
    {
        $c = $this->rest_provider->get_request("c");
        $result = $this->appbase_provider->get_top_app_feature_competes($c);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个类别标签下的竞争图，highcharts图
    public function get_app_search_results()
    {
        $n = $this->rest_provider->get_request("n");
        $result = $this->appbase_provider->get_app_search_results($n);
        $this->rest_provider->print_rest_json($result);
    }

    //app所在类别下的pd和mo二维在竞争图中的位置。highcharts图
    public function get_app_category_compete()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $c = $this->rest_provider->get_request("c");
        $result = $this->appbase_provider->get_app_category_compete($app_id, $c);
        $this->rest_provider->print_rest_json($result);
    }

    //app所属的类别标签
    public function get_app_categories()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->appbase_provider->get_app_categories($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    // app各项特征的极地蛛网图。highcharts图
    public function get_app_feature()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->appbase_provider->get_app_feature($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //app和该领域top5 app的各项指标对比图。highcharts图
    public function get_app_feature_competes()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $c = $this->rest_provider->get_request("c");
        $result = $this->appbase_provider->get_app_feature_competes($app_id,$c);
        $this->rest_provider->print_rest_json($result);
    }

    //更新app匹配
    public function update_app_match()
    {
        $id = $this->rest_provider->get_request("id");//testin的app 安卓id
        $app_id = $this->rest_provider->get_request("app_id"); //ios的app id
        $result = $this->appbase_provider->update_app_match($id,$app_id);
        $this->rest_provider->print_success_json();
    }
}