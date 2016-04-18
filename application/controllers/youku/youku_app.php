<?php
class Youku_app extends CI_Controller {
	public function __construct()
	{
        parent::__construct();
        $this->load->model('youku/app_youku_provider');
    }

/*
    public function index()
	{
		$datetime	= date("Y-m-d", strtotime("-1 days"));
		$result	= $this->app_youku_provider->get_topapp_free($datetime);
		$data['json_str']	= json_encode($result);

		$this->load->view('youku/index', $data);
	}
*/
	public function get_app_rank()
	{
		$datetime	= date("Y-m-d", strtotime("-1 days"));
		$type 		= $_REQUEST['type'];
		if (isset($_REQUEST["category"]))
		{
			$category	= $_REQUEST["category"];
		}

		$result	= array();
		if ($type == "free")
		{
			$result = $this->app_youku_provider->get_topapp_free($datetime);
		}
		else if ($type == "paid")
		{
			$result	= $this->app_youku_provider->get_topapp_paid($datetime);
		}
		else if ($type == "all")
		{
			$result	= $this->app_youku_provider->get_topapp_all($datetime);
		}
/*
		$data['json_str']	= json_encode($result);
		$this->load->view('youku/index', $data);
*/
        $this->rest_provider->print_rest_json($result);
    }

	public function get_app_videos()
	{
		$result	= array();
		if (isset($_REQUEST["id"]))
		{
			$appid	= $_REQUEST["id"];
			$result = $this->app_youku_provider->get_youkuvideo_by_appid($appid);
		}
		else if (isset($_REQUEST["name"]))
		{
			$name	= $_REQUEST["name"];
			$datetime	= date("Y-m-d", strtotime("-1 days"));

			$result 	= $this->app_youku_provider->get_youkuvideo_by_appname($datetime, $name);
		}
/*
		$data['json_str']	= json_encode($result);
		$this->load->view('youku/index', $data);
*/
        $this->rest_provider->print_rest_json($result);
    }
}
?>
