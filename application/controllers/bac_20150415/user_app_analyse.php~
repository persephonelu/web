<?php
class User_app_analyse extends CI_Controller {
    
#用户信息处理 
#获得用户标签    
public function get_tags()
{
      if ( isset($_REQUEST["app_id"] ) )
      {
          $app_id = $_REQUEST["app_id"];
      }
      else
      {
          $app_id = "917670924"; //默认一个id
      }

      #根据app_id获取app的信息
      $data["app_info"] = $this->user_app_provider->get_app_info($app_id);
      #计算感兴趣的用户标签
      //$data['tags'] = $this->apps_analyse->get_tags($data["app_info"]["filter_name"]);
      //$this->load->view('common/header_user_nav', $data);
      //$this->load->view('member/app_process_user_tag',$data);
      //$this->load->view('common/footer_user'); 
      $result = $this->apps_analyse->get_tags($data["app_info"]["filter_name"]);
      header('Content-type:text/json;charset=utf-8');
      echo json_encode($result);
}


#获得用户类别
public function get_classes()
  {
      $this->load->helper('url');
      if ( isset($_REQUEST["app_id"] ) )
      {
          $app_id = $_REQUEST["app_id"];
      }
      else
      {
          $app_id = "917670924"; //默认一个id
      }
      
      #根据app_id获取app的信息
      $data["app_info"] = $this->user_app_provider->get_app_info($app_id);

      #计算可能感兴趣的类别
      $tags_str = '';

      #计算感兴趣的用户标签
      $data['tags'] = $this->apps_analyse->get_tags($app_id);
      
      $arrlength=count($data['tags']);
      for($x=0;$x<$arrlength;$x++) {
          $tags_str = $tags_str.' '.$data['tags'][$x]['tag'];
      }

      $data['classes_list'] = $this->apps_analyse->get_classes($tags_str);
      $this->load->view('apps_analyse/app_process_user_cat', $data);
  }
#获得app的官方账号


#获得app的性别


#获得app的地区分布


#获得在线时间时间分布

#年龄

#职业

#学历


}
?>
