<?php
#user app 模型
class User_app_provider extends CI_Model {
    
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

    /*********************用户竞品app*******************************/
    //根据用户id获得用户的app
    public function get_user_apps($email, $start=0, $limit=100)
    {
        $sql = "select * from member_app left join app_info 
            on member_app.app_id=app_info.app_id
            where email='$email'
            order by member_app.id desc limit $start, $limit";
        $result = $this->db2->query($sql)->result_array();
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
        $sql = "replace into member_app ( `name`, `from_plat`, `app_id`, `user_app_type`, `email`,`add_time`) 
            values ('$name', '$from_plat', '$app_id', $user_app_type,'$email',now())";
        $result = $this->db2->query($sql);
        return 0;
    }

    //删除用户app
    public function del_user_app($email, $app_id)
    {
        $sql = "delete from member_app where
            email='$email' and app_id='$app_id'";
        $result = $this->db2->query($sql);
        return $result;
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

    //替换所有用户的app
    public function replace_all_user_app($email, $app_id)
    {
        //step 1,删除用户所有的app
        $sql = "delete from member_app where
            email='$email'";
        $result = $this->db2->query($sql);
        //echo $sql;
        //step 2,插入新的app
        //分割字符串
        $delimiters = array(",","，","，"," ",'、','\n');
        $app_id_list = $this->multipleExplode($delimiters, $app_id);
        $value_list = array();
        $user_app_type = 1; //用户的app
        foreach ($app_id_list as $app_id)
        {
            $value_list[] = "('$email',  '$app_id', '$user_app_type' ,now())";
        }
        $value_list_join = join(",",$value_list);
        $sql = "replace into member_app ( `email`, `app_id`, `user_app_type`,`add_time`)
            values $value_list_join";
        //echo $sql;
        $result = $this->db2->query($sql);
        return 0;
    }

    //获得一个用户所有app的所有信息
    public function get_all_user_app_info($email, $start, $limit)
    {
        $sql = "select member_app.app_id, app_info,push_app_info.fetch_time
                from member_app
                left join push_app_info
                on member_app.app_id=push_app_info.app_id
                where email='$email' limit $start,$limit";
        $result = $this->db->query($sql)->result_array();

        //改写字段
        $final_result = array();
        $final_result["num"] = $this->get_all_user_app_info_num($email);
        if ($result)
        {
            $final_result["fetch_time"] = $result[0]["fetch_time"];
        }

        foreach ($result as $item)
        {
            $app_info = json_decode($item["app_info"],true);
            $final_result["results"][] = $app_info;
        }

        return $final_result;
    }

    public function get_all_user_app_info_num($email)
    {
        $sql = "select count(*) as num from member_app
                where email='$email'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]["num"];
    }

    /*********************用户竞品app*******************************/
    //获得用户某个app的竞品app列表
    public function get_user_app_competes($email, $app_id)
    {
        $sql = "select * from member_app_compete 
            left join app_info
            on member_app_compete.compete_app_id=app_info.app_id
            where member_app_compete.app_id='$app_id' and email='$email'";
        $result = $this->db2->query($sql)->result_array();
        return $result;
    }

    //增加某个app的竞品app
    public function add_user_app_compete($email, $app_id, $compete_app_id)
    {
        $sql = "replace into member_app_compete
            (app_id,compete_app_id,email,add_date)
            values
            ('$app_id','$compete_app_id','$email',curdate())";
        $result = $this->db2->query($sql);
        return 0;
    }

    //删除某个app的竞品app
    public function del_user_app_compete($email, $app_id, $compete_app_id)
    {
        $sql = "delete from member_app_compete
            where app_id='$app_id' and email='$email' 
            and compete_app_id='$compete_app_id'";
        $result = $this->db2->query($sql);
        return 0;
    }

    //获得用户某个app的推荐竞品,根据用户关键词推荐
    public function get_user_app_recommend_competes($email, $app_id)
    {
        $sql = "select * from app_info right join
                (
                    select aso_search_result_new.app_id,count(*) as freq from aso_search_result_new right join
                    (
                    select * from member_word where app_id='$app_id' and email='$email' and user_word_type=1
                    ) as seed_words
                    on aso_search_result_new.query=seed_words.word where pos<11
                    group by aso_search_result_new.app_id
                    order by freq desc limit 20
                ) as app_id_list
                on  app_info.app_id=app_id_list.app_id";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    /********************辅助函数****************************/
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
