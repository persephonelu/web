<?php
/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2015/10/13
 * Time: 10:16
 * app基础信息服务
 */
class App extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("app_provider");
    }

    /**************app和类别基础信息****************/
    //根据app_id获得app的基本信息
    public function get_app_info()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_provider->get_app_info($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //获得app的一级类别
    public function get_categories()
    {
        $result = $this->app_provider->get_categories();
        $this->rest_provider->print_rest_json($result);
    }

    //获得app的游戏二级类别
    public function get_game_categories()
    {
        $result = $this->app_provider->get_game_categories();
        $this->rest_provider->print_rest_json($result);
    }

    /**************app榜单相关功能****************/
    //获得app的排行榜
    //c：类别
    //rank_type: 榜单类型
    public function get_app_rank()
    {
        //类别
        $category = $this->rest_provider->get_request("c");
        $rank_type = $this->rest_provider->get_request("rank_type");
        
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");
        
        $result = $this->app_provider->get_app_rank($category, $rank_type, $start, $limit);
        $this->rest_provider->print_rest_json($result);
    }

    //获得app的排名变化趋势图，limit为多少天
    public function get_app_rank_trend()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $limit = $this->rest_provider->get_request("limit");
        $start = $this->rest_provider->get_request("start"); //开始日期
        $end = $this->rest_provider->get_request("end"); //结束日期
        $rank_type = $this->rest_provider->get_request("rank_type"); //结束日期
        $result = $this->app_provider->get_app_rank_trend($app_id, $limit,$start,$end,$rank_type);
        $this->rest_provider->print_rest_json($result);
    }

    //上升最快
    public function get_app_rank_up()
    {
        $category = $this->rest_provider->get_request("c");
        $rank_type = $this->rest_provider->get_request("rank_type");

        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");

        $result = $this->app_provider->get_app_rank_up($category, $rank_type, $start, $limit);
        $this->rest_provider->print_rest_json($result);
    }
    //下降最快
    public function get_app_rank_down()
    {
        $category = $this->rest_provider->get_request("c");
        $rank_type = $this->rest_provider->get_request("rank_type");

        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");

        $result = $this->app_provider->get_app_rank_down($category, $rank_type, $start, $limit);
        $this->rest_provider->print_rest_json($result);
    }

    //新上架
    public function get_relase_app()
    {
        $category = $this->rest_provider->get_request("c");
        $date = $this->rest_provider->get_request("date");

        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");

        $result = $this->app_provider->get_relase_app($category, $date, $start, $limit);
        $this->rest_provider->print_rest_json($result);
    }

    //下架
    public function get_offline_app()
    {
        $date = $this->rest_provider->get_request("date");

        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");

        $result = $this->app_provider->get_offline_app($date, $start, $limit);
        $this->rest_provider->print_rest_json($result);
    }

    //获得最近24小时,某个app的排行榜变化
    public function get_app_rank_hourly_trend()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $limit = $this->rest_provider->get_request("limit");
        $rank_type = $this->rest_provider->get_request("rank_type");
        $result = $this->app_provider->get_app_rank_hourly_trend($app_id, $limit, $rank_type);
        $this->rest_provider->print_rest_json($result);
    }

    //获得某个app的最新排名列表,app_id可以是多个
    public function get_app_rank_list()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_provider->get_app_rank_list($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //获得某个app当前最好的两个排名,总榜和分类榜
    public function get_app_best_rank()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->app_provider->get_app_best_rank($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    /**************app搜索功能****************/
    //搜索app name，阿里云全文搜索，或者mysql like搜索
    public function get_app_search_results()
    {
        //搜索词
        $name = $this->rest_provider->get_request("n");

        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");

        $result = $this->app_provider->get_app_search_results($name, $start, $limit);
        $this->rest_provider->print_rest_json($result);
    }

    //在线搜索app api，通过itunes的api实时搜索，主要搜索app名，和iphone搜索结果不一致
    public function get_api_app_search_results()
    {
        //搜索词,最多20个结果
        $name = $this->rest_provider->get_request("n");
        $result = $this->app_provider->get_api_app_search_results($name);
        $this->rest_provider->print_rest_json($result);
    }

    //获得所有的app搜索结果，获得离线下载的全量关键词搜索结果数据，和iphone搜索结果保持一致
    public function get_all_app_search_results()
    {
        //搜索词
        $name = $this->rest_provider->get_request("n");
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");

        if ($start=="")
        {
            $start = 0;
            $limit = 30;
        }

        //$result = $this->app_provider->get_all_app_search_results($name,$start,$limit);
        $result = $this->app_provider->get_search_result($name,$start,$limit);
        $this->rest_provider->print_rest_json($result);
    }


    /**************app数据挖掘功能功能****************/
    //获得一个app的相关app，通过app的搜索词做协同过滤
    public function get_app_relate_apps()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");

        //为用户推荐相关app
        $result = $this->app_provider->get_app_relate_apps($app_id, $start, $limit);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个app的用户同时购买的app
    public function get_app_user_also_buy_apps()
    {
        $app_id = $this->rest_provider->get_request("app_id");

        //为用户推荐相关app
        $result = $this->app_provider->get_app_user_also_buy_apps($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //下载来源页，购买了这些App的，还购买了我的这个App
    public function get_app_refer_apps()
    {
        $app_id = $this->rest_provider->get_request("app_id");

        //为用户推荐相关app
        $result = $this->app_provider->get_app_refer_apps($app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个app的预测信息，主要是日均下载量预测
    public function get_app_predict()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $date = $this->rest_provider->get_request("date");

        //为用户预测下载量
        $result = $this->app_provider->get_app_predict($app_id, $date);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个app在一个关键词下的搜索排序位置
    public function get_app_search_pos()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $n = $this->rest_provider->get_request("n");

        //为用户推荐相关app
        $result = $this->app_provider->get_app_search_pos($app_id, $n);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个词的搜索提示，阿里云搜索提供的提示功能
    public function get_app_search_hints()
    {
        $n = $this->rest_provider->get_request("n");

        //为用户推荐相关app
        $result = $this->app_provider->get_app_search_hints($n);
        $this->rest_provider->print_rest_json($result);
    }

    //给定一个分类榜单和对应排名，给出对应的总榜排名。目前仅支持免费类型的榜单
    public function get_equal_all_category()
    {
        //搜索词,最多20个结果
        $c = $this->rest_provider->get_request("c");//类别
        $rank = $this->rest_provider->get_request("rank");//类别排名
        $result = $this->app_provider->get_equal_all_category($c, $rank);
        $this->rest_provider->print_rest_json($result);
    }
}

?>