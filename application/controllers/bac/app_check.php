<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_check extends CI_Controller {

	public function index()
    {
        //获得用户信息，如果没有登录，返回空
        $data["user"] = $this->user_model->get_user_info();
        
        //获取app name
        if ( isset($_REQUEST["n"]) )
        {
            $app_name = $_REQUEST["n"];
        }
        else
        {
            $app_name = "天天飞车";
        }

        //获取app描述
        if ( isset($_REQUEST["d"]) )
        {
            $app_description = $_REQUEST["d"];
        }
        else
        {
            $app_description = "天天飞车";
        }  
        
        //获取app分类
        if ( isset($_REQUEST["c"]) )
        {
            $app_ori_classes = $_REQUEST["c"];
        }
        else
        {
            $app_ori_classes = "体育";
        }
        
        $data["ori_classes"] = array("体育",
             "健康健美",
             "医疗",
             "参考",
             "商务",
             "商品指南",
             "图书",
             "天气",
             "娱乐",
             "导航",
             "工具",
             "摄影与录像",
             "效率",
             "教育",
             "新闻",
             "旅游",
             "游戏",
             "生活",
             "社交",
             "美食佳饮",
             "财务",
         "音乐");

        $data["app_name"] = $app_name;
        $data["keyword_judge"] = array(); //关键词判别
        
        if ($app_name != "" )
        {
            //获得app基础信息，这里用户填写的app_name可能是一部分，因此需要检索到原始名称
            $data["app_info"] = $this->tag_provider->get_app_info($app_name);
            $app_full_name = $data["app_info"]["name"];

            //获得app的关键词列表，根据原名
            $data["tag_list"] = $this->tag_provider->get_tag_list($app_full_name);

            //获得关键词列表，字符串形式
            $data["keywords"] = $this->tag_provider->get_keywords($app_name);

            //关键词判别
            $keywords = $data["keywords"];
            $select_day = "";
            $from_plat = "";
            $data["keyword_judge"] = $this->aso_provider->get_recommend($keywords, $select_day, $from_plat);

            //新关键词推荐
            $data["recommend"] = $this->aso_predict_provider->predict($keywords);

            //相似app查询
            $data["relate_app_list"] = $this->aso_predict_provider->keyword_to_app($keywords);
        } 
         

        $this->load->view('app2/header_index', $data);
		$this->load->view('app2/app_check', $data);
		$this->load->view('app2/footer');
    }

     public function get_same_tag()
    {
        if ( isset($_REQUEST["n1"]) )
        {
            $app_name1 = $_REQUEST["n1"];
        }
        else
        {
            $app_name1 = "天天飞车";
        }

        if ( isset($_REQUEST["n2"]) )
        {
            $app_name2 = $_REQUEST["n2"];
        }
        else
        {
            $app_name2 = "天天飞车";
        }
        $data["app_name1"] = $app_name1;
        $data["app_name2"] = $app_name2;

        
        $data["keywords1"] = $this->tag_provider->get_keywords($app_name1);
        $data["keywords2"] = $this->tag_provider->get_keywords($app_name2);
        $data["same_keywords"] = $this->tag_provider->get_same_keywords($app_name1, $app_name2);
        $this->load->view('app2/header_index', $data);
        $this->load->view('app2/app_relate_mysql_same', $data);
        $this->load->view('app2/footer');
    }
}

?>
