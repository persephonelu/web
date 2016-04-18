<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//提供关键词信息
class  Word_test extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("word_test_provider");
    }

    //获得关键词排行榜
    public function get_word_rank()
    {
        $category = $this->rest_provider->get_request("c");
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");
        $result = $this->word_test_provider->get_word_rank($category, $start, $limit);
        $this->rest_provider->print_rest_json($result);
    }    

    //关键词搜索,hints搜索
    public function get_word_search_results()
    {
        $cc = $this->rest_provider->get_request("cc"); //国家
        $name = $this->rest_provider->get_request("n");
        $result = $this->word_test_provider->get_suggestion($name, $cc);
        $this->rest_provider->print_rest_json($result);
    }

    //获得某个关键词的热度
    public function get_word_hot_rank()
    {
        $n = $this->rest_provider->get_request("n");
        $result = $this->word_test_provider->get_word_hot_rank($n);
        $this->rest_provider->print_rest_json($result);
    }

    //获得某个关键词的热度趋势
    public function get_word_rank_trend()
    {
        $n = $this->rest_provider->get_request("n");
        $result = $this->word_test_provider->get_word_rank_trend($n);
        $this->rest_provider->print_rest_json($result);
    }

    //两个app关键词的对比
    public function get_app_words_compare()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $compete_app_id = $this->rest_provider->get_request("compete_app_id");
        $result = $this->word_test_provider->get_app_words_compare($app_id, $compete_app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个app最新的关键词和历史某一天的关键词比较，主要是排名比较
    //如果不写date，则默认为前一天的数据
    public function get_app_word_two_date_compare()
    {

        header("content-type:text/json;charset=utf-8");

        $starttimer = microtime(true);

        $app_id = $this->rest_provider->get_request("app_id");
        $date = $this->rest_provider->get_request("date"); //历史日期
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");
        $result = $this->word_test_provider->get_app_word_two_date_compare($app_id, $date, $start, $limit);
        //$this->rest_provider->print_rest_json($result);

        $stoptimer = microtime(true);
        $timer = $stoptimer-$starttimer;

        echo "控制器时间: in $timer seconds.\n";
    }


    //获得一个或者多个关键词的搜索结果第一名信息，以及热度等信息
    public function get_word_info()
    {
        $n = $this->rest_provider->get_request("n");
        $result = $this->word_test_provider->get_word_info($n);
        $this->rest_provider->print_rest_json($result);
    }



}
?>
