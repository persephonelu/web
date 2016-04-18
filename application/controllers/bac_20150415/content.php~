<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//获得app的详细内容,包括排名变化趋势
class Content extends CI_Controller {
    //根据app id获得app内容
    public function index()
    {
        $data["user"] = $this->user_provider->get_login_user_info();
        if ( isset($_REQUEST["app_id"]) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id ="891186836";
        }

        //根据id获得app信息
        $data["app_info"] = $this->user_app_provider->get_app_info($app_id);
        
        //根据id获得app排名趋势信息
        $data["trend"] = $this->trend_provider->get_rank_trend($app_id);
  
        $this->load->view('common/header_rank', $data);
        $this->load->view('main/app_content',$data);
        $this->load->view('common/footer_trend',$data);  
    }

}
?>
