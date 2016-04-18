<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#搜索系统
class Search extends CI_Controller {

    //app 搜索
    public function index($start=0)
    {
        if ( isset($_REQUEST["q"]) )
        {
            $query = $_REQUEST["q"];
        }
        else
        {
            $query = "植物";
        }

       //获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info();

        //只要第一页结果，至多10个
        $data["docs"] = $this->data_provider->search($query, $start);
        $data["query"] = $query;
        $data["start"] = $start;
        $record_num  = $this->data_provider->search_result_num($query);
        
        //翻页
        $this->load->library('pagination');
        $config['uri_segment'] = 3; //翻页是第几个参数
        $config['base_url'] = base_url()."search/index/";
        $config['total_rows'] = $record_num;
        $config['per_page'] = '10';
        $config['full_tag_open'] = '<p>';
        $config['num_links'] = 10;
        $config['full_tag_close'] = '</p>';
        $config['first_link'] = '首页';
        $config['last_link'] = '最后一页';
        $config['next_link'] = '&gt;下一页';
        $config['prev_link'] = '上一页&lt;';
        $config ['suffix'] = "?q=".$query;
        $config['first_url'] = base_url()."search/index/?q=".$query;
        $this->pagination->initialize($config);
        $data['turn_page'] = $this->pagination->create_links(); 
	
        $this->load->view('common/header_rank', $data);
        $this->load->view('main/app_search',$data);
        $this->load->view('common/footer_rank');
   }

}

?>
