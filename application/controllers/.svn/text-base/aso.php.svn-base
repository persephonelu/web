<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//获得app的关键词信息，进行sao
//目前主要提供排行榜信息
class  Aso extends CI_Controller {
    
    public function index($start=0)
    {
        $data["user"] = $this->user_model->get_user_info();

        //获取选择的日期
        if ( isset($_REQUEST["t"]) )
        {
            $select_day = $_REQUEST["t"];
        }
        else
        {
            //$select_day = date('Y-m-d');
            $select_day = date('Y-m-d', strtotime("-1 day"));
        }

        //获取选择的平台
        if ( isset($_REQUEST["f"]) )
        {
            $from_plat = $_REQUEST["f"];
        }
        else
        {
            //$select_day = date('Y-m-d');
            $from_plat = "appstore";
        }

        
        //获取选择类别
        if ( isset($_REQUEST["c"] ) )
        {
            $classes = $_REQUEST["c"];
        }
        else
        {
            $classes = "all";
        }

        //echo date("Y-m-d H:i:s", time()). "<br/>";
        //获得关键词排名结果
        $data["keywords"] = $this->aso_provider->get_keywords(
            $select_day, $from_plat, $start);
        $data["select_day"] = $select_day;
        $data["start"] = $start;
        $record_num  = $this->aso_provider->get_keywords_num($select_day, $from_plat);
        //echo date("Y-m-d H:i:s", time()). "<br/>";
        //翻页
        $this->load->library('pagination');
        $config['uri_segment'] = 3; //翻页是第几个参数
        $config['base_url'] = base_url()."aso/index/";
        $config['total_rows'] = $record_num;
        $config['per_page'] = '20';
        $config['full_tag_open'] = '<p>';
        $config['num_links'] = 10;
        $config['full_tag_close'] = '</p>';
        $config['first_link'] = '首页';
        $config['last_link'] = '最后一页';
        $config['next_link'] = '&gt;下一页';
        $config['prev_link'] = '上一页&lt;';
        $config ['suffix'] = "?q=".$select_day;
        $config['first_url'] = base_url()."aso/index?t=".$select_day;
        $this->pagination->initialize($config);
        $data['turn_page'] = $this->pagination->create_links();

        $data["tab"] = 1; 
        $this->load->view('app2/header_index', $data);
        $this->load->view('app2/aso',$data);
        $this->load->view('app2/footer');
    }


    //获得搜索联想词，目前直接通过xml接口获取
    public function suggestion()
    {
        $data["user"] = $this->user_model->get_user_info();

        if ( isset($_REQUEST["q"]) )
        {
            $keyword = $_REQUEST["q"];
        }
        else
        {
            $keyword = "";
        }
        //for tab1
        $data["keywords"] = array();
        $data["select_day"] = "";
        $data["start"] = "";
        $data['turn_page'] = "";
        
        //for tab2
        $data["tab"] = 2;
        if ( ""==$keyword )
        {
            $data["suggestion"] = array();
        }
        else
        {
            $data["suggestion"] = $this->aso_provider->get_suggestion($keyword);
        }
        $data["query"] = $keyword; 
        $this->load->view('app2/header_index', $data);
        $this->load->view('app2/aso',$data);
        $this->load->view('app2/footer'); 
    }

    
    //关键词判别和推荐
    public function recommend()
    {
        $data["user"] = $this->user_model->get_user_info();

        //获取选择的日期
        if ( isset($_REQUEST["t"]) )
        {
            $select_day = $_REQUEST["t"];
        }
        else
        {
            //$select_day = date('Y-m-d');
            $select_day = date('Y-m-d', strtotime("-1 day"));
        }
        
        if ( isset($_REQUEST["q"]) )
        {
            $keyword = $_REQUEST["q"];
        }
        else
        {
            $keyword = "";
        }
        //for tab default
        $data["keywords"] = array();
        $data["select_day"] = "";
        $data["start"] = "";
        $data['turn_page'] = "";
        
        //for this tab 3
        $data["tab"] = 3;
        $from_plat = "appstore";

        if ( ""==$keyword )
        {
            $data["recommend"] = array();
        }
        else
        {
            $data["recommend"] = $this->aso_provider->get_recommend($keyword, $select_day, $from_plat);
        }
        $data["query"] = $keyword;
        $this->load->view('app2/header_index', $data);
        $this->load->view('app2/aso',$data);
        $this->load->view('app2/footer'); 
    }
}
?>
