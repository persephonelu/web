<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//获得app的关键词信息，进行sao
//目前主要提供排行榜信息
class  Predict extends CI_Controller {
    
    public function index($start=0)
    {
        //获取选择的日期
        if ( isset($_REQUEST["t"]) )
        {
            $select_day = $_REQUEST["t"];
        }
        else
        {
            //$select_day = date('Y-m-d');
            $select_day = "2014-07-26";
        }

        //获得关键词排名结果
        $data["docs"] = $this->predict_provider->get_top($select_day, $start);
        $data["select_day"] = $select_day;
        $data["start"] = $start;

        $this->load->view('app1/header_index');
        $this->load->view('app1/app_predict',$data);
        $this->load->view('app1/footer_result');
    }
    
    public function content()
    {
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
        $data["download"] = $this->trend_provider->get_download($name, $type);
        $data["download_trend"] =
            $this->predict_provider->get_download_trend($name, $type);
        $this->load->view('app1/header_content');
        $this->load->view('app1/predict_content',$data);
        $this->load->view('app1/footer_content');
        
    }
}
?>
