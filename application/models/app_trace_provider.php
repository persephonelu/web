<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/8
 * Time: 14:56
 */

class App_trace_provider extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

    //获得一个app的所有url和对应的content信息
    public function get_urls($email,$app_id="")
    {
        $sql = "select trace_url.url as ori_url, trace_url_content.* from trace_url
                left join trace_url_content
                on trace_url.url=trace_url_content.url
                where email='$email'";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //获得一个url的评论或回复信息
    public function get_url_comments($url)
    {
        $sql = "select * from trace_url_comment
                where url='$url'";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //获得一个url的评论或回复信息
    public function add_url($email,$url)
    {
        $sql = "replace into trace_url (`email`,`url`)
            values ('$email','$url')";
        $result = $this->db2->query($sql);
        return $result;
    }
}

?>