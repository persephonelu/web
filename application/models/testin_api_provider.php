<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/14
 * Time: 11:48
 */
//testin api
class Testin_api_provider extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

    //获得用户信息
    public function get_user_info($token)
    {
        //根据token获得用户email信息
        $sql = "select * from member_token
            where token='$token' limit 1";
        $result = $this->db->query($sql)->result_array();

        if (empty($result))    //如果没有对应的结果
        {
            $email = "no_user_error@appbk.com";
            $code = -1;
        }
        else
        {
            $email = $result[0]["email"];
            $code = 0;
        }
        $user_info = array();
        $user_info["code"] = $code;
        //具体数据
        $data = array();
        $data["appuid"] = $email;
        $data["name"] = $email;
        $data["email"] = $email;

        $user_info["data"] = $data;
        return $user_info;
    }

    //用户登录testin
    public function testin_login($token)
    {
        //step 1,获取host，暂时写死
        //step 2,向testin发送token信息
        $url = "http://sd005.testin.cn/sso/user.action";
        $auth_token = $token; #我方提供的token，可用此token访问appbk账号
        $api_key = '70657f263d828846fe9816f356e0c5fc';
        $secret_key = "2AAD4E2DD938C50F";
        $timestamp = 1000*time();//ms
        $data = array('op'=>'ThirdParty.authenticate',
                'apikey'=>$api_key,
                'timestamp'=>$timestamp,
                'authtoken'=>$auth_token
            );

        $sig = "apikey=". $api_key . 'authtoken=' . $auth_token . "op=ThirdParty.authenticatetimestamp=" . (string)$timestamp . $secret_key;
        $data["sig"] = md5($sig);
        $post = json_encode($data);
        $result = $this->http_post_data($url, $post);
        $result_json = json_decode($result,true);
        $sid = $result_json["data"]["sid"];
        //构造用户访问的url，返回
        $user_url = "http://realauto.testin.cn/nativeapp.action?op=App.testuploadBeta&authtoken=" . $sid;
        return array("url"=>$user_url,"state"=>0);

    }

    //php post数据
    public function http_post_data($url, $post)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_POST           => false,
            CURLOPT_POSTFIELDS     => $post,
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

?>