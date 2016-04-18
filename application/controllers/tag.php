<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//用户兴趣标签排行榜信息
class  Tag extends CI_Controller {
    
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
            $category = "天气";
        }

        $data["category"] = $category;
        

        //获得appstore全部类别
        $data["category_list"] = $this->data_provider->get_category();
        //获得apstore游戏类别
        $data["game_category_list"] = $this->data_provider->get_game_category();
 

        //获得关键词排名结果
        $data["tags"] = $this->user_photo_provider->get_tags($category);
        
        $this->load->view('common/header_rank', $data);
        $this->load->view('main/tag_rank',$data);
        $this->load->view('common/footer_rank');
    }
}
?>
