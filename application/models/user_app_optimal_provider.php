<?php
#用户app的优化
class User_app_optimal_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }
    
    
    #获得关键词列表的搜索热度和搜索结果数特征
    #输入：app_name
    #注意，一个用户可能为多个app加同一个关键词，故需要在最后限制app_id
    public function get_word_rank_and_num($email, $app_info, $start=0,$limit=10)
    {
        $app_id = $app_info["app_id"];
        $sql = "select member_word.word,rank,num,name,user_word_type
                 from member_word left join 
                ( select query,rank,num,name
                from aso_word_rank_new right join aso_result_num 
                on aso_word_rank_new.word=aso_result_num.query
                where query in 
                 (
                   select word from member_word 
                   where email='$email' and app_id='$app_id'
                 )
               ) as word_feature
               on member_word.word=word_feature.query
               where email='$email' and app_id='$app_id' limit $start, $limit";


        
        $result = $this->db->query($sql)->result_array();
         
        $i = 0; 
        $word_type_dict["1"] = "itunes";
        $word_type_dict["2"] = "期望添加";
        
        foreach ($result as $item)
        {
            if ( $result[$i]["num"] == "200" ) //如果是搜索结果的上限
            {
                $result[$i]["num"] = "200+";
            }
            $result[$i]["user_word_type"] = 
                $word_type_dict[$result[$i]["user_word_type"]];
            
            //对缺少热度或者搜索结果数的关键词的处理
            if ( empty($result[$i]["rank"] ) )
            {
                $result[$i]["rank"] = "待下载";
                
                //下载关键词热度数据，并插入数据
                $result[$i]["rank"] = 
                $this->download_and_insert_word_rank($email, $item["word"], $app_info); 
            }

            if ( empty($result[$i]["num"]))
            {
                $result[$i]["rank"] = "待下载";
                //后台下载搜索数据
                $this->push_for_get_search_result($item["word"]);
                /*
                //下载搜索数据，并插入数据库
                $search_result = $this->get_search_result($item["word"]);
                $result[$i]["num"] = $search_result["num"];
                $result[$i]["name"] = $search_result["first_app_name"];
                 */
            }
            $i++;
        }

        //var_dump($result);
        return $result;
    }

    //获得词的个数
    public function get_word_rank_and_num_num($email, $app_info)
    {
        $app_id = $app_info["app_id"];
        $sql = "select count(*) as result_num from member_word 
                   where email='$email' and app_id='$app_id'";

        $result = $this->db->query($sql)->result_array();
        return $result[0]["result_num"];
    }
 
    //下载词的rank数据，并插入数据库
    public function download_and_insert_word_rank($email, $word, $app_info)
    {
        //step 1,下载数据
        $word_rank_list = $this->get_suggestion($word);
        //如果没有对应的热词结果，返回0
        if (empty($word_rank_list))
        {
            return 0;
        }
        //插入数据库aso_word_rank_new
        $download_level = 5;//下载级别为5级
        $ori_classes = $app_info["ori_classes"];
        $i = 0;
        $from_plat = "appstore";
        $fetch_date = date("Y-m-d");
        foreach  ($word_rank_list as $item)
        {
            $word = $item["word"];
            $rank = $item["value"];
            $sql = "replace into aso_word_rank_new
                (word,rank,from_plat,fetch_date,ori_classes,download_level)
                values
                ('$word', $rank, '$from_plat','$fetch_date', '$ori_classes', $download_level)
                ";
            $this->db2->query($sql);
            $i++;
            if ($i>10) //最多只下载10个
            {
                break;
            }
        }
        //返回权重
        return $word_rank_list[0]["value"];
    }

    //后台下载搜索结果
    public function push_for_get_search_result($keyword)
    {
        $redis = new Redis(); #实例化redis类
        $redis->connect('10.132.34.73'); #连接服务器
        $redis->SELECT(2); //队列数据库
        $word = $keyword;
        $redis->lPush("appstore_search_word", $word);
        $redis->close(); #关闭连接 
    }
    //获得搜索结果
    public function get_search_result($keyword)
    {
        $url = "http://itunes.apple.com/search?entity=software&country=cn&explicit=NO&limit=200&term=$keyword";
        $content = file_get_contents($url);
        $result = json_decode($content, true); 

        if ($result["resultCount"] == 0)
        {
            $final_result["first_app_name"] = "";
            $final_result["num"] = 0;
        }
        else
        {
            $final_result["first_app_name"] = $result["results"][0]["trackName"];
            $final_result["num"] = $result["resultCount"];
            //插入数据库aso_search_num
            $name = $final_result["first_app_name"];
            $fetch_date = date("Y-m-d");
            $from_plat = "appstore";
            $download_level = 5;
            $num = $final_result["num"];
            $sql = "replace into aso_result_num
                 (`query`, `num`, `name`, `fetch_date`, `from_plat`, `download_level`)
                 values
                 ('$keyword', $num, '$name', '$fetch_date', '$from_plat', $download_level)";
           $this->db2->query($sql); 
        }    
        return $final_result;
    }

    //抓取搜索搜索热度接口 
    public function get_suggestion($keyword)
    {
        $url = "http://search.itunes.apple.com/WebObjects/MZSearchHints.woa/wa/hints?med
ia=software&cc=cn&q=".$keyword;
        $content = file_get_contents($url);
        $xml = simplexml_load_string($content); //创建 SimpleXML对象 
        //var_dump($xml->dict->array);
        $result = array();
        foreach ($xml->dict->array->dict as $word)
        {
            $suggestion = array();
            $suggestion["word"] = (string)$word->string[0];
            $suggestion["value"] = (string)$word->integer;
            $result[] = $suggestion; //append
        }
        //var_dump($result);
        return $result;
    }

    //获得为用户推荐的关键词
    //sql语句含义为，先获得关键词推荐，然后获得关键词的搜索结果数和热度特征
    //同时需要去掉用户已经填写的关键词
    public function get_recommend_word($email, $app_id, $app_name, $start=0)
    {
       $limit = 10; 
       
       /*
       $sql = "select word,rank,num,name
                from aso_word_rank_new left join aso_result_num 
                on aso_word_rank_new.word=aso_result_num.query
                where word in 
                (select word_list.tag as word from
                 (
                     select tag,sum(score) as final_score 
                     from aso_app_tag where name in 
                    ( 
                        select name from aso_search_result_new where query in 
                        ( 
                            select query from  aso_search_result_new 
                            where name='$app_name' and pos<11 
                        ) 
                        group by name 
                    ) 
                    and source=2 group by tag order by final_score desc limit 30
                ) as word_list
               )
               and word not in 
               ( select word from member_word where
               email='$email' and app_id='$app_id')
               order by rank desc limit $start,$limit";
         */
        
        $sql = "select feature_list.word,rank,num,name from
                (select word,rank,num,name from aso_word_rank_new left join aso_result_num 
                on aso_word_rank_new.word=aso_result_num.query
                ) as feature_list right join
                
(select word_list.tag as word from
   ( 
                     select tag,sum(score) as final_score 
                     from aso_app_tag right join 
                    ( 
                        select distinct(name) from aso_search_result_new where query in 
                        ( 
                            select query from  aso_search_result_new 
                            where name='$app_name' and pos<11 
                        ) 
                    ) as app_list
                    on aso_app_tag.name=app_list.name
                    and source=2 group by tag order by final_score desc limit 30
  ) as word_list 

) as tag_list on feature_list.word=tag_list.word 
               and feature_list.word not in 
               ( select word from member_word where
               email='$email' and app_id='$app_id')
order by rank desc limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        $i = 0;
        foreach ($result as $item)
        {
            if ( $result[$i]["num"] == "200" ) //如果是搜索结果的上限
            {
                $result[$i]["num"] = "200+";
            }
            $i++;
        } 
       //var_dump($result);
       return $result; 
    }

    //为用户推荐关键词个数 
    public function get_recommend_word_num($email, $app_id, $app_name)
    {
               /*
               $sql = "select count(*) as result_num
                from aso_word_rank_new left join aso_result_num 
                on aso_word_rank_new.word=aso_result_num.query
                where word in 
                (select word_list.tag as word from
                 (
                     select tag,sum(score) as final_score 
                     from aso_app_tag where name in 
                    ( 
                        select name from aso_search_result_new where query in 
                        ( 
                            select query from  aso_search_result_new 
                            where name='$app_name' and pos<11 
                        ) 
                        group by name 
                    ) 
                    and source=2 group by tag order by final_score desc limit 30
                ) as word_list
               )
               and word not in 
               ( select word from member_word where
               email='$email' and app_id='$app_id')";
        
    
        $sql = "";
        $result = $this->db->query($sql)->result_array();
        return $result[0]["result_num"];  
        */
        return 30;
    }
    
    //根据搜索结果，推荐相关app
    public function get_search_sim_app($app_name, $start=0)
    {
        $limit = 10; //每页10个结果
        /*
        $sql = "select name, app_id, sum(0.5+0.5/pos) as score from aso_search_result_new  
            where query in 
                (select query from aso_search_result_new where name='$app_name' and pos<11) 
            and pos<11 and name !='$app_name' group by name order by score desc limit $start,$limit";
         */
        $sql = "select app_list.name, app_info.app_id,score from 
            (select name,sum(0.5+0.5/pos) as score from aso_search_result_new  
            where query in 
           (select query from aso_search_result_new where name='$app_name' and pos<11) 
           and pos<11 and name !='$app_name' group by name) as app_list  
           left join app_info on app_list.name=app_info.name
           where app_info.from_plat='appstore'
           order by score desc limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        //var_dump($result);
        return $result;
    }

    
    //根据搜索结果，推荐相关app数量
    public function get_search_sim_app_num($app_name)
    {
        $sql = "select count(*) as result_num from (select name from aso_search_result_new  
            where query in 
                (select query from aso_search_result_new where name='$app_name' and pos<11) 
            and pos<11 and name !='$app_name' group by name) as app_list";
        $result = $this->db->query($sql)->result_array();
        //var_dump($result);
        return $result[0]["result_num"];
    }

    //根据app id，获得标签
    public function get_app_keywords($app_name, $start=0)
    {
        $limit = 10;
        $sql = "select query,rank,num,name
                from aso_word_rank_new right join aso_result_num 
                on aso_word_rank_new.word=aso_result_num.query
                where query in 
                 (
                    select distinct(tag) from aso_app_tag where name='$app_name' and source=2
                 )
                 order by rank desc
                limit $start, $limit";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //删除用户关键词
    public function del_user_keyword($email, $app_id, $keyword)
    {
        $sql = "delete from member_word where 
            email='$email' and app_id='$app_id' and word='$keyword'";
        $result = $this->db2->query($sql);
        return 0;
    } 
}
