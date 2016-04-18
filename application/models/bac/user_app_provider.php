<?php
#user app 模型
class User_app_provider extends CI_Model {
    
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }
    
    //添加新增app(未提交市场的)
    public function add_user_new_app($email, $name, $category, $description)
    {
        $data["trackName"] = $name;
        $data["artworkUrl60"] = "http://appbk.oss-cn-hangzhou.aliyuncs.com/images/57.png";
        $data["fileSizeBytes"] = 0;
        $data["description"] = $description;
        $data["version"] = "0.0";
        $data["genres"][] = $category;
        $data["trackId"] = "9999-" . (string)rand(0,10000000);
        $data["trackViewUrl"] = base_url() . "?app_id=" . $data["trackId"]; 
        //插入app_info表
        $this->insert_app_info($data);

        //插入用户表
        $this->add_user_app($email, $data["trackId"]);
        return $data["trackId"];
    }
 
    //根据用户id获得用户的app
    //最大返回100个结果
    public function get_user_apps($email, $start=0, $limit=100)
    {
        //app info中一个app id可能对应多个app,故需要group
        $sql = "select * from member_app left join app_info 
            on member_app.app_id=app_info.app_id
            where email='$email' and app_info.from_plat='appstore'
            group by member_app.app_id
            order by member_app.id desc limit $start, $limit";
        $result = $this->db->query($sql)->result_array();
        return $result; 
    }

    //根据用户id获得用户的app
    public function get_user_app_num($email)
    {
        $sql = "select count(*) as result_num from member_app where email='$email'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]["result_num"];
    }

    //添加用户app数据
    public function add_user_app($email, $app_id)
    {
        //根据app_id从app_info中获取app信息
        $sql = "select * from app_info where app_id='$app_id'";
        $result = $this->db->query($sql)->result_array();

        $name = "";//app的name
        $from_plat = "appstore";//平台来源
        $user_app_type = 1; //用户的app
        if ( !empty($result) )//如果数据库中有这个记录
        {
            $name = $result[0]["name"];
        }
        else //如果没有，则下载
        {
            //下载
            $app_info = $this->download_app_info($app_id);
            if ( -1 != $app_info)
            {
                $name = $app_info["trackName"];
                //插入app_info数据库
                $this->insert_app_info($app_info);
            }
            else
            {
                return -1;
            }    
        }

        //插入数据库，app_id和email构成uniq key，一个app一个用户只能添加一次
        $sql = "replace into member_app ( `name`, `from_plat`, `app_id`, `user_app_type`, `email`) 
            values ('$name', '$from_plat', '$app_id', $user_app_type,'$email')";
        //echo $sql;
        $result = $this->db2->query($sql);
        return 0;
    }

    //根据app_id,下载appstore的app信息
    public function download_app_info($app_id="784574300")
    {

        $url = "http://itunes.apple.com/lookup?id=$app_id";
        $content = file_get_contents($url);
        $app_info = json_decode($content, true);
        if ( 0 == $app_info["resultCount"] )
        {
            return -1; //如果未能找到结果
        }
        else
        {
            return $app_info["results"][0];
        }
    }

    //将获得的app_info信息插入数据库
    public function insert_app_info($app_info)
    {
        $table_name = "app_info";

        $data["name"] = $app_info["trackName"];
        $data["package"] = $app_info["trackViewUrl"];
        $data["icon"] = $app_info["artworkUrl60"];
        $data["download_url"] = $app_info["trackViewUrl"];
        $data["size"] = ((int)$app_info["fileSizeBytes"])/(1024*1024);
        
        if ( isset($app_info["description"]) )
        {
            $data["brief"] = $app_info["description"];
        }
        
        $data["from_plat"] = "appstore";
        $data["version"] = $app_info["version"];
        $data["ori_classes"] = $app_info["genres"][0];
        $data["app_id"] = $app_info["trackId"];
        $data["download_level"] = 5 ;//下载级别，用户涉及的为5
        //var_dump($data);
        $result = $this->mysql_insert($data, $table_name);
        return $result;
    }

    //将json格式的数据插入数据库
    public function mysql_insert($data, $table_name)
    {
        $key_list = array();
        $value_list = array();
        
        foreach ($data as $key=>$value)
        {
            $key_list[] = $key;
            $value_list[] = "'" . $value . "'";
        }
        $key = implode(",",$key_list);
        $value = implode(",",$value_list);

        $sql = "replace into  " .  $table_name . " (" . $key . ") values (" . $value . ")";
        $result = $this->db2->query($sql);
    }

    //删除用户app
    public function del_user_app($email, $app_id)
    {
        $sql = "delete from member_app where
            email='$email' and app_id='$app_id'";
        $result = $this->db2->query($sql);
        return $result;
    }

    //根据用户和appid，获取用户填写的itunes的关键词
    public function get_app_keywords($email, $app_id)
    {
        $sql = "select word from member_word
            where email='$email' and app_id='$app_id' and user_word_type=1";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }  
    
    //更新用户填写的itunes关键词
    public function update_app_itunes_word($email, $app_id, $word_list)
    {
        //分割字符串
        $delimiters = array(",","，","，"," ");
        $word_list = $this->multipleExplode($delimiters, $word_list);
        $word_type = 1;
        foreach ($word_list as $word)
        {
            if ( $word=="" || $word==" ")
            {
                continue;
            }
            $sql = "replace into member_word 
                (`email`, `word`, `app_id`, `user_word_type`)
                values 
                ('$email', '$word', '$app_id', $word_type)";
            $this->db2->query($sql);
        }
        return 0;
    }

    //更新用户期望关键词
    public function update_app_wish_word($email, $app_id, $word_list)
    {
        //分割字符串
        $delimiters = array(",","，","，"," ");
        $word_list = $this->multipleExplode($delimiters, $word_list);
        $word_type = 2;
        foreach ($word_list as $word)
        {
            if ( $word=="" || $word==" ")
            {
                continue;
            }
            $sql = "replace into member_word 
                (`email`, `word`, `app_id`, `user_word_type`)
                values 
                ('$email', '$word', '$app_id', $word_type)";
            $this->db2->query($sql);
        }
        return 0;
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
