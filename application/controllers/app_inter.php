<?php
/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2016/1/18
 * Time: 13:40
 * App国际化服务
 */
class App_inter extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("app_inter_provider");
    }

    //获得关键词排行榜
    public function get_word_rank()
    {
        $category = $this->rest_provider->get_request("c");//
        //$cc = $this->rest_provider->get_request("cc"); //国家
        $cc = "th_";
        $start = $this->rest_provider->get_request("start");
        $limit = $this->rest_provider->get_request("limit");
        $result = $this->app_inter_provider->get_word_rank($cc, $category, $start, $limit);
        $this->rest_provider->print_rest_json($result);
    }


}

?>