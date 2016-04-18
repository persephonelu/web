<?php

//sina weibo api
include_once( 'resource/weibo_api/config.php' );
include_once( 'resource/weibo_api/saetv2.ex.class.php' );

//用户相关的model
class User_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

    
    #获得登录的url
    public function get_login_url()
    {
        $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
        $code_url = $o->getAuthorizeURL(WB_CALLBACK_URL);
        return $code_url;
    }

    //微博用户登录检测，写入session
    public function weibo_user_login($code)
    {
        //step 1, 获取微博用户的uid
        $user_info = $this->get_weibo_user_info($code);
        $uid = $user_info["id"];

        //step 2, 查看是否已经存在
        $email = $uid . "@weibo.com";
        $password = $uid;
        $nickname = $user_info["screen_name"];
        
        $sql = "select count(*) as num from member where email='$email'";
        $result = $this->db->query($sql)->result_array();
        $result_number = $result[0]["num"];
        
        if ( 0 == $result_number ) //如果email没注册过
        {
            $data["email"] = $email;
            $data["password"] = $password;
            $data["nickname"] = $nickname;
            //注册账号，并写session
            $this->reg_user($data);
        }
        else //如果已经登录过
        {
            $this->write_session($email);
        }
    }

    //获得weibo用户的id
    public function get_weibo_user_info($code)
    {
        $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
        $code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
        
        #step 1, get token
        $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
        $keys = array();
        $keys['code'] = $code;
        $keys['redirect_uri'] = WB_CALLBACK_URL;
        $token = $o->getAccessToken( 'code', $keys ) ;

        #step 2, get uid
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $token['access_token'] );
        $uid_get = $c->get_uid();
        $uid = $uid_get['uid']; //当前用户的uid
        $user_info = $c->show_user_by_id($uid);
        return $user_info;
    }

    //注册用户
    public function reg_user($data)
    {
        $email = $data["email"];
        $password = md5($data["password"]); //md5加密

        //如果没有设置nickname，则取email中@前的部分
        if ( !isset($data["nickname"]) )
        {
            $item = explode("@", $email);
            $nickname = $item[0];
        }
        else
        {
            $nickname = $data["nickname"];
        }

        $regdate = time();//linux时间
        $sql = "replace into member (`email`,`password`,`regdate`,`nickname`) 
            values ('$email','$password',$regdate,'$nickname')";
        $result = $this->db2->query($sql);
        
        //为用户添加一个默认的app，"天天飞车" app_id=728200220
        $app_id = "728200220";
        $CI = get_instance();
        $CI->load->model('user_app_provider');
        $CI->user_app_provider->add_user_app($email, $app_id);

        //为用户添加一个iTunes关键词
        $itunes_word = "飞车";
        $CI->user_app_provider->update_app_itunes_word($email, $app_id, $itunes_word);

        //为用户添加一个期望填写的关键词
        $wish_word = "赛车";
        $CI->user_app_provider->update_app_wish_word($email, $app_id, $wish_word);
         
        //记录session
        $this->write_session($email);
        return 0;
    }

    //将用户id,也就是email，记录到session内
    public function write_session($email)
    {
        $this->session->set_userdata("email", $email);
        return 0;
    }

    //退出登录，删除session
    public function logout()
    {
        $this->session->unset_userdata('email');
        return 0;
    }

    //检查用户是否已经登录
    //如果没有登录，返回false
    public function check_login($location_url="")
    {   
        $location_url = base_url() . "user/login";
        $result = $this->session->userdata('email');
        if ( $result ) //如果session设置了email,返回email
        {
            return $result;
        }
        else //否则转向到登陆页面
        {
            header("location:$location_url");
        }
    }
    
    //判断用户是否登录，返回用户信息，否则，返回空字符串
    public function get_login_user_info()
    {
        $email = $this->session->userdata('email');
        
        if ( $email ) //如果session设置了email,返回用户信息
        {
            $sql = "select * from member where email='$email'";
            $result = $this->db->query($sql)->result_array();
            return $result[0];
        }
        else //否则转向到登陆页面
        {
            return "";
        }
    }

    //检查用户注册的输入
    //input : 用户输入的数据
    //return : 正确，返回"0"，else，返回错误信息文本
    public function check_input($data)
    {
        //step 1,检测email是否符合规范,bootstrap已经检测
        //step 2，检测两次输入的密码是否一致
        $email = $data["email"];
        $password = $data["password"];
        $password_check = $data["password_check"];
        if ( $password!=$password_check )
        {
            return "两次密码输入不一致，请检查后再输入";
        } 
        
        //step 3，检测email是否已经存在
        $sql = "select count(*) as num from member where email='$email'";
        $result = $this->db->query($sql)->result_array();
        $result_number = $result[0]["num"];
        if ( 0 != $result_number )
        {
            //如果错误，返回一个错误信息
            $error = "该Email已经注册，请输入新的Email，或者点击右上角 '登录' ";
            return $error;
        }
        return 0;
    }

    //检查用户注册的输入
    //input : 用户输入的数据
    //return : 正确，返回字符串"0"，else，返回错误信息文本
    public function login_check($data)
    {
        //step 1，检测帐号或者密码是否正确
        $email = $data["email"];
        $password = md5($data["password"]); //md5加密
        $sql = "select count(*) as num from member where
            email='$email' and password='$password'";
        $result = $this->db->query($sql)->result_array();
        $result_number = $result[0]["num"];
        if ( 0 == $result_number )
        {
            //如果错误，返回一个错误信息
            $error = "帐号或密码错误，请重新输入,如仍有错误，请联系q群:39351116";
            return $error;
        } 
        else
        {
            //记录session
            $this->write_session($email);
            return "0";
        }
    }
}

?>
