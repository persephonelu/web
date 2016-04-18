<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#搜索热门词，直接读取xml接口即可，只有一页
class Search_word extends CI_Controller {

    //关键词 搜索
    public function index($start=0)
    {
        //获取用户信息 
        $data["user"] = $this->user_model->get_user_info();

        if ( isset($_REQUEST["q"]) )
        {
            $query = $_REQUEST["q"];
        }
        else
        {
            $query = "";
        } 	

        if ( ""==$query )
        {
            $data["suggestion"] = array();
        }
        else
        {
            $data["suggestion"] = $this->aso_provider->get_suggestion($query);
        }
        $data["query"] = $query;
        $this->load->view('common/header_rank', $data);
        $this->load->view('main/word_search',$data);
        $this->load->view('common/footer_rank');
   }

}

?>
