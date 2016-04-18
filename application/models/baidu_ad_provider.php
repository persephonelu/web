<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/18
 * Time: 19:22
 */
#功能函数
require_once "resource/baidu_api/sms_v3_AccountService.php";
//百度广告服务
class Baidu_ad_provider extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

    //根据auth code，获得 access token
    public function get_token($code)
    {

        $url = "https://open2.baidu.com/oauth2/token";
        $post_data = array(
            "client_id"=>"oIae0jr8Uk7wlp2Pl8A0Laly",
            "client_secret"=>"QTbFlLJCwSPUnbglVqwBOSgaIrN6VJq7",
            "grant_type"=>"authorization_code",
            "code"=>$code,
            "redirect_uri"=>"http://rest.appbk.com/baidu_ad/callback");

        $data = http_build_query($post_data);
        $result =  $this->http_post_data($url, $data);
        $auth_info = json_decode($result, true);

        $token = $auth_info["access_token"];
        $name = $auth_info["username"];
        $email = $name . "@baidu-ad.com";
        $refresh_token = $auth_info["refresh_token"];

        $salt = "appbk.com";
        $cur_time = (string)time();
        $login_token = md5($email.$salt.$cur_time);

        $sql = "replace into baidu_member (email,name,token,refresh_token,login_token)
              VALUES ('$email','$name','$token','$refresh_token','$login_token')";
        $this->db2->query($sql);

        //构造返回的url
        $host_split_list = explode(".",base_url());
        $length = count($host_split_list);
        $user_url = "http://www.". $host_split_list[$length-2] . "." . $host_split_list[$length-1] . "baidu.html/" . $login_token ;
        return $user_url;
    }

    //根据login_token获得用户基础信息
    public function get_user($login_token)
    {
        $sql = "select email,name from baidu_member WHERE
              login_token='$login_token'";
        $result = $this->db->query($sql)->result_array();
        return $result[0];
    }

    //根据refresh_token，更新access token
    public function refresh_token($email)
    {

        $refresh_token = "";
        $url = "https://open2.baidu.com/oauth2/token";
        $post_data = array(
            "client_id" => "oIae0jr8Uk7wlp2Pl8A0Laly",
            "client_secret" => "QTbFlLJCwSPUnbglVqwBOSgaIrN6VJq7",
            "grant_type" => "refresh_token",
            "token" => $refresh_token,
            "redirect_uri" => "http://rest.appbk.com/baidu_ad/callback");

        $data = http_build_query($post_data);
        $result = $this->http_post_data($url, $data);
        //更新用户token数据
    }

    //获得百度账号用户信息
    public function get_baidu_user_info($email)
    {
        /*
        $result = $this->get_token($code);
        $auth_info = json_decode($result, true);
        echo $auth_info["access_token"];
        $access_token = "9f123c51-9831-46e8-8a17-cdac8b449e29";
        */
        $service = new sms_v3_AccountService();
        $newheader = new AuthHeader();
        $newheader->setUsername("xxx");
        $newheader->setPassword("xxx");
        $newheader->setToken("92172e27239f827a9c13c0ee17ab785a");
        $newheader->setAccessToken("95125d16-48a1-4a82-9792-bdc428239553");
        $service->setAuthHeader($newheader);
        $info_request = new GetAccountInfoRequest();
        $info = $service->getAccountInfo($info_request);
        var_dump($info);
        echo $info->accountInfoType->userid;
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

    //restful调用中检查用户是否已经登录,并且调用的账号和登录账号一致
    //如果不一致，跳转到错误页面,输出一个展示错误的json数据后续设计检测token来验证
    //注： admin账号58100533@qq.com不需要检测登录
    public function check_login_restful($email)
    {
        //admin账号，不需要登录
        if ($email == "58100533@qq.com")
        {
            return 0;
        }

        $token = "";
        if ( isset($_REQUEST["token"])  )//如果没输入$token，检测cookie是
        {
            $token = $_REQUEST["token"];
        }
        else
        {
            if ( isset($_COOKIE["token"])  )
            {
                $token = $_COOKIE["token"];
            }
        }

        $error_url = base_url() . "rest/error?ec=-2&ei=no_log_in";
        if ( ""==$token )
        {
            header("location:$error_url");//如果没token，发错误信息
        }
        else
        {
            //检查token
            $sql = "select count(*) as num from baidu_member where email='$email' and login_token='$token'";
            $result = $this->db->query($sql)->result_array();
            $result_number = (int)$result[0]["num"];
            if ( 0 == $result_number )
            {
                //错误,没有颁发过次token
                header("location:$error_url");//转到错误页面，发错误信息
            }
            else
            {
                return 0;
            }

        }
    }

    /*****************用户App管理服务*******************/
    //获得用户的app
    public function get_user_app($email)
    {
        $sql = "select * from app_info where app_id in
              (select app_id from baidu_member_app
              where email='$email')";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //添加用户app
    public function add_user_app($email,$app_id)
    {
        /*
        $sql = "update baidu_member_app set app_id='$app_id'
                where email='$email'";
        */
        $sql = "insert baidu_member_app (email, app_id)
        values ('$email','$app_id')";
        $this->db2->query($sql);
        return 0;
    }

    //删除用户app
    public function del_user_app($email,$app_id)
    {
        $sql = "delete from baidu_member_app WHERE
            email='$email' and app_id='$app_id'";
        $this->db2->query($sql);
        return 0;
    }

    /*****************用户App广告关键词相关*******************/
    public function get_app_recommend_word($email, $app_id, $start=0, $limit=100)
    {
        $sql = "select * from
                (
                    select query,pos,rank from aso_search_result_new
                    left join aso_word_rank_new
                    on aso_search_result_new.query=aso_word_rank_new.word
                    where app_id='$app_id'
                ) as word_list
                left join baidu_keyword
                on baidu_keyword.word=word_list.query
                where rank>100
                order by rank DESC limit $start, $limit";
        $result = $this->db->query($sql)->result_array();

        //获得用户选择的扩展词,判断是否选择
        $user_final_words = $this->get_ad_dict($email, $app_id);
        $index = 0;
        foreach ($result as $item)
        {
            if (  array_key_exists($item["query"], $user_final_words) )
            {
                $result[$index]["select"] = 1;
            }
            else
            {
                $result[$index]["select"] = 0;
            }
            $index++;
        }

        $num = $this->get_app_recommend_word_num($app_id);
        return array("num"=>$num,"results"=>$result);

        return $result;

    }

    //app推荐词的个数
    public function get_app_recommend_word_num($app_id)
    {
        $sql = "select count(*) as result_num from aso_search_result_new where app_id='$app_id'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    //获得用户选择的一个app的ad词，返回一个dict
    public function get_ad_dict($email, $app_id)
    {
        $sql = "select * from baidu_member_word
            where email='$email' and app_id ='$app_id' and word_type=1";
        $result = $this->db2->query($sql)->result_array();
        $result_dict = array();
        foreach ($result as $item)
        {
            $result_dict[$item["word"]] = 1;
        }
        return $result_dict;
    }

    //添加用户关键词，replace添加
    //n ,关键词或者串
    public function add_user_word($email,$app_id,$n,$type)
    {
        //step 1, 对关键词串进行分割
        $delimiters = array(",","，","，"," ",'、');
        $word_list = $this->multipleExplode($delimiters, $n);

        //添加关键词
        foreach ($word_list as $word)
        {
            if ( $word=="" || $word==" ")
            {
                continue;
            }
            $sql = "replace into baidu_member_word
                (email, word, app_id, word_type, update_time)
                values
                ('$email', '$word', '$app_id', $type, now())";
            $this->db2->query($sql);
        }
        return 0;
    }

    //获得用户关键词
    public function get_user_words($email,$app_id,$type)
    {
        $sql = "select * from baidu_member_word WHERE
            email='$email' and $app_id='$app_id' and word_type=$type";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    //使用多个字符串分割
    public function multipleExplode($delimiters = array(), $string = '')
    {

        $mainDelim=$delimiters[count($delimiters)-1]; // dernier
        array_pop($delimiters);
        foreach($delimiters as $delimiter)
        {
            $string= str_replace($delimiter, $mainDelim, $string);
        }
        $result= explode($mainDelim, $string);
        return $result;
    }
}
?>