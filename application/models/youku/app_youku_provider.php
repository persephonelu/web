<?php
include_once( 'resource/youku_api/YoukuSearcher.class.php' );
include_once( 'resource/youku_api/Config.class.php' );
class App_youku_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    public function get_youkuvideo_by_appid($appid)
    {
        $sql    = "select * from youku_video_info where youku_id in (select youku_id from app_youku where app_id = ".$appid.");";
        $query  = $this->db->query($sql);

        return $query->result_array();
    }

    public function get_youkuvideo_by_appname($datetime, $appname)
    {
        $sql    = "select app_id from app_rank_new where ori_classes='游戏' and from_plat='appstore' and filter_name='".$appname."';";

		$query  = $this->db->query($sql);

        $result  = $query->result_array();
        $appid  = $result[0]['app_id'];

        $result     = array();
        if ($appid != "")
        {
            $sql    = "select * from youku_video_info where youku_id in (select youku_id from app_youku where app_id = ".$appid.");";
            $result = $this->db->query($sql)->result_array();    
        }
		
        return $result;
    }

    public function get_youku_app()
    {
        $this->db->select('youku_id, app_id');
        $query  = $this->db->get('app_youku');

        return $query->result_array();
    }

    public function get_youku_by_appid($appid)
    {
        $this->db->select('youku_id');
        $this->db->where('app_id', $appid);

        $query = $this->db->get('app_youku');
        return $query->result_array();
    }

    public function get_app_by_youkuid($youkuid)
    {

        $this->db->select('app_id');
        $this->db->where('youku_id', $youkuid);

        $query = $this->db->get('app_youku');
        return $query->result_array();
    }

    public function get_appinfo_by_appid($appid)
    {

        $this->db->select('*');
        $this->db->where('app_id', $appid);
        $this->db->from('app_info');

        $query  = $this->db->get();

        return $query->result_array();
    }

    public function get_video_by_youkuid($youkuid)
    {

        $this->db->select('*');
        $this->db->where('youku_id', $youkuid);
        $this->db->from('youku_video_info');

        $query  = $this->db->get();

        return $query->result_array();
    }

    public function get_topapp_all($datetime)
    {
        $this->db->select('*');
        $this->db->where('fetch_date', $datetime);
        $this->db->where('ori_classes', '游戏');
        $this->db->where('rank_type', 'topgrossingapplications');
        $this->db->order_by('rank');

        $query  = $this->db->get('app_rank_new');

        return $query->result_array();
    }

    public function get_topapp_free($datetime)
    {
        $this->db->select('*');
        $this->db->where('fetch_date', $datetime);
        $this->db->where('ori_classes', '游戏');
        $this->db->where('rank_type', 'topfreeapplications');
        $this->db->order_by('rank');

        $query  = $this->db->get('app_rank_new');
        return $query->result_array();
    }

    public function get_topapp_paid($datetime)
    {
        $this->db->select('*');
        $this->db->where('fetch_date', $datetime);
        $this->db->where('ori_classes', '游戏');
        $this->db->where('rank_type', 'toppaidapplications');
        $this->db->order_by('rank');

        $query  = $this->db->get('app_rank_new');
        return $query->result_array();
    }
    
    public function get_youku_video_by_search($params)
    {
        $youkuSearcher = new YoukuSearcher( YK_AKEY, YK_SKEY );
        $ret = $youkuSearcher->searchYoukuData($params);
        $videos = $ret->videos;
        return $videos;
    }
}
?>
