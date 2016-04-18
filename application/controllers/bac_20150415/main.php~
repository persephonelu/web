<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#公共首页
class Main extends CI_Controller {

    #公共首页
	public function index()
    {
        #获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info();
        $this->load->view('common/header_main', $data);
        $this->load->view('main/index');
        $this->load->view('common/footer_main');
	}
    
}

?>
