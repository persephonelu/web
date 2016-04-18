<?php
#用户画像统计
class user_photo_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }


    #获得一个类别下的用户标签
    public function get_tags($ori_classes)
    {
        $sql = "SELECT tag,weight,count(*) as freq from weibo_user_tag 
            left join app_info on weibo_user_tag.tag=app_info.filter_name 
            where app_info.from_plat='appstore' and 
            (ori_classes='$ori_classes' or ori_classes1='$ori_classes' or ori_classes2='$ori_classes') 
            group by tag order by freq desc limit 30"; 
        $result = $this->db->query($sql)->result_array();
        //var_dump($result);
        return $result;
    }


}

?>
