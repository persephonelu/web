<?php
class Data_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    //输出json的搜索结果
    public function search_json($query, $page)
    {
    }

    #mysql 搜索
    #query 检索词
    #page 页码，每页默认为10个记录
    public function search($query, $start=0)
    {
        $per_page = 10;//没页结果数目
        /*
        $sql = "select *, group_concat(distinct(from_plat)) as platform_list from app_info 
            where MATCH (name) AGAINST ('$query' IN BOOLEAN MODE) group by filter_name order by 
            download_times desc limit $start,$per_page";
         */
        $sql = "select * from app_info
            where name like '$query%' and from_plat='appstore'
            order by download_times desc limit $start,$per_page";
        $result = $this->db->query($sql)->result_array();
        //var_dump($result);
        return $result;
    }

    #获得总的检索结果数目
    public function search_result_num($query)
    {
        /*
        $sql = "select count(DISTINCT name) as result_num from 
            app_info where  MATCH (name) AGAINST ('$query' IN BOOLEAN MODE)";
         */
        $sql = "select count(*) as result_num from app_info
            where name like '$query%' and from_plat='appstore'";
        $result = $this->db->query($sql)->result_array();
        //var_dump($result);
        return $result[0]['result_num'];
    }

    #获得appstore全部类别
    public function get_category()
    {
        $sql = "select * from app_map_classes
            where from_plat='appstore' and level=1";
        $result = $this->db->query($sql)->result_array();
        return $result;
    } 

    #获得appstore游戏类别
    public function get_game_category()
    {
        $sql = "select * from app_map_classes
            where from_plat='appstore' and level=2";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    #获得排行榜结果
    #type, 排行榜类型
    #category，app类别
    #game_category, 游戏子类别
    #start, 开始位置
    public function get_rank($type, $category, $game_category, $start=0)
    {
        /*
         * topfreeapplications 免费排行榜
         * toppaidapplications 付费排行榜
         * topgrossingapplications 畅销榜
         *
         */
        
        $per_page = 10;//第页结果数目

        $result_list = array();
        //如果游戏子类别未选择
        if ( $game_category=="" )
        {
            $sql = "select * 
                from app_rank_new left join app_info
                on app_rank_new.app_id=app_info.app_id
                where app_rank_new.from_plat='appstore'
                and rank_type='$type' and app_rank_new.ori_classes='$category'
                order by rank
                limit $start,$per_page";
        }
        else
        {
            $sql = "select * 
                from app_rank_new left join app_info
                on app_rank_new.app_id=app_info.app_id
                where app_rank_new.from_plat='appstore'
                and rank_type='$type' and app_rank_new.ori_classes='$game_category'
                order by rank
                limit $start,$per_page";
        }
        //echo $sql;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    

    //获得排行榜的数据数目，以每一个页面的第一个榜单为主
    public function rank_result_num($type, $category, $game_category)
    {
        if ( $game_category=="" )
        {
            $sql = "select count(*) as result_num from app_rank_new
                    where from_plat='appstore'
                    and rank_type='$type' and ori_classes='$category'";
        }
        else
        {
            $sql = "select count(*) as result_num from app_rank_new
                    where from_plat='appstore'
                    and rank_type='$type' and ori_classes='$game_category'";
        }
        $result = $this->db->query($sql)->result_array();
        //var_dump($result);
        return $result[0]['result_num'];
    }
    
}

?>
