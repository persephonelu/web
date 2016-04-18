<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/11
 * Time: 15:15
 * portal监控,主要用于数据日常运营和监控.
 */
class App_portal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("app_portal_provider");
    }

    //采样用户app数据统计
    public function index()
    {
        //t为输入的日期，格式如2014-06-10
        $t = $this->rest_provider->get_request("t");
        if (""==$t) //如果t为空
        {
            $t = date("Y-m-d",time()-1*24*60*60);//默认昨天的数据
        }

        $data["t"] = $t;

        $email = $this->rest_provider->get_request("email");
        $app_id = $this->rest_provider->get_request("app_id");

        $email = "58100533@qq.com";
        $app_id = "728200220";

        //获得app基础信息
        $data["app_info"] = $this->app_provider->get_app_info($app_id);
        //获得总的曝光度提升情况
        //$expose_result = $this->app_portal_provider->get_expose_watch($email,$app_id,$t);

        //获得关键词排名提升情况
        $data["pos_result"] = $this->app_portal_provider->get_word_pos_watch($email,$app_id,$t);

        //获得榜单排名提升情况
        $data["rank_result"] = $this->app_portal_provider->get_app_rank_watch($app_id,$t);

        //输出views
        $this->load->view('portal',$data);

    }

    //获得数据库总体情况
    public function get_summary()
    {
        $result = $this->app_portal_provider->get_summary();
        $this->rest_provider->print_rest_json($result);
    }

    #获得数据增长情况
    public function get_increase()
    {
        //t为输入的日期，格式如2014-06-10
        $t = $this->rest_provider->get_request("t");
        if (""==$t) //如果t为空
        {
            $t = date("Y-m-d",time());//默认当天的数据
        }
        $result = $this->app_portal_provider->get_increase($t);
        $this->rest_provider->print_rest_json($result);
    }

    #获得搜索数据的每日任务数据
    public function get_search_job_info()
    {
        $result = $this->app_portal_provider->get_search_job_info();
        $this->rest_provider->print_rest_json($result);
    }

    #获得搜索数据的每日循环小时级数据
    public function get_hourly_search_job_info()
    {
        $result = $this->app_portal_provider->get_hourly_search_job_info();
        $this->rest_provider->print_rest_json($result);
    }

    #获得app排行榜的每日任务数据
    public function get_app_rank_job_info()
    {
        $result = $this->app_portal_provider->get_app_rank_job_info();
        $this->rest_provider->print_rest_json($result);
    }


    /************数据监控*********************
    通过阿里云的站点监控,定时拉取数据,监控具体的结果
     */
    //获得搜索结果更新时间监控
    public function get_search_result_update_watch()
    {
        $result = $this->app_portal_provider->get_search_result_update_watch();
        $this->rest_provider->print_rest_json($result);
    }

    //获得榜单更新时间监控
    public function get_app_rank_update_watch()
    {
        $result = $this->app_portal_provider->get_app_rank_update_watch();
        $this->rest_provider->print_rest_json($result);
    }
}