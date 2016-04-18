<?php
/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2015/12/22
 * Time: 16:52
 * aso刷榜需要的相关数据服务
 */
class user_aso extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("user_aso_provider");
    }

    //获得app的可能的关键词,刷榜用
    public function get_app_possible_keywords()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $start =  $this->rest_provider->get_request("start");
        $limit =  $this->rest_provider->get_request("limit");

        $result = $this->user_aso_provider->get_app_possible_keywords($email,$app_id, $start, $limit);
        //输出json数据
        $this->rest_provider->print_rest_json($result);
    }

    //删除刷aso的词，user_word_type=5
    public function del_aso_keyword()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        //添加关键词
        $n = $this->rest_provider->get_request("n");

        //删除关键词
        $result = $this->user_aso_provider->del_aso_keyword($email, $app_id, $n);
        $this->rest_provider->print_success_json();
    }

    //增加aso的词，user_word_type=5
    public function add_aso_keyword()
    {
        //登录检测
        $email = $this->check_login_state();

        $app_id = $this->rest_provider->get_request("app_id");

        //用户添加的关键词
        $n= $this->rest_provider->get_request("n");

        //删除填写的关键词
        $result = $this->user_aso_provider->add_aso_keyword($email, $app_id, $n);
        $this->rest_provider->print_success_json();
    }

    //获得aso刷排名方案
    public function get_aso_solution()
    {
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->user_aso_provider->get_aso_solution($email,$app_id);
        //输出json数据
        $this->rest_provider->print_rest_json($result);

    }

    //获得一个关键词的top10搜索结果app是否机器刷的信息
    public function get_word_brush()
    {
        $n = $this->rest_provider->get_request("n");
        $result = $this->user_aso_provider->get_word_brush($n);
        //输出json数据
        $this->rest_provider->print_rest_json($result);
    }

    /*********************************关键词监控部分********************************/
    //获得用app用户填写关键词的热度和搜索位置，以及曝光度
    public function get_app_keywords_rank_and_pos()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->user_aso_provider->get_app_keywords_rank_and_pos($email, $app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个app在一个关键词下的搜索曝光度变化趋势
    public function get_app_keyword_trend()
    {
        $name = $this->rest_provider->get_request("n");
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->user_aso_provider->get_app_keyword_trend($app_id, $name);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个app所有用户填写关键词的总曝光度的趋势图
    public function get_app_keywords_trend()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->user_aso_provider->get_app_keywords_trend($email, $app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //实时刷新搜索结果
    public function update_search_result()
    {
        $n = $this->rest_provider->get_request("n"); //关键词，一个或者多个，用逗号分开
        $result = $this->user_aso_provider->update_search_result($n);
        $this->rest_provider->print_success_json();
    }

    /************私有函数***************/
    //登陆态检测
    private function check_login_state()
    {
        $email = $this->rest_provider->get_request("email");

        //检查用户是否登录,如果未登录则直接跳转到错误页面
        $this->user_provider->check_login_restful($email);
        return $email;
    }

}

?>