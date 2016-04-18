<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//新闻数据源
class News extends CI_Controller {

    public function index()
    {
        $this->load->view('news/header');
        $this->load->view('news/index');
        $this->load->view('news/footer_index');
    }
     
    //给web界面提供的数据
    public function search($page=1)
    {
        if ( isset($_REQUEST["q"]) )
        {
            $query = $_REQUEST["q"];
        }
        else
        {
            $query = "中国好声音";
        }
        
        $search_type = "News"; //固定为搜索新闻
        $start = ($page-1)*10; //开始的记录index
        $limit = 10;//每页10个结果，暂时固定
        $result = $this->news_provider->search($query, $search_type, $start, $limit);
        $data["docs"] = $result;
        $record_num = 100;//固定为10页
        $data["query"] = $query;

        //翻页
        $this->load->library('pagination');
        $config['uri_segment'] = 3; //翻页是第几个参数
        $config['base_url'] = base_url()."news/search/";
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
        $config['first_url'] = base_url()."news/search/?q=".$query;
        $this->pagination->initialize($config);
        $data['turn_page'] = $this->pagination->create_links();

        $this->load->view('news/header');
        $this->load->view('news/list',$data);
        $this->load->view('news/footer');
    }

    public function search_json($page=1)
    {
        header('Content-type:text/json;charset=utf-8');
        if ( isset($_REQUEST["q"]) )
        {
            $query = $_REQUEST["q"];
        }
        else
        {
            $query = "中国好声音";
        }
        
        $search_type = "News"; //固定为搜索新闻
        $start = ($page-1)*10; //开始的记录index
        $limit = 10;//每页10个结果，暂时固定
        
        $result = $this->news_provider->search_json($query, $search_type, $start, $limit);
        echo $result;
    } 
}
