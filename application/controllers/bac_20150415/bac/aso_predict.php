<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//获得app的关键词信息，进行sao
//目前主要提供排行榜信息
class  Aso_predict extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model("aso_predict_provider");
    }
     
    //关键词推荐服务
    public function index()
    {
        if ( isset($_REQUEST["q"]) )
        {
            $keyword = $_REQUEST["q"];
        }
        else
        {
            $keyword = "";
        }
        //for tab default
        if ( ""==$keyword )
        {
            $data["recommend"] = array();
        }
        else
        {
            $data["recommend"] = $this->aso_predict_provider->predict($keyword);
        }
        $data["query"] = $keyword;
        $this->load->view('app2/header_index');
        $this->load->view('app2/aso_predict',$data);
        $this->load->view('app2/footer'); 
    }
}
?>
