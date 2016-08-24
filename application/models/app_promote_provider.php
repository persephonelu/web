<?php
/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2016/1/19
 * Time: 11:09
 */

//微信api
require_once("resource/weixin/jssdk.php");

class App_promote_provider extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    //获得关App的年度最好排名
    public function get_app_best_rank($app_id)
    {
        $sql = "SELECT * FROM app_rank WHERE app_id='$app_id'
                and ori_classes!='game' and fetch_date<'2016-01-01' order by rank";
        $result = $this->db->query($sql)->result_array();

        $rank_type_dict = array( "topfreeapplications"=>"免费",
            "toppaidapplications"=>"付费","topgrossingapplications"=>"畅销" );

        if ($result)
        {
            $result[0]["rank_type"] = $rank_type_dict[$result[0]["rank_type"]];
        }
        return $result?$result[0]:array();
    }

    /*
     * 功能：获得App的热门搜索词
     * 热度大于5000，排名top5的，按照热度排序
     */
    public function get_app_top_word($app_id)
    {
        $sql = "select query,pos,rank from aso_search_result_new
            left join aso_word_rank_new
            on aso_search_result_new.query=aso_word_rank_new.word
            where app_id='$app_id' and rank>4000 and length(query)<=18
            order by pos limit 20";
        $result = $this->db->query($sql)->result_array();
        return $result?$result:array();
    }

    /*
    * 功能：App用户印象，获得App的热门用户标签
    * 要正面的，热度最高的
    */
    public function get_app_top_tag($app_id)
    {
        $sql = "select topic,count(*) as num from app_appstore_comment
                inner join app_appstore_comment_topic
                on app_appstore_comment.user_review_id=app_appstore_comment_topic.user_review_id
                where app_id='$app_id' and rating>3 group by topic order by num desc limit 10";
        $result = $this->db->query($sql)->result_array();
        return $result?$result:array(array("topic"=>"还不错","num"=>"1"),
            array("topic"=>"值得一试","num"=>"1"),
            array("topic"=>"还行吧","num"=>"1"));
    }

    /*
     * 功能：获得新年寄语
     * 返回某个领域的排名情况，如果是应用就是一级类别，如果是游戏
     * 就是二级类别，选择一个。主要根据下载量预估计算。
     * 同时根据排名情况，给出新年寄语。
     */
    public function get_app_send_word($app_id)
    {
        //步骤1，获得App的类别
        $sql = "select *  from app_info WHERE app_id='$app_id'";
        $result = $this->db->query($sql)->result_array();
        $classes = "";//类别
        $user_comment_num_all = 0; //App总评论数
        $app_classes_list = array("ori_classes","ori_classes1","ori_classes2","ori_classes3");
        if ($result)
        {
            $user_comment_num_all = (int)($result[0]["user_comment_num_all"]);
            //找到第一个非游戏的类别
            foreach ($app_classes_list as $item)
            if (strlen($result[0][$item])>1 && "游戏"!=$result[0][$item])
            {
                $classes = $result[0][$item];
                break;
            }
        }
        //步骤2，高于该App的应用数量
        /*
        $sql = "select count(*) as num from app_info WHERE
                ori_classes='$classes'
                or ori_classes1='$classes'
                or ori_classes2='$classes'
                or ori_classes3='$classes'";
        $result = $this->db->query($sql)->result_array();
        $app_num = (int)($result[0]["num"]);
        */
        $app_num = 3000; //最多只统计3000个app

        //步骤3，获得App在选择类别下的大致位置,计算比这个App好的App个数（exceed_app_num）
        $sql = "select count(*) as exceed_app_num from app_info WHERE
                user_comment_num_all>$user_comment_num_all and
                (ori_classes='$classes'
                or ori_classes1='$classes'
                or ori_classes2='$classes'
                or ori_classes3='$classes')";
        $result = $this->db->query($sql)->result_array();
        $exceed_app_num = $result[0]["exceed_app_num"] ;//比这个App好的App个数

        if ($exceed_app_num>=$app_num)//如果超过这App的应用数量大于3000
        {
            $exceed_app_percent = 0.4;//默认为最少超过49%的app
        }
        else
        {
            $exceed_app_percent = ($app_num - $exceed_app_num)/3000;
            if ($exceed_app_percent<0.5)//如果小于0.5,都算做是0.5
            {
                $exceed_app_percent = 0.5;
            }
        }

        $app_rank = $exceed_app_num;
        if ($app_rank>=0 and $app_rank<=10)
        {
            $send_word = "独步天下";
        }
        else if ($app_rank>10 and $app_rank<=300)
        {
            $send_word = "声名鹊起";
        }
        else if ($app_rank>300 and $app_rank<=1500)
        {
            $send_word = "小有所成";
        }
        else if ($app_rank>1500 and $app_rank<=3000)
        {
            $send_word = "崭露头角";
        }
        else //排名3000以后
        {
            $send_word = "江湖小虾，明日大侠";
        }

        return array("ori_classes"=>$classes,"exceed_app_percent"=>$exceed_app_percent,
            "send_word"=>$send_word);
    }

    //获得微信配置
    public function get_wexin_config($web_url)
    {
        //获得当前绝对路径

        //$web_url = "http://test.appbk.com/wechat.html?app_id=623705649";
        $web_url = urldecode($web_url);
        $jssdk = new JSSDK("wxacf9d7e1862f8193", "44f0cbcfdffbb3605d12c609981d177a",$web_url);
        $signPackage = $jssdk->GetSignPackage();
        return $signPackage;
    }
}

?>