<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//提供关键词排行榜信息
class  Word extends CI_Controller {
    
    //关键词排行榜
    public function index($start=0)
    {
        //获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info();

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
        

        //获得appstore全部类别
        $data["category_list"] = $this->data_provider->get_category();
        //获得apstore游戏类别
        $data["game_category_list"] = $this->data_provider->get_game_category();
 

        //获得关键词排名结果
        $data["keywords"] = $this->aso_provider->get_keywords($category, $start);
        $data["start"] = $start;
        $record_num  = $this->aso_provider->get_keywords_num($category);

        //翻页
        $this->load->library('pagination');
        $config['uri_segment'] = 3; //翻页是第几个参数
        $config['base_url'] = base_url()."word/index/";
        $config['total_rows'] = $record_num;
        $config['per_page'] = '20';
        $config['full_tag_open'] = '<p>';
        $config['num_links'] = 10;
        $config['full_tag_close'] = '</p>';
        $config['first_link'] = '首页';
        $config['last_link'] = '最后一页';
        $config['next_link'] = '&gt;下一页';
        $config['prev_link'] = '上一页&lt;';
        $config ['suffix'] = "?c=" . $category . "&nav=word";
        $config['first_url'] = base_url()."word/index?c=" . $category  . "&nav=word";
        $this->pagination->initialize($config);
        $data['turn_page'] = $this->pagination->create_links();
        
        $this->load->view('common/header_rank', $data);
        $this->load->view('main/word_rank',$data);
        $this->load->view('common/footer_rank');
    }
}
?>
