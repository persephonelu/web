<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//获得app的详细内容
class Content extends CI_Controller {
    
    public function index()
    {
        $data["user"] = $this->user_model->get_user_info();

        if ( isset($_REQUEST["name"]) )
        {
            $name = $_REQUEST["name"];
        }
        else
        {
            $name ="微信";
        } 
        
        if ( isset($_REQUEST["type"]) )
        {
            $type = $_REQUEST["type"];
        }
        else
        {
            $type = "角色扮演";
        }
        $data["name"] = $name;
        #echo date("Y-m-d H:i:s", time()). "<br/>";
        $data["app_list"] = $this->trend_provider->get_app_list($name, $type);
        //$data["download"] = $this->trend_provider->get_download($name, $type);
        //$data["download_trend"] = 
        //    $this->trend_provider->get_download_trend($name, $type);
        //总下载趋势
        $data["download_trend"] = $this->predict_provider->get_download_trend($name, $type); 
        $this->load->view('app2/header_index', $data);
        $this->load->view('app2/content',$data);
        $this->load->view('app2/footer_content');
    }
    
    //根据app id获得app内容
    public function id_content()
    {
        $data["user"] = $this->user_model->get_user_info();

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
        
        $this->load->view('app2/header_index', $data);
        $this->load->view('app2/content_new',$data);
        $this->load->view('app2/footer_content');  
    }

    
    //p排行榜趋势
    public function rank_trend()
    {
        $data["user"] = $this->user_model->get_user_info();

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
        
         
        $this->load->view('app2/header_index', $data);
        $this->load->view('app2/content_rank_trend',$data);
        $this->load->view('app2/footer_content');

    }
}
?>
