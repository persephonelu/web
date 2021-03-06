<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//用户app管理页面
class User_app extends CI_Controller {

    //首页 
    public function index($start=0)
    {
        //检查登陆,如果没有登录，则转到登录页面
        $data["email"] = $this->user_provider->check_login();
        //获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info(); 
        
        //获得用户的app列表
        $data["app_list"] = $this->user_app_provider->get_user_app($data["email"], $start);  
        
        
        //翻页 
        $record_num = $this->user_app_provider->get_user_app_num($data["email"]);
        $data["start"] = $start + 1;
        $this->load->library('pagination');
        $config['uri_segment'] = 3; //翻页是第几个参数
        $config['base_url'] = base_url()."user_app/index";
        $config['total_rows'] = $record_num;
        $config['per_page'] = '5';
        $config['full_tag_open'] = '<p>';
        $config['num_links'] = 10;
        $config['full_tag_close'] = '</p>';
        $config['first_link'] = '首页';
        $config['last_link'] = '最后一页';
        $config['next_link'] = '&gt;下一页';
        $config['prev_link'] = '上一页&lt;';
        $config ['suffix'] = "?nav=" . (isset($_REQUEST["nav"])?$_REQUEST["nav"]:"");
        $config['first_url'] = base_url()."user_app/index?"
                             . "nav=" . (isset($_REQUEST["nav"])?$_REQUEST["nav"]:"");
        $this->pagination->initialize($config);
        $data['turn_page'] = $this->pagination->create_links();
 
        $this->load->view('member/header_index', $data);
        $this->load->view('member/index', $data);
        $this->load->view('member/footer');
    }

    //用户app添加页面
    public function add_app()
    {
        $data["email"] = $this->user_provider->check_login();
                //获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info();

        $this->load->view('member/header_index', $data);
        $this->load->view('member/add_app');
        $this->load->view('member/footer');
    }

    //通过appid添加用户app
    public function add_app_by_id()
    {
        //检查登陆
        $data["email"] = $this->user_provider->check_login();
        
        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
            //如果不是空字符串，则添加数据
            if ( ""!=$app_id )
            {
                //添加用户数据
                $this->user_app_provider->add_user_app($data["email"], $app_id);
            }
            //$this->index();
        }
        $index_url = base_url() . "user_app";
        header("location:$index_url");
    }

    //通过appid删除用户的app
    public function del_app_by_id()
    {
        //检查登陆
        $data["email"] = $this->user_provider->check_login();
        
        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
            //如果不是空字符串，则添加数据
            if ( ""!=$app_id )
            {
                //添加用户数据
                $this->user_app_provider->del_user_app($data["email"], $app_id);
            }
            //$this->index();
        }
        $index_url = base_url() . "user_app";
        header("location:$index_url");
    }

    //通过搜索添加app页面
    //搜索结果展示页
    public function add_app_search($start=0)
    {

        $data["email"] = $this->user_provider->check_login();
                //获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info();
 
        if ( isset($_REQUEST["q"]) )
        {
            $query = $_REQUEST["q"];
        }
        else
        {
            $query = "植物";
        }

        
        
        $data["docs"] = $this->user_app_provider->search($query, $start);
        $data["query"] = $query;
        
        //翻页
        $record_num  = $this->user_app_provider->search_result_num($query);   
        $data["start"] = $start + 1;
        $this->load->library('pagination');
        $config['uri_segment'] = 3; //翻页是第几个参数
        $config['base_url'] = base_url()."user_app/add_app_search";
        $config['total_rows'] = $record_num;
        $config['per_page'] = '5';
        $config['full_tag_open'] = '<p>';
        $config['num_links'] = 10;
        $config['full_tag_close'] = '</p>';
        $config['first_link'] = '首页';
        $config['last_link'] = '最后一页';
        $config['next_link'] = '&gt;下一页';
        $config['prev_link'] = '上一页&lt;';
        $config ['suffix'] = "?q=" .$query . "&nav=" . (isset($_REQUEST["nav"])?$_REQUEST["nav"]:"");
        $config['first_url'] = base_url()."user_app/add_app_search?".
                             "q=" .$query . "&nav=" . (isset($_REQUEST["nav"])?$_REQUEST["nav"]:"");
        $this->pagination->initialize($config);
        $data['turn_page'] = $this->pagination->create_links();
 
        $this->load->view('member/header_index', $data);
        $this->load->view('member/add_app_search',$data);
        $this->load->view('member/footer');
    }

    //app 信息页
    public function app_process_app_info()
    {
        $data["email"] = $this->user_provider->check_login();

        //获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info();

        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "917670924"; //默认一个id
        }
        //根据id获得app信息
        $data["app_info"] = $this->user_app_provider->get_app_info($app_id);
        //总下载趋势
        //$data["download_trend"] = $this->predict_provider->get_download_trend($app_id);

        $this->load->view('member/header_nav', $data);
        $this->load->view('member/app_process_app_info', $data);
        $this->load->view('member/footer');
    }
}
