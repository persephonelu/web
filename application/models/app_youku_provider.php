<?php
class App_youku_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    //根据app_id，获得对应的视频结果
    public function get_app_videos($app_id,$start=0, $limit=30)
    {
        $sql = "select * from youku_video_info
            where app_id ='$app_id'
            order by view_count desc
            limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }


    //获得app的排行榜
    //category：类别
    //rank_type: 榜单类型
    public function get_app_rank($category, $start=0, $limit=30)
    {
        /*
        $sql = "select * from app_rank_new
        right join youku_video_info on app_rank_new.app_id=youku_video_info.app_id
        where ori_classes='$category'
        group by youku_video_info.app_id
        order by rank
        limit $start,$limit";
        */
        $sql = "select app_info.name,app_info.app_id,ipad_pic, icon,thumbnail, rank, num ,
         app_has_video_list.ori_classes from app_info right join
        (select app_rank_new.app_id, thumbnail, rank, ori_classes, count(*) as num from app_rank_new
        right join youku_video_info on app_rank_new.app_id=youku_video_info.app_id
        where ori_classes='$category'
        group by youku_video_info.app_id) as app_has_video_list
        on app_has_video_list.app_id = app_info.app_id
        order by rank
        limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //根据关键词，获取app的相关视频列表
    public function get_app_videos_by_search($n,$start=0,$limit=30)
    {
        $sql = "select * from app_info inner join youku_video_info
            on app_info.app_id=youku_video_info.app_id
            where app_info.name like '$n%' and youku_id is not NULL
            order by view_count desc
            limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

}
?>
