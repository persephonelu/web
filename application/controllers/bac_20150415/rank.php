<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#app排行榜主页
class Rank extends CI_Controller {

    //各类排行榜，接收参数为排行榜类型，app类别，如果app类别是游戏，需要游戏子类别
    public function index($start=0)
    {
        //获得排行榜类别
        if ( isset($_REQUEST["c"]) )
        {
            $category = $_REQUEST["c"];
        }
        else
        {
            $category = "应用";
        } 
        //获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info();

        //获得类别信息
        $data["category"] = $category;

        //获得appstore全部类别
        $data["category_list"] = $this->data_provider->get_category();
        
        //获得apstore游戏类别
        $data["game_category_list"] = $this->data_provider->get_game_category();

        
        $data["start"] = $start;
        
        $data["docs"] = $this->data_provider->get_app_rank($category, $start);
        $result_num = $this->data_provider->get_app_rank_num($category);
        $data["result_num"] = $result_num;
        
        //翻页
        $this->load->library('pagination');
        $config['uri_segment'] = 3; //翻页是第几个参数
        $config['base_url'] = base_url()."rank/index/";
        $config['total_rows'] = $result_num;
        $config['per_page'] = '10';
        $config['full_tag_open'] = '<p>';
        $config['num_links'] = 10;
        $config['full_tag_close'] = '</p>';
        $config['first_link'] = '首页';
        $config['last_link'] = '最后一页';
        $config['next_link'] = '&gt;下一页';
        $config['prev_link'] = '上一页&lt;';
        $config ['suffix'] = "?c=" . $category  . "&nav=app";
        $config['first_url'] = base_url()."rank/index/?c=" . $category  . "&nav=app";
        $this->pagination->initialize($config);
        $data['turn_page'] = $this->pagination->create_links();
        
        $this->load->view('common/header_rank', $data);
        $this->load->view('main/app_rank',$data);
        $this->load->view('common/footer_rank');
    }

}

?>
