<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/11
 * Time: 15:17
 */
//用户portal监控页面
class App_portal_provider extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

    /*********************************用户数据监控部分***************/
    //获得总的曝光度提升情况
    public function get_expose_watch($email,$app_id,$t)
    {

    }

    //获得用户关键词搜索排名提升情况
    public function get_word_pos_watch($email,$app_id,$t)
    {
        $cur_day = $t; //当前日期
        $pre_day = date("Y-m-d", strtotime($cur_day)-1*24*60*60);    //昨天
        $pre_week_date = date("Y-m-d", strtotime($cur_day)-7*24*60*60); //一周前

        //获得这三个日期的数据
        $sql = "select * from aso_search_result where query in
                (select word from member_word
                where email='$email' and app_id='$app_id'  and user_word_type=1)
                and (fetch_date='$cur_day' or fetch_date='$pre_day' or fetch_date='$pre_week_date')
                and app_id='$app_id'";

        $result = $this->db->query($sql)->result_array();

        #构造不同关键词的数据,一级key是关键词 内容是｛日期:排名}
        $word_data = array();

        foreach ($result as $item)
        {
            $key = $item["query"]; //类别
            $word_data[$key][ $item["fetch_date"] ] = $item["pos"];
        }

        $day_list = array($cur_day, $pre_day, $pre_week_date);
        $final_result = array();
        foreach($word_data as $word=>$item)
        {
            $one_result = array();//一行结果
            $one_result[] = $word ;//第一列是category
            foreach($day_list as $day)
            {
                if (isset($item[$day]))
                {
                    $one_result[] = (int)$item[$day];

                }
                else
                {
                    $one_result[] = 3000;//如果没有排名，默认为3000
                }
            }
            $final_result[] = $one_result;
        }
        return $final_result;
    }

    //获得榜单排名提升情况
    public function get_app_rank_watch($app_id,$t)
    {
        $cur_day = $t; //当前日期
        $pre_day = date("Y-m-d", strtotime($cur_day)-1*24*60*60);    //昨天
        $pre_week_date = date("Y-m-d", strtotime($cur_day)-7*24*60*60); //一周前

        //获得这三个日期的数据
        $sql = "select * from app_rank where app_id='$app_id'
            and (fetch_date='$cur_day' or fetch_date='$pre_day' or fetch_date='$pre_week_date')
            order by fetch_date";
        $result = $this->db->query($sql)->result_array();
        //构造排名数据
        #构造不同类别的数据,一级key是类别 内容是｛日期:排名}
        $category_data = array();
        $rank_type_dict = array( "topfreeapplications"=>"免费榜",
            "toppaidapplications"=>"收费榜","topgrossingapplications"=>"收入榜" );

        foreach ($result as $item)
        {
            $key = $item["ori_classes"] . "_" . $rank_type_dict[ $item["rank_type"] ]; //类别
            $category_data[$key][ $item["fetch_date"] ] = $item["rank"];
        }


        $day_list = array($cur_day, $pre_day, $pre_week_date);
        $final_result = array();
        foreach($category_data as $category=>$item)
        {
            $one_result = array();//一行结果
            $one_result[] = $category ;//第一列是category
            foreach($day_list as $day)
            {
                if (isset($item[$day]))
                {
                    $one_result[] = (int)$item[$day];

                }
                else
                {
                    $one_result[] = 1000;
                }
            }
            $final_result[] = $one_result;
        }
        return $final_result;
    }


    /*********************************总体数据监控部分***************/
    #获得数据库总体情况
    public function get_summary()
    {
        $table_list = array("app_info"=>"app(app_info)",
            "aso_word_rank_new"=>"关键词(aso_word_rank_new)",
            "aso_search_result_new"=>"搜索数据(aso_search_result_new)",
            "app_appstore_comment"=>"评论(app_appstore_comment)",
            "member"=>"用户(member)",
            "member_word"=>"用户词(member_word)");
        $summary = array();
        foreach ($table_list as $table=>$name)
        {
            $table_data = array();
            $table_data["name"] = $name;
            $table_data["num"] = $this->get_data_num($table);
            $summary[] = $table_data;
        }
        return $summary;
    }

    #获得数据增长情况
    public function get_increase($fetch_date)
    {
        $pre_week_time = strtotime($fetch_date)-7*24*60*60;
        $pre_week_date = date("Y-m-d", $pre_week_time);

        $pre_day_time = strtotime($fetch_date)-1*24*60*60;
        $pre_day_date = date("Y-m-d", $pre_day_time);

        $table_list = array("app_rank"=>"app榜单(app_rank)",
            "aso_word_rank"=>"关键词数(aso_word_rank)",
            "aso_search_result"=>"搜索数据(aso_search_result)"
        );
        $summary = array();
        foreach ($table_list as $table=>$name)
        {
            $table_data = array();
            $table_data["name"] = $name;
            $table_data["week_increase"] = $this->get_data_num_increase($table, $fetch_date, $pre_week_date);
            $table_data["day_increase"] = $this->get_data_num_increase($table, $fetch_date, $pre_day_date);
            $summary[] = $table_data;
        }
        return $summary;
    }

    #获得搜索数据的每日循环任务数据
    public function get_search_job_info()
    {
        $t = date("Y-m-d",time());//当天的日期
        $table2 = "aso_search_result_hourly";//循环任务
        //时间段
        $period_list = array();
        for ($i=8;$i<22;$i=$i+2)
        {
            $start = $t . " ".(string)$i . ":00:00";
            $end = $t . " ".(string)($i + 2) . ":00:00";
            $period =array("start"=>$start,"end"=>$end);
            $period_list[] = $period;
        }
        $summary = array();
        foreach ($period_list as $item)
        {
            $item["num"] = $this->get_time_data_num($table2, $item["start"], $item["end"]);
            $summary[] = $item;
        }
        return $summary;
    }

    #获得搜索数据的每日小时级循环任务数据
    public function get_hourly_search_job_info()
    {
        $t = date("Y-m-d",time());//当天的日期
        $table2 = "aso_search_result_update_test";//循环任务
        //时间段
        $period_list = array();
        for ($i=0;$i<23;$i=$i+1)
        {
            $start = $t . " ".(string)$i . ":00:00";
            $end = $t . " ".(string)($i + 1) . ":00:00";
            $period =array("start"=>$start,"end"=>$end);
            $period_list[] = $period;
        }
        $summary = array();
        foreach ($period_list as $item)
        {

            $item["num"] = $this->get_time_data_num($table2, $item["start"], $item["end"]);
            $summary[] = $item;
        }
        return $summary;
    }

    /**************************公共函数***************************/
    #获得一个数据表，一个时间段的数据量
    #输入：table_name, 数据表
    #输入：fetch_time1, 时间1
    #输入：fetch_time2, 时间2
    #返回：数据量
    public function get_time_data_num($table_name, $fetch_time1, $fetch_time2)
    {
        $sql = "select count(*) as result_num from $table_name where
            fetch_time>'$fetch_time1' and fetch_time<'$fetch_time2'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    #获得一个日期下数据表的数据量
    #输入：table_name, 数据表
    #输入：fetch_date, 日期
    #返回：数据量
    public function get_day_data_num($table_name, $fetch_date)
    {
        $sql = "select count(*) as result_num from $table_name where
            fetch_date='$fetch_date'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    #获得一个数据表的总数据量
    #输入：table_name, 数据表
    #返回：数据量
    public function get_data_num($table_name)
    {
        $sql = "select count(*) as result_num from $table_name";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    #获得两个日期之间的数据增长比例
    #输入：table_name, 数据表
    #输入：fetch_date1, 最近的日期
    #输入：fetch_data2, 之前的日期
    #返回：数据量
    public function get_data_num_increase($table_name, $fetch_date1, $fetch_date2)
    {
        $sql = "select 100*(a.count-b.count)/(b.count+1) as increase,
                a.count as count1,b.count as count2
                from (select count(*) as count from $table_name where
                fetch_date='$fetch_date1') as a,
                (select count(*) as count from $table_name where
                fetch_date='$fetch_date2') as b";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['increase'];
    }

    /************数据监控***************/

    /*
     * 功能:搜索结果监控,看几个词的搜索结果更新时间
     * 包括,4800热度以上的小时级更新
     * 4606到4800的天级更新
     * 4605以下的周级更新
     */
    public function get_search_result_update_watch()
    {
        //在0点和6点之间,不检测,直接返回正确
        //在此期间主要是执行全量更新
        $hour =  (int) date("H",time());//当前的小时
        if ($hour>=0 && $hour<=6)
        {
            return array("status"=>0,"message"=>"ok");
        }

        $status = 0;
        $message = "";
        //小时级更新监控
        //需要监控的词,添加 '分期宝' 可以测试error
        $words = "'药','借贷'";
        $sql = "select min(fetch_time) as fetch_time
                from aso_search_result_new
                where query in ($words) and pos<200";
        $result = $this->db->query($sql)->result_array();
        $fetch_time = $result[0]["fetch_time"];
        $max_interval = 70*60;//最长的间隔,不超过一个小时十分钟
        $interval = time() - strtotime($fetch_time);//距离当前的时间
        if ( $interval > $max_interval ) //如果超过了最长时间
        {
            $status = $status - 1;
            $message = $message . "hourly update error. ";
        }

        //天级的更新检测
        $words = "'运营','appannie'";
        $sql = "select min(fetch_time) as fetch_time
                from aso_search_result_new
                where query in ($words) and pos<200";
        $result = $this->db->query($sql)->result_array();
        $fetch_time = $result[0]["fetch_time"];
        $max_interval = 24*60*60;//最长的间隔,不超过24小时
        $interval = time() - strtotime($fetch_time);//距离当前的时间
        if ( $interval > $max_interval ) //如果超过了最长时间
        {
            $status = $status - 1;
            $message = $message . "Day update error. ";
        }

        //7天级更新检测,爱客疯
        $words = "'玩赚世界','玩赚世界'";
        $sql = "select min(fetch_time) as fetch_time
                from aso_search_result_new
                where query in ($words) and pos<200";
        $result = $this->db->query($sql)->result_array();
        $fetch_time = $result[0]["fetch_time"];
        $max_interval = 7*24*60*60;//最长的间隔,不超过7天
        $interval = time() - strtotime($fetch_time);//距离当前的时间
        if ( $interval > $max_interval ) //如果超过了最长时间
        {
            $status = $status - 1;
            $message = $message . "week update error. ";
        }

        if (""==$message) //如果消息为空,表示没有任何错误,则改写成OK
        {
            $message = "ok";
        }
        return array("status"=>$status,"message"=>$message);
    }

    /*
     * 功能:获得榜单更新时间监控
     */
    public function get_app_rank_update_watch()
    {
        $app_ids = "'382201985','414478124'"; //微信和百度的 app id,一般肯定是在榜单上的
        $sql = "select min(fetch_time) as fetch_time
                from app_rank_new
                where app_id in ($app_ids)";
        $result = $this->db->query($sql)->result_array();
        //echo $sql;
        $fetch_time = $result[0]["fetch_time"];
        $max_interval = 20*60;//最长的间隔,不超过20分钟
        $interval = time() - strtotime($fetch_time);//距离当前的时间
        if ( $interval > $max_interval ) //如果超过了最长时间
        {
            return array("status"=>-1,"message"=>"error");
        }
        else
        {
            return array("status"=>0,"message"=>"ok");
        }
    }
}