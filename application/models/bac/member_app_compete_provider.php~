<?php
#user app 竞品模型
class Member_app_compete_provider extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }
    
    //获得用户某个app的竞品app列表
    public function get_member_app_competes($email, $app_id)
    {
        $sql = "select * from member_app_compete 
            left join app_info
            on member_app_compete.compete_app_id=app_info.app_id
            where member_app_compete.app_id='$app_id' and email='$email'
            and from_plat='appstore'";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //增加某个app的精品app
    public function add_member_app_compete($email, $app_id, $compete_app_id)
    {
        $sql = "replace into member_app_compete
            (app_id,compete_app_id,email,add_date)
            values
            ('$app_id','$compete_app_id','$email',curdate())";
        $result = $this->db2->query($sql);
        return 0;
    }

    //删除某个app的竞品app
    public function del_member_app_compete($email, $app_id, $compete_app_id)
    {
        $sql = "delete from member_app_compete
            where app_id='$app_id' and email='$email' 
            and compete_app_id='$compete_app_id'";
        $result = $this->db2->query($sql);
        return 0;
    }
   
    //获得某个app的同时购买的app
    public function get_user_also_buy_apps($app_id)
    {
        $sql = "select * from aso_user_also_buy 
              left join app_info on 
              relate_app_id=app_info.app_id
              where aso_user_also_buy.app_id='$app_id'
              and app_info.from_plat='appstore'";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //获得系统推荐的相关app

}
?>
