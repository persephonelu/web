<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//用户app的管理
class User_app_process extends CI_Controller {

    //用户关键词管理
    public function keywords_manage()
    {
        //登录检测
        $data["email"] = $this->user_provider->check_login(); 
        //获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info();
        
        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "653350791"; //默认一个id
        }


        //获得app信息
        $data["app_info"] = $this->user_app_provider->get_app_info($app_id);
         
        $this->load->view('common/header_user_nav', $data);
        $this->load->view('user/keyword', $data);
        $this->load->view('common/footer_user');
    }
    
    //获得关键词列表,restful
    public function get_user_keywords($start=0)
    {
        //读取session,获得用户email
        $email = $this->user_provider->check_login_restful();
        if ( empty($email) )//方便调试，可以不登陆
        {
            $email = "58100533@qq.com";
        }

        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "728200220"; //默认一个id
        }
        
        //根据id获得app信息
        $data["app_info"] = $this->user_app_provider->get_app_info($app_id);

        //获得用户填写关键词的热度和搜索结果数指标
        $result = $this->user_app_optimal_provider
            ->get_word_rank_and_num($email, $data["app_info"], $start, 100);
 
        header('Content-type:text/json;charset=utf-8');
        echo json_encode($result);
    }
    
    //删除用户关键词,restful
    public function del_user_keyword()
    {
        //读取session,获得用户email
        $email = $this->user_provider->check_login_restful();
        if ( empty($email) )//方便调试，可以不登陆
        {
            $email = "58100533@qq.com";
        }

        if ( isset($_REQUEST["keyword"] ) )
        {
            $keyword = $_REQUEST["keyword"];
        }
        else
        {
            $keyword = "728200220"; //默认一个id
        }

        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "728200220"; //默认一个id
        }
        //用户填写的关键词
        $email = "58100533@qq.com";
        $result = $this->user_app_optimal_provider->del_user_keyword($email, $app_id, $keyword);
    } 

    //添加用户appstore关键词,restful
     public function append_user_keyword()
    {
        //读取session,获得用户email
        $email = $this->user_provider->check_login_restful();
        if ( empty($email) )//方便调试，可以不登陆
        {
            $email = "58100533@qq.com";
        }


        if ( isset($_REQUEST["keyword"] ) )
        {
            $keyword = $_REQUEST["keyword"];
        }
        else
        {
            $keyword = "728200220"; //默认一个id
        }
        
        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "728200220"; //默认一个id
        }

        //用户填写的关键词
        $result = $this->user_app_provider->update_app_itunes_word($email, $app_id, $keyword);
    }


    //为用户推荐新的关键词,restful
    public function get_recommend_keywords($start=0)
    {
        //读取session,获得用户email
        $email = $this->user_provider->check_login_restful();
        if ( empty($email) )//方便调试，可以不登陆
        {
            $email = "58100533@qq.com";
        }
 
        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "728200220"; //默认一个id
        }
        
        
        //根据id获得app信息
        $data["app_info"] = $this->user_app_provider->get_app_info($app_id);

        //为用户推荐新的关键词
        $result = $this->user_app_optimal_provider
            ->get_recommend_word($email, $app_id, $data["app_info"]["name"], $start);

        header('Content-type:text/json;charset=utf-8');
        echo json_encode($result); 
    }

    //获得相关app,restful
    public function get_relate_apps($start=0)
    {
        
        //读取session,获得用户email
        
        $email = $this->user_provider->check_login_restful();
        if ( empty($email) )//方便调试，可以不登陆
        {
            $email = "58100533@qq.com";
        }

        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "728200220"; //默认一个id
        }

        //根据id获得app信息
        $data["app_info"] = $this->user_app_provider->get_app_info($app_id);

        //为用户推荐相关app
        $result = $this->user_app_optimal_provider
            ->get_search_sim_app($data["app_info"]["name"], $start);
        
        header('Content-type:text/json;charset=utf-8');
        echo json_encode($result);
    }

    //获得app的关键词,restful
    public function get_app_keywords()
    {
        //读取session,获得用户email
        $email = $this->user_provider->check_login_restful();
        if ( empty($email) )//方便调试，可以不登陆
        {
            $email = "58100533@qq.com";
        }

        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "728200220"; //默认一个id
        }
        //根据id获得app信息
        $app_info = $this->user_app_provider->get_app_info($app_id);

        $result = $this->user_app_optimal_provider
            ->get_app_keywords($app_info["name"]);
 
        header('Content-type:text/json;charset=utf-8');
        echo json_encode($result);
 
    }
}
