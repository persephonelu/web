<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//用户app的管理
class User_app_process extends CI_Controller {

    
    //获得关键词列表,restful
    public function get_user_keywords()
    {
        //登录检测
        $email = $this->check_user_login();
        
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
            ->get_word_rank_and_num($email, $data["app_info"], 0, 100);
        
        $this->print_rest_json($result);
    }
    
    //删除用户关键词,restful
    public function del_user_keyword()
    {
        //登录检测
        $email = $this->check_user_login();

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
        $result = $this->user_app_optimal_provider->del_user_keyword($email, $app_id, $keyword);
    } 

    //添加用户appstore关键词,restful
     public function append_user_keyword()
    {
        //登录检测
        $email = $this->check_user_login();

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
        //登录检测
        $email = $this->check_user_login();
 
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
        
        //输出json数据
        $this->print_rest_json($result);
    }

    //获得相关app,restful,暂时不需要登陆
    public function get_relate_apps()
    {
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
            ->get_search_sim_app($data["app_info"]["name"], 0);
        $this->print_rest_json($result);    
    }

    //获得app的关键词,restful
    public function get_app_keywords()
    {
        //登录检测
        $email = $this->check_user_login();

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
 
        //输出json数据
        $this->print_rest_json($result); 
    }
    
    /***********************私有函数******************************/
    //rest json输出
    private function print_rest_json($result)
    {
        header("content-type:text/json;charset=utf-8");
        echo json_encode($result);
    }

    //用户登录检测
    private function check_user_login()
    {
        if ( isset($_REQUEST["email"] ) )
        {
            $email = $_REQUEST["email"];
        }
        else
        {
            $email = "unkown"; //没有此用户
        }

        //检查用户是否登录,如果未登录则直接跳转到错误页面
        $this->user_provider->check_login_restful($email);
        return $email;
    }

}
