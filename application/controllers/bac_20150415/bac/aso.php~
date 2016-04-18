<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//获得app的关键词信息，进行sao
//目前主要提供排行榜信息
class  Aso extends CI_Controller {
    
    //关键词排行榜
    public function index($start=0)
    {
        $data["user"] = $this->user_model->get_user_info();
        
        //获得排行榜类别
        if ( isset($_REQUEST["c"]) )
        {
            $category = $_REQUEST["c"];
        }
        else
        {
            $category = "应用";
        }

        $data["category"] = $category;
        
        //如果类别是游戏，获得游戏子类别
        $game_category = "";
        if ( $category == "游戏" )
        {
            if ( isset($_REQUEST["gc"]) )
            {
                $game_category = $_REQUEST["gc"];
            }
        }

        $data["game_category"] = $game_category;

        //获得appstore全部类别
        $data["category_list"] = $this->data_provider->get_category();
        //获得apstore游戏类别
        $data["game_category_list"] = $this->data_provider->get_game_category();
 

        //echo date("Y-m-d H:i:s", time()). "<br/>";
        //获得关键词排名结果
        $data["keywords"] = $this->aso_provider->get_keywords(
            $category, $game_category, $start);
        $data["start"] = $start;
        $record_num  = $this->aso_provider->get_keywords_num($category, $game_category);
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
        $config ['suffix'] = "?c=" . $category . "&gc=" . $game_category;
        $config['first_url'] = base_url()."aso/index?c=" . $category . "&gc=" . $game_category;
        $this->pagination->initialize($config);
        $data['turn_page'] = $this->pagination->create_links();

        $this->load->view('member/header', $data);
        $this->load->view('app2/aso',$data);
        $this->load->view('app2/footer_word');
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
