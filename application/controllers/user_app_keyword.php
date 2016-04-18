<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/14
 * Time: 11:36
 * 用户app的关键词管理,推荐等相关功能
 */
class User_app_keyword extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("user_app_keyword_provider");
    }

    /*********************************用户关键词管理部分*********************************/

    //获得用户app关键词列表，用户填写的app关键词
    public function get_user_app_keywords()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        //$start = $this->rest_provider->get_request("start");
        //$limit = $this->rest_provider->get_request("start");
        
       
        #获得用户关键词
        $result = $this->user_app_keyword_provider->get_user_app_keywords($email, $app_id);

        $this->rest_provider->print_rest_json($result);
    }

    //获得用户最终的关键词user_word_type=1，不包含特征的，主要是提供处理速度
    public function get_user_app_keywords_no_feature()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->user_app_keyword_provider->get_user_app_keywords_no_feature($email, $app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //增加用户关键词
    public function add_user_app_keyword()
    {
        //登录检测
        $email = $this->check_login_state();

        $app_id = $this->rest_provider->get_request("app_id");

        //用户添加的关键词
        $name = $this->rest_provider->get_request("n");

        //删除填写的关键词
        $result = $this->user_app_keyword_provider->add_user_app_keyword($email, $app_id, $name);
        $this->rest_provider->print_success_json();
    }

    //删除用户关键词
    public function del_user_app_keyword()
    {
        //登录检测
        $email = $this->check_login_state();

        $app_id = $this->rest_provider->get_request("app_id");

        //用户添加的关键词
        $name = $this->rest_provider->get_request("n");

        //删除填写的关键词
        $result = $this->user_app_keyword_provider->del_user_app_keyword($email, $app_id, $name);
        $this->rest_provider->print_success_json();
    }


    /*********************************用户关键词拓展部分*********************************/
    //获得用app用户的种子词
    public function get_user_app_seed_keywords()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->user_app_keyword_provider->get_user_app_seed_keywords($email, $app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //获得用app用户的种子词,包含各种特征
    public function get_user_app_seed_keywords_feature()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->user_app_keyword_provider->get_user_app_seed_keywords_feature($email, $app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //更新用户的种子词
    public function update_user_app_seed_keywords()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $n = $this->rest_provider->get_request("n");
        $result = $this->user_app_keyword_provider->update_user_app_seed_keywords($email, $app_id, $n);
        $this->rest_provider->print_success_json();
    }

    //获得用户app扩展关键词列表
    public function get_user_app_expand_keywords()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");


        #获得用户关键词
        $result = $this->user_app_keyword_provider->get_user_app_expand_keywords($email, $app_id, 0, 100);

        $this->rest_provider->print_rest_json($result);
    }

    //删除用户扩展关键词
    public function del_user_app_expand_keyword()
    {
        //登录检测
        $email = $this->check_login_state();

        $app_id = $this->rest_provider->get_request("app_id");

        //用户添加的关键词
        $name = $this->rest_provider->get_request("n");

        //删除填写的关键词
        $result = $this->user_app_keyword_provider->del_user_app_expand_keyword($email, $app_id, $name);
        $this->rest_provider->print_success_json();
    }

    //增加用户拓展关键词，user_word_type=1
    public function add_user_app_expand_keyword()
    {
        //登录检测
        $email = $this->check_login_state();

        $app_id = $this->rest_provider->get_request("app_id");

        //用户添加的关键词
        $name = $this->rest_provider->get_request("n");

        //删除填写的关键词
        $result = $this->user_app_keyword_provider->add_user_app_expand_keyword($email, $app_id, $name);
        $this->rest_provider->print_success_json();
    }

    //将拓展词合并到当前版本的关键词
    public function merge_user_app_expand_keywords()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        #合并关键词
        $result = $this->user_app_keyword_provider->merge_user_app_expand_keywords($email, $app_id);

        $this->rest_provider->print_success_json();
    }

    //一些关键词拓展方法

    //拓词方法1，类似app使用词，推荐新的关键词,最多50个,最基础的推荐方法
    public function get_user_app_recommend_keywords()
    {
         //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");

        $result = $this->user_app_keyword_provider
            ->get_word_recommend_keywords($email, $app_id);
        //输出json数据
        $this->rest_provider->print_rest_json($result);
    }

    //拓词方法2， 语义扩展
    public function get_word_relate_keywords()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->user_app_keyword_provider->get_word_relate_keywords($email, $app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //拓词方法3，词根扩展词
    public function get_word_expand_keywords()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->user_app_keyword_provider->get_word_expand_keywords($email, $app_id);
        $this->rest_provider->print_rest_json($result);
    }

    //拓词方法4，根据用户填写的竞品app，获得关键词推荐
    public function get_compete_apps_keywords()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->user_app_keyword_provider->get_compete_apps_keywords($email, $app_id);
        $this->rest_provider->print_rest_json($result);
    }

    /*********************************用户关注的词部分，member_word_expand,user_word_type=2*********************************/
    //增加用户关注的词
    public function add_user_watch_keyword()
    {
        //登录检测
        $email = $this->check_login_state();

        $app_id = $this->rest_provider->get_request("app_id");
        //用户添加的关键词
        $name = $this->rest_provider->get_request("n");

        //删除填写的关键词
        $result = $this->user_app_keyword_provider->add_user_watch_keyword($email, $app_id, $name);
        $this->rest_provider->print_success_json();
    }

    //删除用户关注的词
    public function del_user_watch_keyword()
    {
        //登录检测
        $email = $this->check_login_state();

        $app_id = $this->rest_provider->get_request("app_id");

        //用户添加的关键词
        $name = $this->rest_provider->get_request("n");

        //删除填写的关键词
        $result = $this->user_app_keyword_provider->del_user_watch_keyword($email, $app_id, $name);
        $this->rest_provider->print_success_json();
    }

    //获得app的可能的关键词,以及用户关注的词
    //simple=1,采用简化的返回数据
    public function get_app_possible_and_watch_keywords()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $start =  $this->rest_provider->get_request("start");
        $limit =  $this->rest_provider->get_request("limit");
        $simple = $this->rest_provider->get_request("simple");//数据是否是精简模式
        $result = $this->user_app_keyword_provider->get_app_possible_and_watch_keywords($email, $app_id, $start, $limit, $simple);
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
        $date = $this->rest_provider->get_request("date"); //日期
        $result = $this->user_app_keyword_provider->get_app_keywords_rank_and_pos($email, $app_id,$date);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个app在一个关键词下的搜索曝光度变化趋势
    public function get_app_keyword_trend()
    {
        $name = $this->rest_provider->get_request("n");
        $app_id = $this->rest_provider->get_request("app_id");
        $limit = $this->rest_provider->get_request("limit"); //多少天前的数据
        $start = $this->rest_provider->get_request("start");
        $end = $this->rest_provider->get_request("end");
        $result = $this->user_app_keyword_provider->get_app_keyword_trend($app_id, $name,$limit,$start,$end);
        $this->rest_provider->print_rest_json($result);
    }

    //获得一个app所有用户填写关键词的总曝光度的趋势图
    public function get_app_keywords_trend()
    {
        //登录检测
        $email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $result = $this->user_app_keyword_provider->get_app_keywords_trend($email, $app_id);
        $this->rest_provider->print_rest_json($result);
    }

    /*********************************工具，后续把用户的和公共的分开********************************/
    //获得app的可能的关键词
    public function get_app_possible_keywords()
    {
        //登录检测，因为app content本身使用，故暂时不做检测
        //$email = $this->check_login_state();
        $app_id = $this->rest_provider->get_request("app_id");
        $start =  $this->rest_provider->get_request("start");
        $limit =  $this->rest_provider->get_request("limit");
        $date =  $this->rest_provider->get_request("date"); //日期
       
        //根据id获得app信息
        //$app_info = $this->app_provider->get_app_info($app_id);

        //$result = $this->user_app_keyword_provider->get_app_possible_keywords($app_info["name"]);
        $result = $this->user_app_keyword_provider->get_app_possible_keywords($app_id, $date, $start, $limit);
        //输出json数据
        $this->rest_provider->print_rest_json($result);
    }


    //获得app的曝光度趋势图(所有覆盖的关键词,默认30天内，选日期，则为日期区间内的)
    public function get_app_expose_trend()
    {
        $app_id = $this->rest_provider->get_request("app_id");
        $start = $this->rest_provider->get_request("start");
        $end = $this->rest_provider->get_request("end");
        $result = $this->user_app_keyword_provider->get_app_expose_trend($app_id,$start,$end);
        $this->rest_provider->print_rest_json($result);
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

    /******************废弃的函数*********************/
    //获得关键词扩展词
    public function get_app_keyword_expand_keywords()
    {
        $name = $this->rest_provider->get_request("n");
        $result = $this->user_app_keyword_provider->get_app_keyword_expand_keywords($name);
        $this->rest_provider->print_rest_json($result);
    }


}

?>
