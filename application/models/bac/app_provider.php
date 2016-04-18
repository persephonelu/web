<?php
//用户相关的model
class App_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }
    
    
    //根据appid，获得app的详细信息,一个app id可能对应多个app，主要是语言差异
    public function get_app_info($app_id)
    {
        $sql = "select * from app_info 
            where app_id='$app_id' order by fetch_time desc";
        $result = $this->db->query($sql)->result_array();
        return $result[0];
    }

    #获得appstore全部类别
    public function get_categories()
    {
        $sql = "select * from app_map_classes
            where from_plat='appstore' and level=1";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    #获得appstore游戏类别
    public function get_game_categories()
    {
        $sql = "select * from app_map_classes
            where from_plat='appstore' and level=2";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    #app搜索,用mysql like搜索name
    #name 检索词,也可以是appid
    #start, 记录开始位置
    #limit，记录个数
    public function get_app_search_results($name, $start=0, $limit=10)
    {
        if ( preg_match("/^\d{6,10}$/", $name) ) //如果输入的是app_id
        {
            $sql = "select * from app_info
            where app_id='$name' and from_plat='appstore'";
            //如果没有搜索结果，下载搜索结果,插入数据库
            $num = 1;
        }
        else
        {
            $sql = "select * from app_info
            where name like '$name%' and from_plat='appstore'
            order by download_times desc limit $start,$limit";
            $num = $this->search_app_search_results_num($name);
        }
        $result = $this->db->query($sql)->result_array();
        return ["num"=>$num,"results"=>$result];
    }

    
    #获得app总的检索结果数
    public function search_app_search_results_num($name)
    {
        $sql = "select count(*) as result_num from app_info
            where name like '$name%' and from_plat='appstore'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    //获得app的排行榜
    //category：类别
    //rank_type: 榜单类型
    public function get_app_rank($category, $rank_type, $start=0, $limit=10)
    {
        $sql = "select *
        from app_rank_new
        where from_plat='appstore'
        and rank_type='$rank_type' and ori_classes='$category'
        order by rank
        limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        $num = $this->get_app_rank_num($category, $rank_type);
        return ["num"=>$num,"results"=>$result];
    }

    //获得榜单的结果数
    public function get_app_rank_num($category, $rank_type)
    {
        $sql = "select count(*) as result_num from app_rank_new
                    where from_plat='appstore'
                    and ori_classes='$category' and ori_classes='$category'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    //获得app的排名变化趋势
    public function get_app_rank_trend($app_id)
    {
        //获得day_num天前的数据
        $day_num = -10;
        $day_num_str = (string)$day_num . " day";
        $day_threshold = date('Y-m-d', strtotime( $day_num_str ));//n天前
        $sql = "select * from app_rank where app_id='$app_id' 
            and fetch_date>'$day_threshold' and from_plat='appstore' 
            order by fetch_date";
        $result = $this->db->query($sql)->result_array();

        #构造图表数据
        $data = array();
        $data["chart"]["type"] = "line";
        $data["title"]["text"] = "app排名";
        $data["yAxis"]["title"]["text"] = "排名";

        //构造日期数据,x轴数据
        for ($i=$day_num;$i<0;$i++)
        {
            $day_str = (string)$i . " day";
            $day_pre = date('Y-m-d', strtotime( $day_str ));//n天前
            $data["xAxis"]["categories"][] = $day_pre ;
        }

        //构造排名数据
        #构造不同类别的数据,一级key是类别 内容是｛日期:排名}
        $category_data = array();
        foreach ($result as $item)
        {
            $key = $item["ori_classes"] . "_" . $item["rank_type"];
            $category_data[$key][ $item["fetch_date"] ] = $item["rank"];
        }

        //构造y轴数据，如果某个日期没有数据，则设置为200
        foreach ($category_data as $category=>$day_data)
        {
            //处理一个类别的数据
            $y_data = array();
            $y_data["name"] = $category; #类别的name
            foreach ( $data["xAxis"]["categories"] as $fetch_date )
            {
                if ( isset( $day_data[$fetch_date] ) )
                {
                    $y_data["data"][] = (int)$day_data[$fetch_date];
                }
                else
                {
                    $y_data["data"][] = 201; //如果没有对应的排名数据，为201，即榜单之外
                }
            }
            $data["series"][] = $y_data;
        }

        return $data;
    }

    //系统推荐的相关app，主要根据搜索结果，推荐相关app
    public function get_app_relate_apps($name, $start=0, $limit=10)
    {
        $sql = "select * from 
            (select name,sum(0.5+0.5/pos) as score from aso_search_result_new  
            where query in 
           (select query from aso_search_result_new where name='$name' and pos<11) 
           and pos<11 and name !='$name' group by name) as app_list  
           left join app_info on app_list.name=app_info.name
           where app_info.from_plat='appstore'
           order by score desc limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        $num = $this->get_app_relate_app_num($name);
        return ["num"=>$num,"results"=>$result];
    } 

    //系统推荐app的数量
    public function get_app_relate_app_num($name)
    {
        $sql = "select count(*) as result_num from (select name from aso_search_result_new  
            where query in 
                (select query from aso_search_result_new where name='$name' and pos<11) 
            and pos<11 and name !='$name' group by name) as app_list";
        $result = $this->db->query($sql)->result_array();
        return $result[0]["result_num"];
    }

    //获得一个app用户同时购买的app
    public function get_app_user_also_buy_apps($app_id)
    {
        $sql = "select * from aso_user_also_buy 
              left join app_info on 
              relate_app_id=app_info.app_id
              where aso_user_also_buy.app_id='$app_id'
              and app_info.from_plat='appstore'";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
}
