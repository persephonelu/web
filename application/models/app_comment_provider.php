<?php
/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2015/10/13
 * Time: 10:30
 * 生成app评论的函数
 */
class App_comment_provider extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

    //根据appid，获得相关app的评论
    public function get_relate_app_comments($app_id,$start,$limit)
    {
        $sql = "select title,body,rating,app_appstore_comment.app_id as app_id from
                (
                    select app_id,sum(0.5+0.5/pos) as score from aso_search_result_new
                     where query in
                    (select query from aso_search_result_new where app_id='$app_id' and pos<11 )
                    and pos<11 and app_id!='$app_id' group by app_id
                    order by score desc limit 0,100
                ) as app_list
                left join  app_appstore_comment on app_list.app_id=app_appstore_comment.app_id
                where useful=1 and rating=5 limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        $num = $this->get_relate_app_comments_num($app_id);
        return array("num"=>$num,"results"=>$result);
    }
    //根据appid，获得相关app的评论
    public function get_relate_app_comments_num($app_id)
    {
        $sql = "select count(*) as result_num from
                (
                    select app_id,sum(0.5+0.5/pos) as score from aso_search_result_new
                     where query in
                    (select query from aso_search_result_new where app_id='$app_id' and pos<11 )
                    and pos<11 and app_id!='$app_id' group by app_id
                    order by score desc limit 0,100
                ) as app_list
                left join  app_appstore_comment on app_list.app_id=app_appstore_comment.app_id
                where useful=1 and rating=5";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    //根据app name获得app信息,可以只输入正标题
    public function get_app_info($n)
    {
        $sql = "select * from app_info WHERE
              name like '$n%' ORDER by user_comment_num desc";
        $result = $this->db->query($sql)->result_array();
        return $result[0];
    }

    //根据app id，获得该app的评论
    public function get_app_comments($app_id,$start,$limit)
    {
        $sql = "select *,DATE_ADD(date,INTERVAL 8 HOUR) as date from app_appstore_comment
                where app_id='$app_id'
                 order by date DESC
                 limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        $num = $this->get_app_comments_num($app_id);
        return array("num"=>$num,"results"=>$result);
    }

    //根据app id，获得app的评论数
    public function get_app_comments_num($app_id)
    {
        $sql = "select count(*) as result_num from
                app_appstore_comment where app_id='$app_id'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    public function get_app_comment_trend($app_id,$limit=30)
    {
        //获得day_num天前的数据
        $day_num = -1*(int)($limit);
        $day_num_str = (string)$day_num . " day";
        $day_threshold = date('Y-m-d', strtotime( $day_num_str ));//n天前
        $sql = "select count(*) as num,comment_date from app_appstore_comment
                where app_id='$app_id' and comment_date>='$day_threshold'
                group by comment_date";
        $result = $this->db->query($sql)->result_array();

        //hichart数据构造
        $data = array();
        //构造日期数据,x轴数据
        for ($i=$day_num;$i<1;$i++)
        {
            $day_str = (string)$i . " day";
            $day_pre = date('Y-m-d', strtotime( $day_str ));//n天前
            $data["xAxis"]["categories"][] = $day_pre ;
        }

        #构造图表数据
        $data["chart"]["type"] = "column";
        $data["tooltip"]["crosshairs"] = array(array("enabled"=>"true","width"=>1,"color"=>"#d8d8d8"));
        $data["tooltip"]["pointFormat"] = '<span style="color:{series.color}">{series.name}</span>: {point.y} <br/>';
        $data["tooltip"]["shared"] = "true";
        $data["tooltip"]["borderColor"] = "#d8d8d8";
        $data["plotOptions"]["series"]["marker"]["radius"] = 1;


        $data["title"]["text"] = "评论数趋势图";
        $data["title"]["style"] = "fontFamily:'微软雅黑', 'Microsoft YaHei',Arial,Helvetica,sans-serif,'宋体',";
        $data["yAxis"] = array(
            array("title"=>array("text"=>"评论数")),
        );

        //版权信息
        $data["credits"]["text"] = "APPBK";
        $data["credits"]["href"] = "http://www.appbk.com/";
        $data["credits"]["position"]["align"] = "right";
        $data["credits"]["position"]["x"] = -10;
        $data["credits"]["position"]["verticalAlign"] = "bottom";
        $data["credits"]["position"]["y"] = -5;

        //构造y轴数据
        $hot_rank_data = array();
        foreach ($result as $item)
        {
            $hot_rank_data[ $item["comment_date"] ] = $item["num"];
        }

        //图表y轴真实数据
        $y_hot_data = array();
        $y_hot_data["name"] = "评论数";
        $y_hot_data["yAxis"] = 0;

        foreach ( $data["xAxis"]["categories"] as $fetch_date )
        {
            //热度数据
            if ( isset( $hot_rank_data[$fetch_date] ) )
            {
                $hot_rank_value = (int)$hot_rank_data[$fetch_date];
            }
            else
            {
                $hot_rank_value = 0;
            }
            $y_hot_data["data"][] = $hot_rank_value;
        }
        $data["series"][] = $y_hot_data;
        return $data;
    }
}

?>