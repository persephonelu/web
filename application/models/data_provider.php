<?php
class Data_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    #mysql 搜索
    #query 检索词
    #page 页码，每页默认为10个记录
    public function search($query, $start=0)
    {
        $per_page = 10;//每页结果数目
        $sql = "select * from app_info
            where name like '$query%' and from_plat='appstore'
            order by download_times desc limit $start,$per_page";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    #获得总的检索结果数目
    public function search_result_num($query)
    {
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
    
    #获得app排行榜结果
    #category，app类别
    public function get_app_rank($category, $start=0)
    {
        /*
         * topfreeapplications 免费排行榜
         * toppaidapplications 付费排行榜
         * topgrossingapplications 畅销榜
         *
         */

        $rank_type_list = array("topfreeapplications","toppaidapplications","topgrossingapplications");
        $per_page = 10;//第页结果数目

        $result_list = array();  
        
        foreach ($rank_type_list as $rank_type)
        {
            $sql = "select *
            from app_rank_new
            where from_plat='appstore'
            and rank_type='$rank_type' and ori_classes='$category'
            order by rank
            limit $start,$per_page";

            $result = $this->db->query($sql)->result_array();
            $result_list[$rank_type] = $result;
        }
        return $result_list;
    }

    //获得排行榜的数据数目，以每一个页面的第一个榜单为主
    public function get_app_rank_num($category)
    {
        $type = "topgrossingapplications";
        $sql = "select count(*) as result_num from app_rank_new
                    where from_plat='appstore'
                    and ori_classes='$category' group by rank_type
                    order by result_num";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num']-10;
    }
}
?>
