<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#app排行榜主页
class Rank extends CI_Controller {

    //各类排行榜，接收参数为排行榜类型，app类别，如果app类别是游戏，需要游戏子类别
    public function rank($start=0)
    {
        //获得排行榜类型
        if ( isset($_REQUEST["t"]) )
        {
            $type = $_REQUEST["t"];
        }
        else
        {
            $type = "topfreeapplications";
        }
        $data["type"] = $type;
        
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

        //获得排行结果
        //echo date("Y-m-d H:i:s", time()). "<br/>";
        $data["app_list"] = $this->data_provider->get_rank($type, $category, $game_category, $start);
        //echo date("Y-m-d H:i:s", time()). "<br/>";
        
        $data["start"] = $start;

        //获得搜索结果数
        $record_num  = $this->data_provider->rank_result_num($type, $category, $game_category);
        $data["result_num"] = $record_num;//总结果数传递到页面，防止出现结果为0的情况
        
        //翻页
        $this->load->library('pagination');
        $config['uri_segment'] = 3; //翻页是第几个参数
        $config['base_url'] = base_url()."main/rank/";
        $config['total_rows'] = $record_num;
        $config['per_page'] = '10';
        $config['full_tag_open'] = '<p>';
        $config['num_links'] = 10;
        $config['full_tag_close'] = '</p>';
        $config['first_link'] = '首页';
        $config['last_link'] = '最后一页';
        $config['next_link'] = '&gt;下一页';
        $config['prev_link'] = '上一页&lt;';
        $config ['suffix'] = "?t=".$type . "&c=" . $category . "&gc=" . $game_category;
        $config['first_url'] = base_url()."main/rank/?t=".$type . "&c=" . $category . "&gc=" . $game_category;
        $this->pagination->initialize($config);
        $data['turn_page'] = $this->pagination->create_links();

        $this->load->view('common/header');
        $this->load->view('app2/fenlei_all',$data);
        $this->load->view('app2/footer_rank');
    }

}

?>
