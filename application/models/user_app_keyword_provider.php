<?php
#user app keyword 模型
class User_app_keyword_provider extends CI_Model {
    
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

    /*********************************用户关键词管理部分*********************************/
    //根据用户和appid，获取用户填写的itunes的关键词，没有热度等信息
    public function get_user_app_keywords_no_feature($email, $app_id)
    {
        $sql = "select * from member_word
            where email='$email' and app_id ='$app_id' and user_word_type=1
            order by update_time";
        $result = $this->db2->query($sql)->result_array();
        return $result;
    }

    //根据用户和appid，获取用户填写的itunes的关键词，包括热度等信息
    public function get_user_app_keywords($email, $app_id, $start=0,$limit=30000)
    {
        $sql = "select * from
                (
                select  user_word_pos.word,pos,rank,update_time from
                (
                    select word,pos,update_time from
                    (select * from member_word  where email='$email' and app_id='$app_id' and user_word_type=1) as user_word_list
                    left join
                        (select * from aso_search_result_new where app_id='$app_id') as query_list
                        on user_word_list.word=query_list.query
                        ) as user_word_pos
                    left join aso_word_rank_new
                    on user_word_pos.word=aso_word_rank_new.word
                ) as user_word_pos_rank
                left join aso_result_num
                on user_word_pos_rank.word=aso_result_num.query
                order by update_time DESC
                limit $start, $limit";
    
        $result = $this->db2->query($sql)->result_array();
        
        //获得优化容易度
        $words = array();
        foreach ($result as $item)
        {
            $words[] = $item["word"];
        }
        $words_optimal_prob = $this->get_words_optimal_prob($words);
        //获得最终的结果，添加aso容易度和优化指数
        $index = 0;
        foreach ($result as $item)
        {
            $word_optimal_prob = $words_optimal_prob[$item["word"]];
            $word_rank_normal = $this->normalize_hot_value( (int)$item["rank"] );
            $aso_index = 100 * $word_optimal_prob * $word_rank_normal;//优化指数
            $result[$index]["aso_compete"] = round(100*(1-$word_optimal_prob), 1);
            $result[$index]["aso_index"] = round($aso_index, 1);
            $index++;
        }
        return $result; 
    }

    //添加用户填写的关键词
    public function add_user_app_keyword($email, $app_id, $word_list)
    {
        //分割字符串
        $delimiters = array(",","，","，"," ",'、','\n');
        $word_list = $this->multipleExplode($delimiters, $word_list);
        $word_type = 1;
        $value_list = array();
        foreach ($word_list as $word)
        {
            if ( $word=="" || $word==" ")
            {
                continue;
            }
            $value_list[] = "('$email', '$word', '$app_id', $word_type, now())";
        }
        $value_list_join = join(",",$value_list);
        $sql = "replace into member_word
                (email, word, app_id, user_word_type, update_time)
                values $value_list_join";
        $this->db2->query($sql);
        return 0;
    }

    //删除用户关键词,use_word_type=1
    public function del_user_app_keyword($email, $app_id, $keyword)
    {
        $sql = "delete from member_word where
            email='$email' and app_id='$app_id' and word='$keyword' and user_word_type=1";
        $result = $this->db2->query($sql);
        return 0;
    }

    /*********************************用户关键词拓展部分*********************************/
    //使用user_word_expand这个独立的库来进行相关操作，分种子词user_word_type=0
    //和用户选择的 拓展词user_word_type=1两种

    //获得种子词，无额外特征信息
    public function get_user_app_seed_keywords($email, $app_id)
    {
        $sql = "select * from member_word_expand
                where email='$email' and
                user_word_type=0 and app_id='$app_id'";
        $result = $this->db2->query($sql)->result_array();
        return $result;
    }

    //获得app用户的种子词,包含各种特征，user_word_type=0
    public function get_user_app_seed_keywords_feature($email, $app_id)
    {
        $sql = "select member_word_expand.word,rank,num,name,user_word_type,word_feature.app_id as app_id
                 from member_word_expand left join
                ( select query,rank,num,name,app_id
                from aso_word_rank_new right join aso_result_num
                on aso_word_rank_new.word=aso_result_num.query
                where query in
                 (
                   select word from member_word_expand
                   where email='$email' and app_id='$app_id' and user_word_type=0
                 )
               ) as word_feature
               on member_word_expand.word=word_feature.query
               where email='$email' and member_word_expand.app_id='$app_id' and user_word_type=0
               order by update_time limit 0,100";

        $result = $this->db2->query($sql)->result_array();

        //获得优化容易度
        $words = array();
        foreach ($result as $item)
        {
            $words[] = $item["word"];
        }
        $words_optimal_prob = $this->get_words_optimal_prob($words);
        //获得用户iTunes词
        $user_final_words = $this->get_user_app_keywords_dict($email, $app_id);

        //获得最终的结果，添加aso容易度和优化指数
        $index = 0;
        foreach ($result as $item)
        {
            $word_optimal_prob = $words_optimal_prob[$item["word"]];
            $word_rank_normal = $this->normalize_hot_value( (int)$item["rank"] );
            $aso_index = 100 * $word_optimal_prob * $word_rank_normal;//优化指数
            $result[$index]["aso_compete"] = round(100*(1-$word_optimal_prob), 1);
            $result[$index]["aso_index"] = round($aso_index, 1);
            if (  array_key_exists($item["word"], $user_final_words) )
            {
                $result[$index]["select"] = 1;
            }
            else
            {
                $result[$index]["select"] = 0;
            }
            $index++;
        }
        return $result;
    }

    //更新种子词，user_word_type=0：种子词，3：关注词，用于做种子词的备份
    public function update_user_app_seed_keywords($email, $app_id, $word_list)
    {
        //step 1 ,删除该用户，该app对应的关注词，user_word_type=3,只保留一个版本的关注词
        $sql = "delete from member_word_expand
            where email='$email' and user_word_type=3
            and app_id='$app_id'";
        $this->db2->query($sql);

        //step 2,将现有用户的种子词user_word_type=0，更新为user_word_type=3
        $sql = "update member_word_expand set user_word_type=3
            where email='$email' and
            user_word_type=0 and app_id='$app_id'";
        $this->db2->query($sql);

        //step3 ，解析词，并分别更新，可能是多个词隔开的
        $delimiters = array(",","，","，"," ","、","\n");
        $word_list = $this->multipleExplode($delimiters, $word_list);
        $word_type = 1;
        foreach ($word_list as $word)
        {
            if ( $word=="" || $word==" ")
            {
                continue;
            }
            $sql = "replace INTO member_word_expand
                (`email`, `word`, `app_id`, `user_word_type`, `update_time`)
                VALUES ('$email','$word','$app_id',0,NOW())";
            $this->db2->query($sql);
        }
        return 0;
    }

    //获取扩展词，包括热度等信息，user_word_type=1
    public function get_user_app_expand_keywords($email, $app_id, $start=0,$limit=100)
    {
        $sql = "select * from
                (
                select  user_word_pos.word,pos,rank from
                (
                    select word,pos from
                    (select * from member_word_expand where email='$email' and app_id='$app_id' and user_word_type=1) as user_word_list
                    left join
                        (select * from aso_search_result_new where app_id='$app_id') as query_list
                        on user_word_list.word=query_list.query
                        ) as user_word_pos
                    left join aso_word_rank_new
                    on user_word_pos.word=aso_word_rank_new.word
                ) as user_word_pos_rank
                left join aso_result_num
                on user_word_pos_rank.word=aso_result_num.query limit $start, $limit";

        $result = $this->db2->query($sql)->result_array();

        //获得优化容易度
        $words = array();
        foreach ($result as $item)
        {
            $words[] = $item["word"];
        }
        $words_optimal_prob = $this->get_words_optimal_prob($words);
        //获得最终的结果，添加aso容易度和优化指数
        $index = 0;
        foreach ($result as $item)
        {
            $word_optimal_prob = $words_optimal_prob[$item["word"]];
            $word_rank_normal = $this->normalize_hot_value( (int)$item["rank"] );
            $aso_index = 100 * $word_optimal_prob * $word_rank_normal;//优化指数
            $result[$index]["aso_compete"] = round(100*(1-$word_optimal_prob), 1);
            $result[$index]["aso_index"] = round($aso_index, 1);
            $index++;
        }
        return $result;
    }

    //获得扩展词，返回一个dict
    public function get_user_app_keywords_dict($email, $app_id)
    {
        $sql = "select * from member_word_expand
            where email='$email' and app_id ='$app_id' and user_word_type=1";
        $result = $this->db2->query($sql)->result_array();
        $result_dict = array();
        foreach ($result as $item)
        {
            $result_dict[$item["word"]] = 1;
        }
        return $result_dict;
    }

    //删除扩展关键词use_word_type=1
    public function del_user_app_expand_keyword($email, $app_id, $keyword)
    {
        $sql = "delete from member_word_expand where
            email='$email' and app_id='$app_id' and word='$keyword' and user_word_type=1";
        $result = $this->db2->query($sql);
        return 0;
    }

    //添加扩展关键词
    public function add_user_app_expand_keyword($email, $app_id, $word_list)
    {
        //分割字符串
        $delimiters = array(",","，","，"," ",'、');
        $word_list = $this->multipleExplode($delimiters, $word_list);
        $word_type = 1;
        foreach ($word_list as $word)
        {
            if ( $word=="" || $word==" ")
            {
                continue;
            }
            $sql = "replace into member_word_expand
                (email, word, app_id, user_word_type, update_time)
                values
                ('$email', '$word', '$app_id', $word_type, now())";
            $this->db2->query($sql);
        }
        return 0;
    }

    //将拓展词合并到当前版本的关键词
    public function merge_user_app_expand_keywords($email, $app_id)
    {
        $sql = " insert into member_word (email,word,app_id,user_word_type,update_time)
                select email,word,app_id,user_word_type,update_time from member_word_expand
                where word not in
                (select word from member_word where email='$email'
                and app_id='$app_id' and user_word_type=1)
                and email='$email' and app_id='$app_id'  and user_word_type=1";
        $this->db2->query($sql);
        return 0;
    }

    //一些关键词拓展方法

    //拓词方法1，类似app使用词，推荐新的关键词,最多50个
    public function get_word_recommend_keywords($email, $app_id)
    {
        //方法
        //step 1,根据种子词，找到命中的app，查询aso_search_result_new,pos<11
        //step 2,根据这些app的id，在aso_search_result_new查询，获得其对应关键词，把pos倒数相加，然后排序

        //echo "step 1 ". date("h:i:s") ."<br/>";
        /*
        $sql = "select  word,rank,num,name,app_id
                from aso_word_rank_new inner join
                (
                    select aso_result_num.query, num,name,app_id from aso_result_num right join
                    (
                        select query, sum(1/pos) as score from aso_search_result_new right join
                            (
                            select aso_search_result_new.app_id from aso_search_result_new right join
                            (
                            select * from member_word_expand where app_id='$app_id' and email='$email' and user_word_type=0
                            ) as seed_words
                            on aso_search_result_new.query=seed_words.word where pos<11
                            ) as seed_app_id
                        on aso_search_result_new.app_id=seed_app_id.app_id
                        group by query
                        order by score desc
                        limit 100
                    ) as seed_word
                    on aso_result_num.query=seed_word.query
                ) as seed_word_search
                on seed_word_search.query=aso_word_rank_new.word
                order by rank desc limit 0,50";
        */
        $sql = "select  word,rank,num,name,app_id
                from aso_word_rank_new inner join
                (
                    select aso_result_num.query, num,name,app_id from aso_result_num right join
                    (
                        select query, sum(1/pos) as score from aso_search_result_new_recommend right join
                            (
                            select aso_search_result_new_recommend.app_id from aso_search_result_new_recommend right join
                            (
                            select * from member_word_expand where app_id='$app_id' and email='$email' and user_word_type=0
                            ) as seed_words
                            on aso_search_result_new_recommend.query=seed_words.word where pos<11
                            ) as seed_app_id
                        on aso_search_result_new_recommend.app_id=seed_app_id.app_id
                        group by query
                        order by score desc
                        limit 100
                    ) as seed_word
                    on aso_result_num.query=seed_word.query
                ) as seed_word_search
                on seed_word_search.query=aso_word_rank_new.word
                order by rank desc limit 0,50";
        $result = $this->db->query($sql)->result_array();
        //echo "step 2 ". date("h:i:s"). "<br/>";
        //获得优化容易度
        $words = array();
        foreach ($result as $item)
        {
            $words[] = $item["word"];
        }
        $words_optimal_prob = $this->get_words_optimal_prob($words);
        //获得用户选择的扩展词
        $user_final_words = $this->get_user_app_keywords_dict($email, $app_id);

        //获得最终的结果，添加aso容易度和优化指数
        $index = 0;
        foreach ($result as $item)
        {
            $word_optimal_prob = $words_optimal_prob[$item["word"]];
            $word_rank_normal = $this->normalize_hot_value( (int)$item["rank"] );
            $aso_index = 100 * $word_optimal_prob * $word_rank_normal;//优化指数
            $result[$index]["aso_compete"] = round(100*(1-$word_optimal_prob), 1);
            $result[$index]["aso_index"] = round($aso_index, 1);
            if (  array_key_exists($item["word"], $user_final_words) )
            {
                $result[$index]["select"] = 1;
            }
            else
            {
                $result[$index]["select"] = 0;
            }
            $index++;
        }
        return $result;
    }

    //拓词方法2， 语义扩展
    public function  get_word_relate_keywords($email, $app_id)
    {
        /*
        $sql = "select query as word,rank,num,name,app_id
                from aso_word_rank_new right join aso_result_num
                on aso_word_rank_new.word=aso_result_num.query
                where query in
                (
                select query from
                (
                     select relate_word  as query from aso_relate_word inner join
                (
                     select * from member_word_expand where app_id='$app_id' and email='$email' and user_word_type=0
                ) as word_list
                on word_list.word=aso_relate_word.word limit 0,100
                ) as relate_word
                )
                order by rank desc limit 0,50";
        */
        $sql = "select query as word,rank,num,name,app_id
                from aso_word_rank_new right join aso_result_num
                on aso_word_rank_new.word=aso_result_num.query
                where query in
                (
                select query from
                (
                     select relate_word  as query from aso_relate_word inner join
                (
                     select * from member_word_expand where app_id='$app_id' and email='$email' and user_word_type=0
                ) as word_list
                on word_list.word=aso_relate_word.word limit 0,100
                ) as relate_word
                )
                order by rank desc limit 0,50";

        $result = $this->db->query($sql)->result_array();
        //获得优化容易度
        $words = array();
        foreach ($result as $item)
        {
            $words[] = $item["word"];
        }
        $words_optimal_prob = $this->get_words_optimal_prob($words);
        //获得用户iTunes词
        $user_final_words = $this->get_user_app_keywords_dict($email, $app_id);

        //获得最终的结果，添加aso容易度和优化指数
        $index = 0;
        foreach ($result as $item)
        {
            $word_optimal_prob = $words_optimal_prob[$item["word"]];
            $word_rank_normal = $this->normalize_hot_value( (int)$item["rank"] );
            $aso_index = 100 * $word_optimal_prob * $word_rank_normal;//优化指数
            $result[$index]["aso_compete"] = round(100*(1-$word_optimal_prob), 1);
            $result[$index]["aso_index"] = round($aso_index, 1);
            if (  array_key_exists($item["word"], $user_final_words) )
            {
                $result[$index]["select"] = 1;
            }
            else
            {
                $result[$index]["select"] = 0;
            }
            $index++;
        }
        return $result;
    }

    //拓词方法3，词根扩展词
    public function  get_word_expand_keywords($email, $app_id)
    {
        /*
        $sql = "select distinct(word),rank,num,name,app_id
                from aso_result_num right join
                (
                    select  aso_word_rank_new.word,rank from aso_word_rank_new,
                    (
                         select word from member_word_expand where app_id='$app_id' and email='$email' and user_word_type=0
                    )  as word_list
                    where instr(aso_word_rank_new.word,word_list.word)>0
                    order by rank desc limit 100
                ) as word_rank_list
                on word_rank_list.word=aso_result_num.query";
        */
        $sql = "select distinct(word),rank,num,name,app_id
                from aso_result_num right join
                (
                    select  word_recommend.word,word_recommend.rank from
                    (
                      select * from aso_word_rank_new_recommend
                    ) as word_recommend,
                    (
                         select word from member_word_expand where app_id='$app_id' and email='$email' and user_word_type=0 limit 10
                    )  as word_list
                    where instr(word_recommend.word,word_list.word)>0
                    order by rank desc limit 100
                ) as word_rank_list
                on word_rank_list.word=aso_result_num.query";

        //echo $sql;
        $result = $this->db2->query($sql)->result_array();
        //获得优化容易度
        $words = array();
        foreach ($result as $item)
        {
            $words[] = $item["word"];
        }
        $words_optimal_prob = $this->get_words_optimal_prob($words);
        //获得用户iTunes词
        $user_final_words = $this->get_user_app_keywords_dict($email, $app_id);

        //获得最终的结果，添加aso容易度和优化指数
        $index = 0;
        foreach ($result as $item)
        {
            $word_optimal_prob = $words_optimal_prob[$item["word"]];
            $word_rank_normal = $this->normalize_hot_value( (int)$item["rank"] );
            $aso_index = 100 * $word_optimal_prob * $word_rank_normal;//优化指数
            $result[$index]["aso_compete"] = round(100*(1-$word_optimal_prob), 1);
            $result[$index]["aso_index"] = round($aso_index, 1);
            if (  array_key_exists($item["word"], $user_final_words) )
            {
                $result[$index]["select"] = 1;
            }
            else
            {
                $result[$index]["select"] = 0;
            }
            $index++;
        }
        return $result;
    }

    //拓展方法4，根据竞品app，获得关键词推荐
    public function get_compete_apps_keywords($email, $app_id)
    {
        //方法
        //step 1,根据种子词，找到命中的app，查询aso_search_result_new,pos<11
        //step 2,根据这些app的id，在aso_search_result_new查询，获得其对应关键词，把pos倒数相加，然后排序

        //echo "step 1 ". date("h:i:s") ."<br/>";
        $sql = "select  word,rank,num,name,app_id,match_num
                from aso_word_rank_new inner join
                (
                    select aso_result_num.query, num,name,app_id,match_num from aso_result_num right join
                    (
                        select query, sum(1/pos) as score,count(*) as match_num from aso_search_result_new right join
                            (
                                select compete_app_id from member_app_compete where email='$email' and app_id='$app_id '
                            ) as seed_app_id
                        on aso_search_result_new.app_id=seed_app_id.compete_app_id
                        group by query
                        order by score desc
                        limit 100
                    ) as seed_word
                    on aso_result_num.query=seed_word.query
                ) as seed_word_search
                on seed_word_search.query=aso_word_rank_new.word
                order by rank desc limit 0,50";
        $result = $this->db->query($sql)->result_array();
        //echo "step 2 ". date("h:i:s"). "<br/>";
        //获得优化容易度
        $words = array();
        foreach ($result as $item)
        {
            $words[] = $item["word"];
        }
        $words_optimal_prob = $this->get_words_optimal_prob($words);
        //获得用户iTunes词
        $user_final_words = $this->get_user_app_keywords_dict($email, $app_id);

        //获得最终的结果，添加aso容易度和优化指数
        $index = 0;
        foreach ($result as $item)
        {
            $word_optimal_prob = $words_optimal_prob[$item["word"]];
            $word_rank_normal = $this->normalize_hot_value( (int)$item["rank"] );
            $aso_index = 100 * $word_optimal_prob * $word_rank_normal;//优化指数
            $result[$index]["aso_compete"] = round(100*(1-$word_optimal_prob), 1);
            $result[$index]["aso_index"] = round($aso_index, 1);
            if (  array_key_exists($item["word"], $user_final_words) )
            {
                $result[$index]["select"] = 1;
            }
            else
            {
                $result[$index]["select"] = 0;
            }
            $index++;
        }
        return $result;
    }


    /*********************************用户关注的词部分，member_word_expand,user_word_type=2*********************************/
    //增加用户关注的词
    public function add_user_watch_keyword($email, $app_id, $name)
    {
        //分割字符串,空格的不删,因为可能是英文,其他的全干掉
        $delimiters = array(",","，","，",'、','\n');
        $word_list = $this->multipleExplode($delimiters, $name);
        $word_type = 2; //用户关注的词
        $value_list = array();
        foreach ($word_list as $word)
        {
            if ( $word=="" || $word==" ")
            {
                continue;
            }
            $value_list[] = "('$email', '$word', '$app_id', $word_type, now())";
        }
        $value_list_join = join(",",$value_list);
        $sql = "replace into member_word_expand
                (email, word, app_id, user_word_type, update_time)
                values $value_list_join";
        $this->db2->query($sql);
        return 0;
    }

    //删除用户关注的词
    public function del_user_watch_keyword($email, $app_id, $name)
    {
        $sql = "delete from member_word_expand where
            email='$email' and app_id='$app_id' and word='$name' and user_word_type=2";
        $result = $this->db2->query($sql);
        return 0;
    }

    //获得app的可能的关键词,以及用户关注的词
    //simple是是否精简模式的标记,如果=1,则采用精简模式,排名数据不加字段名
    public function get_app_possible_and_watch_keywords($email, $app_id, $start, $limit, $simple)
    {
        //step 0， 获得top3,top10,全部的关键词个数
        $sql = "select count(*) as num from aso_search_result_new
            where app_id='$app_id' and pos<=3";
        $result = $this->db->query($sql)->result_array();
        $top3_num = $result[0]["num"];

        $sql = "select count(*) as num from aso_search_result_new
            where app_id='$app_id' and pos<=10";
        $result = $this->db->query($sql)->result_array();
        $top10_num = $result[0]["num"];

        $sql = "select count(*) as num from aso_search_result_new
            where app_id='$app_id'";
        $result = $this->db->query($sql)->result_array();
        $num = $result[0]["num"];


        //step 1，获得App当前最新的关键词
        $sql = "select word_list.query as query,pos,rank,fetch_time,num from
                (
                    select query,pos,rank,fetch_time from aso_search_result_new
                    left join aso_word_rank_new
                    on aso_search_result_new.query=aso_word_rank_new.word
                    where app_id='$app_id' and query not in
                    (SELECT word from member_word_expand where app_id='$app_id'
                    and email='$email' and user_word_type=2)
                    group by query
                    order by pos limit $start, $limit
                ) as word_list
                left join aso_result_num
                on aso_result_num.query=word_list.query";
        $cur_day_result = $this->db->query($sql)->result_array();

        //对于热度为-1,或者没热度的,设置为0
        $i = 0;
        foreach ($cur_day_result as $item)
        {
            if ($item["rank"]<0)
            {
                $cur_day_result[$i]["rank"] = 0;
            }
            $i++;
        }
        //step 2,获得用户关注的词，最新的数据
        //注意使用用户的word作为query，因为有可能用户添加到词不在库里
        $sql =  "select word as query,pos,rank,fetch_time,num,app_id from
                (
                select  user_word_pos.word as word,pos,rank,update_time,fetch_time from
                (
                    select word,pos,update_time,fetch_time from
                    (select * from member_word_expand  where email='$email' and app_id='$app_id' and user_word_type=2) as user_word_list
                    left join
                        (select * from aso_search_result_new where app_id='$app_id') as query_list
                        on user_word_list.word=query_list.query
                        ) as user_word_pos
                    left join aso_word_rank_new
                    on user_word_pos.word=aso_word_rank_new.word
                ) as user_word_pos_rank
                left join aso_result_num
                on user_word_pos_rank.word=aso_result_num.query
                order by update_time DESC";

        $cur_day_user_result = $this->db->query($sql)->result_array();
        //echo $sql;

        //step 3,获得昨天的全部关键词，
        //昨天可能没有用户定义的关键词，因此可以当做昨天没有结果处理
        //数据的比较，也和当天0点到1点的数据比较，如果没有当天数据，则和昨天的比较。
        $cur_day = date("Y-m-d");//默认今天凌晨的数据
        $sql = "select * from aso_search_result
            where app_id='$app_id' and fetch_date='$cur_day'";
        $search_result = $this->db->query($sql)->result_array();

        if (!$search_result)  //如果今天的数据为空,使用昨日的数据
        {
            $pre_day = date("Y-m-d", time() - 1 * 24 * 60 * 60);//昨天的数据
            $sql = "select * from aso_search_result
            where app_id='$app_id' and fetch_date='$pre_day'";
            $search_result = $this->db->query($sql)->result_array();
        }

        //转化成dict的形式，key是关键词，value是位置
        $search_result_dict = array();
        foreach ($search_result as $app)
        {
            $search_result_dict[$app["query"]] = $app["pos"];
        }

        //step 3,进行pos的比较
        $final_result = array();

        //处理用户关注的词,只在第一页结果中添加
        if (0 == (int)$start)
        {
            foreach ($cur_day_user_result as $app) {
                $query = $app["query"];
                $increase = ""; //增长多少
                $increase_sign = "";//增长标记

                if (array_key_exists($query, $search_result_dict)) //如果包含这个词
                {
                    $increase = (int)$search_result_dict[$query] - (int)$app["pos"];
                    if ($increase > 0) {
                        $increase_sign = "↑"; //排名提升
                    } elseif ($increase < 0) {
                        $increase_sign = "↓"; //排名下载
                    } else {
                        $increase_sign = "-"; //排名不变
                    }
                } else //如果昨天没有这个词
                {
                    $increase = 0;
                    $increase_sign = "N";
                }

                $app["increase"] = $increase;
                $app["increase_sign"] = $increase_sign;
                $app["watch"] = 1;//用户是否关注，0未关注，1关注

                //加入final result
                $final_result[] = $app;
            }
        }
        //处理app的关键词，不包括用户的关键词
        foreach ($cur_day_result as $app)
        {
            $query = $app["query"];
            $increase = ""; //增长多少
            $increase_sign = "";//增长标记

            if ( array_key_exists($query,$search_result_dict) ) //如果包含这个词
            {
                $increase =  (int)$search_result_dict[$query] - (int)$app["pos"];
                if ( $increase >0 )
                {
                    $increase_sign = "U"; //排名提升
                }
                elseif ($increase<0)
                {
                    $increase_sign = "D"; //排名下载
                }
                else
                {
                    $increase_sign = "R"; //排名不变
                }
            }
            else //如果昨天没有这个词
            {
                $increase = 0;
                $increase_sign = "N";
            }

            $app["increase"] = $increase;
            //$app["increase_sign"] = $increase_sign;
            $app["watch"] = 0;//用户是否关注，0未关注，1关注

            //加入final result
            $final_result[] = $app;
        }


        $result = array();
        $result["top3_num"] = $top3_num;//top3的结果个数
        $result["top10_num"] = $top10_num;//top10的结果个数
        $result["num"] = $num;//全部覆盖词的个数
        $result["results"] = $final_result;


        //如果是精简模式,去掉字段名
        $simple_results = array();
        if (1 == (int)$simple)
        {
            foreach ($result["results"] as $item )
            {
                $one_result = array($item["query"],$item["pos"],
                    $item["increase"],$item["rank"],$item["num"],$item["watch"]);
                $simple_results[] = $one_result;
            }

            $result["results"] = $simple_results;
        }

        return $result;
    }


    /*********************************关键词监控部分********************************/
    //获得用户关键词的搜索结果位置信息和搜索词的热度，并计算曝光度,word_type=1的词，也就是itunes关键词
    public function get_app_keywords_rank_and_pos($email, $app_id,$date)
    {
        if ("" == $date)//如果没有设置日期
        {
            //获得搜索热度数据
            $sql = "select * from aso_word_rank_new
                    where word in
                    (
                       select word from member_word
                       where email='$email' and app_id='$app_id' and user_word_type=1
                    )";

            $word_rank_result = $this->db->query($sql)->result_array();//搜索热度结果数据

            //获得app在搜索结果中的位置信息，部分词的命中结果中可能暂时不包括这个app
            $sql = "select * from aso_search_result_new
                    where query in
                    (
                       select word from member_word
                       where email='$email' and app_id='$app_id'  and user_word_type=1
                    )
                    and app_id='$app_id'";
            $word_pos_result = $this->db->query($sql)->result_array();//搜索热度结果位置数据
        }
        else //如果设置了日期
        {
            $cur_time = time();//当前时间
            $one_month_ago = time() - 30*24*60*60;//一个月之前的时间
            $select_date = strtotime($date);//选择的日期

            if ($select_date>$one_month_ago) //如果是前一个月之后的日期，选择aso_search_result
            {
                //获得app在搜索结果中的位置信息，部分词的命中结果中可能暂时不包括这个app
                $sql = "select * from aso_search_result
                    where query in
                    (
                       select word from member_word
                       where email='$email' and app_id='$app_id'  and user_word_type=1
                    )
                    and app_id='$app_id' and  fetch_date='$date'";
                $word_pos_result = $this->db->query($sql)->result_array();//搜索热度结果位置数据
            }
            else //如果更久之前的，aso_search_result_recommend
            {
                //获得app在搜索结果中的位置信息，部分词的命中结果中可能暂时不包括这个app
                $sql = "select * from aso_search_result_recommend
                    where query in
                    (
                       select word from member_word
                       where email='$email' and app_id='$app_id'  and user_word_type=1
                    )
                    and app_id='$app_id' and  fetch_date='$date'";
                $word_pos_result = $this->db->query($sql)->result_array();//搜索热度结果位置数据
            }
            //获得搜索热度数据
            $sql = "select * from aso_word_rank
                    where word in
                    (
                       select word from member_word
                       where email='$email' and app_id='$app_id' and user_word_type=1
                    ) and fetch_date='$date'";

            $word_rank_result = $this->db->query($sql)->result_array();//搜索热度结果数据
        }


        //处理成dict
        $word_pos_dict = array();
        foreach ($word_pos_result as $item)
        {
            $word_pos_dict[ $item["query"] ] = (int)$item["pos"];
        }

        $data = array();
        foreach ($word_rank_result as $item)
        {
            $word = $item["word"];
            $rank = (int)$item["rank"];
            //正则化
            $rank_normalize = $this->normalize_hot_value($rank);

            //获得搜索结果位置信息
            if ( isset($word_pos_dict[$word]) )
            {
                $pos = $word_pos_dict[$word];
            }
            else
            {
                $pos = 3000;//搜索排名默认为3000
            }
            //正则化
            $pos_normalize =  $this->normalize_pos_value($pos);
            //搜索曝光度
            $expose = round( (100*( $rank_normalize * $pos_normalize )), 2);
            $data[] = array("word"=>"$word","rank"=>$rank,"pos"=>$pos,"expose"=>$expose);
        }
        return $data;
    }

    //获得一个app在一个关键词下的搜索曝光度变化趋势
    public function get_app_keyword_trend($app_id, $name, $limit, $start,$end)
    {
        if ($start=="") //如果没有选择日期,最近一个月的数据
        {
            //step 1,获得关键词最近7天的热度数据
            if (10 == (int)$limit) //如果是默认日期,将limit改成7天钱
            {
                $day_num = -7;
            }
            else
            {
                $day_num = -1 * (int)$limit;
            }
            $day_num_str = (string)$day_num . " day";
            $day_threshold = date('Y-m-d', strtotime($day_num_str));//n天前数据
            $sql = "select * from aso_word_rank
                where word='$name' and fetch_date>='$day_threshold'";
            $hot_rank_result = $this->db->query($sql)->result_array();

            //step 2,获得一个app最近一个月在关键词下排名的数据
            $sql = "select * from aso_search_result
            where query='$name' and fetch_date>='$day_threshold' and app_id='$app_id'";
            $search_result = $this->db->query($sql)->result_array();

            //hichart数据构造
            $data = array();
            //构造日期数据,x轴数据
            for ($i=$day_num;$i<1;$i++)
            {
                $day_str = (string)$i . " day";
                $day_pre = date('Y-m-d', strtotime( $day_str ));//n天前
                $data["xAxis"]["categories"][] = $day_pre ;
            }
        }
        else //如果选择了，使用aso_search_result_recommend,可能有数据不一致的地方
        {
            $sql = "select * from aso_word_rank
                where word='$name' and fetch_date>='$start' and fetch_date<='$end'";
            $hot_rank_result = $this->db->query($sql)->result_array();

            //step 2,获得一个app最近一个月在关键词下排名的数据
            $sql = "select * from aso_search_result_recommend
            where query='$name' and  app_id='$app_id' and fetch_date>='$start' and fetch_date<='$end'";
            $search_result = $this->db->query($sql)->result_array();

            //hichart数据构造
            $data = array();
            //构造日期数据,x轴数据
            $day_num = -1*(round( ( strtotime($end)-strtotime($start) )/(3600*24) ));
            for ($i=$day_num;$i<=0;$i++)
            {
                $day_pre = date('Y-m-d', strtotime($end)+$i*24*60*60);//n天前
                $data["xAxis"]["categories"][] = $day_pre ;
            }
        }




        #构造图表数据
        $data["chart"]["type"] = "spline";
        $data["title"]["text"] = "'" . $name . "'--趋势图";
        $data["yAxis"] = array(
            array("title"=>array("text"=>"搜索排名"),"reversed"=>"true","min"=>1),
            array("title"=>array("text"=>"搜索热度/曝光度"), "opposite"=>"false")
        );

        $data["plotOptions"]["series"]["marker"]["radius"] = 2;
        //版权信息
        $data["credits"]["text"] = "APPBK.COM";
        $data["credits"]["href"] = "http://www.appbk.com/";
        $data["xAxis"]["gridLineWidth"] = 1; //纵向网格线宽度



        //构造y轴数据
        #构造不同类别的数据,一级key是日期， 内容是内容是
        //搜索热度数据
        $hot_rank_data = array();
        foreach ($hot_rank_result as $item)
        {
            $hot_rank_data[ $item["fetch_date"] ] = $item["rank"];
        }

        //搜索结果位置数据
        $search_pos_data = array();
        foreach ($search_result as $item)
        {
            $search_pos_data[ $item["fetch_date"] ] = $item["pos"];
        }

        //图表y轴真实数据
        $y_hot_data = array();
        $y_hot_data["name"] = "热度";
        $y_hot_data["yAxis"] = 1;
        $y_hot_data["visible"] = false;//默认不展示

        $y_pos_data = array();
        $y_pos_data["name"] = "搜索排名";
        $y_pos_data["yAxis"] = 0;

        $y_expose_data = array();
        $y_expose_data["name"] = "搜索曝光度";
        $y_expose_data["yAxis"] = 1;
        $y_expose_data["visible"] = false;//默认不展示

        $pre_rank_value = NULL; //前一天的rank值
        $pre_pos_value = NULL;//前一天的pos值

        foreach ( $data["xAxis"]["categories"] as $fetch_date )
        {
            //热度数据
            if ( isset( $hot_rank_data[$fetch_date] ) )
            {
                $hot_rank_value = (int)$hot_rank_data[$fetch_date];
            }
            else
            {
                $hot_rank_value = $pre_rank_value; //如果没有对应的数据，热度假设为1
            }
            $y_hot_data["data"][] = $hot_rank_value;
            $pre_rank_value = $hot_rank_value;

            //排名数据
            if ( isset( $search_pos_data[$fetch_date] ) )
            {
                $search_pos_value = (int)$search_pos_data[$fetch_date];
            }
            else
            {
                $search_pos_value = $pre_pos_value;//搜索排名默认为3000
            }
            $y_pos_data["data"][] = $search_pos_value;
            $pre_pos_value = $search_pos_value;

            if (empty($hot_rank_value)||empty($search_pos_value)) //如果有空数据
            {
                $y_expose_data["data"][] = NULL;
            }
            else
            {
                $y_expose_data["data"][] = round( 100*($this->normalize_hot_value($hot_rank_value)
                        * $this->normalize_pos_value($search_pos_value)), 1 );
            }
        }

        //数据替换
        //目前我们aso_search_result的数据，一天的数据，是凌晨0点到1点左右下载的数据。
        //而aso100，则使用的是23点到0点的数据，造成我们的排名趋势看着比aso100慢一天的假象。
        //故,我们使用目前0点到1点的数据，当做前一天的数据。当天的数据，使用aso_seach_result_new的数据。

        //获得当前的热度数据
        $sql = "select * from aso_word_rank_new
                where word='$name'";
        $cur_hot_rank_result = $this->db->query($sql)->result_array();

        //获得当关键词下排名的数据
        $sql = "select * from aso_search_result_new
            where query='$name' and  app_id='$app_id'";
        $cur_search_result = $this->db->query($sql)->result_array();

        $cur_hot_rank = NULL;
        if ($cur_hot_rank_result)
        {
            $cur_hot_rank = $cur_hot_rank_result[0]["rank"];
        }


        $cur_search_pos = NULL;
        if ($cur_search_result)
        {
            $cur_search_pos = $cur_search_result[0]["pos"];
        }

        if (empty($cur_hot_rank)||empty($cur_search_pos)) //如果有空数据
        {
            $cur_expose = NULL;
        }
        else
        {
            $cur_expose = round( 100*($this->normalize_hot_value($cur_hot_rank)
                    * $this->normalize_pos_value($cur_search_pos)), 1 );
        }

        //替换数据,先删掉第一个,然后末尾添加一个,日期不变,相当于数据提前一天
        array_splice($y_hot_data["data"],0,1);
        $y_hot_data["data"][] = (int)$cur_hot_rank;

        array_splice($y_pos_data["data"],0,1);
        $y_pos_data["data"][] = (int)$cur_search_pos;

        array_splice($y_expose_data["data"],0,1);
        $y_expose_data["data"][] = $cur_expose;


        $data["series"][] = $y_hot_data;
        $data["series"][] = $y_pos_data;
        $data["series"][] = $y_expose_data;


        return $data;
    }

    //获得一个app所有用户填写itunes关键词的总曝光度的趋势图,user_word_type=1
    public function get_app_keywords_trend($email, $app_id)
    {
        //step 1,获得关键词最近一个月的热度数据
        $day_num = -30;
        $day_num_str = (string)$day_num . " day";
        $day_threshold = date('Y-m-d', strtotime($day_num_str));//n天前数据
        $sql = "select * from aso_word_rank
            where word in
            (
               select word from member_word
               where email='$email' and app_id='$app_id' and user_word_type=1
            )
            and fetch_date>'$day_threshold'
            order by fetch_date";
        $hot_rank_result = $this->db->query($sql)->result_array();

        //step 2,获得一个app最近一个月在关键词下排名的数据
        $sql = "select * from aso_search_result
            where query in
            (
               select word from member_word
               where email='$email' and app_id='$app_id' and user_word_type=1
            )
            and fetch_date>'$day_threshold' and app_id='$app_id'
            order by fetch_date";
        $search_result = $this->db->query($sql)->result_array();

        //hichart数据构造
        $data = array();
        //构造日期数据,x轴数据
        for ($i=$day_num;$i<0;$i++)
        {
            $day_str = (string)$i . " day";
            $day_pre = date('Y-m-d', strtotime( $day_str ));//n天前
            $data["xAxis"]["categories"][] = $day_pre ;
        }

        #构造图表数据
        $data["chart"]["type"] = "line";
        $data["title"]["text"] = "app全部关键词曝光度";
        $data["yAxis"]["title"]["text"] = "曝光度";

        //构造y轴数据
        #构造不同类别的数据,一级key是日期，内容是内容是每天的所有的词的数据
        # 二级key是具体的词
        //搜索热度数据
        $hot_rank_data = array();
        foreach ($hot_rank_result as $item)
        {
            $hot_rank_data[ $item["fetch_date"] ][ $item["word"] ] = $item["rank"];
        }

        //搜索结果位置数据
        $search_pos_data = array();
        foreach ($search_result as $item)
        {
            $search_pos_data[ $item["fetch_date"] ][ $item["query"] ] = $item["pos"];
        }

        $y_expose_data = array();
        $y_expose_data["name"] = "搜索曝光度";

        $pre_day_expose_value = NULL; //前一天的曝光度，主要用于补充数据

        foreach ( $data["xAxis"]["categories"] as $fetch_date )
        {
            //计算每一天的值，所有词的曝光度，是每个词的曝光度的和
            if ( isset( $hot_rank_data[$fetch_date] )&&isset( $search_pos_data[$fetch_date] )  ) //如果有当天数据
            {
                $expose_value = 0; //每天所有词的曝光值
                foreach ( $hot_rank_data[$fetch_date] as $word =>$value )
                {
                    //计算这一天每个词的值
                    $hot_rank_value =  $this->normalize_hot_value((int)$value); //热度
                    if ( isset($search_pos_data[$fetch_date][$word]) )  //如果有对应的搜索词，则加上，如果没有，则不做累计
                    {
                        $search_pos_value = $this->normalize_pos_value((int)$search_pos_data[$fetch_date][$word]); //搜索位置得分
                        $expose_value = $expose_value + round( 100*( $hot_rank_value * $search_pos_value ),1 ); //曝光度得分
                    }
                }

                if ($expose_value==0)
                {
                    $expose_value = $pre_day_expose_value; //如果值为0，也用前一个的值
                }
            }
            else //如果没有当天数据，用前一天的
            {
                $expose_value = $pre_day_expose_value; //如果没有对应的数据，热度假设为1
            }
            $pre_day_expose_value = $expose_value;
            $y_expose_data["data"][] = $expose_value;
        }
        $data["series"][] = $y_expose_data;
        return $data;
    }

    //获得app的曝光度趋势图(所有覆盖的关键词,默认30天内，可选择日期)
    public function get_app_expose_trend($app_id,$start,$end)
    {
    //step 1,
    //如果没有选择日期，默认获得关键词最近一个月的热度数据
        if ($start=="")
        {
            $day_num = -10; //默认30天前
            $day_num_str = (string)$day_num . " day";
            $day_threshold = date('Y-m-d', strtotime($day_num_str));//n天前数据
           /*
            $sql = "select * from aso_word_rank
            where word in
            (
               select query as word from aso_search_result_new
               where app_id='$app_id' and pos<100
            )
            and fetch_date>'$day_threshold'";
           */
            $sql = "select word,rank, aso_word_rank.fetch_date from aso_word_rank
                    INNER JOIN aso_search_result_new ON
                    aso_word_rank.word=aso_search_result_new.query
                    WHERE app_id='$app_id' and pos<35
                    and aso_word_rank.fetch_date>='$day_threshold' and rank>4600";


            //echo time()."<br/>";
            $hot_rank_result = $this->db->query($sql)->result_array();
            //echo time()."<br/>";
            //step 2,获得一个app最近一个月在关键词下排名的数据
            $sql = "select * from aso_search_result_recommend
            where app_id='$app_id' and fetch_date>='$day_threshold'";
            $search_result = $this->db->query($sql)->result_array();
            //echo time()."<br/>";
            //echo $sql;

            //hichart数据构造
            $data = array();
            //构造日期数据,x轴数据
            for ($i=$day_num;$i<0;$i++)
            {
                $day_str = (string)$i . " day";
                $day_pre = date('Y-m-d', strtotime( $day_str ));//n天前
                $data["xAxis"]["categories"][] = $day_pre ;
            }
        }
        else //获得两个日期前的数据
        {
            /*
            $sql = "select * from aso_word_rank
            where word in
            (
               select query as word from aso_search_result_new
               where app_id='$app_id' and pos<100
            )
            and fetch_date>='$start' and fetch_date<='$end'";
            */
            $sql = "select aso_word_rank.* from aso_word_rank
                    INNER JOIN aso_search_result_new ON
                    aso_word_rank.word=aso_search_result_new.query
                    WHERE app_id='$app_id' and pos<35
                    and aso_word_rank.fetch_date>='$start' and aso_word_rank.fetch_date<='$end'
                    and rank>4600";
            //echo time()."<br/>";
            $hot_rank_result = $this->db->query($sql)->result_array();

            //step 2,获得一个app最近一个月在关键词下排名的数据
            $sql = "select * from aso_search_result_recommend
            where  app_id='$app_id' and fetch_date>='$start' and fetch_date<='$end'";

            //echo time()."<br/>";
            $search_result = $this->db->query($sql)->result_array();
            //echo time()."<br/>";

            //hichart数据构造
            $data = array();
            //构造日期数据,x轴数据
            $day_num = -1*(round( ( strtotime($end)-strtotime($start) )/(3600*24) ));
            for ($i=$day_num;$i<=0;$i++)
            {
                $day_pre = date('Y-m-d', strtotime($end)+$i*24*60*60);//n天前
                $data["xAxis"]["categories"][] = $day_pre ;
            }

        }


        #构造图表数据
        $data["chart"]["type"] = "spline";
        $data["title"]["text"] = "app全部关键词曝光度";
        $data["title"]["style"] = "fontFamily:'微软雅黑', 'Microsoft YaHei',Arial,Helvetica,sans-serif,'宋体',";
        $data["yAxis"]["title"]["text"] = "曝光度";

        $data["tooltip"]["crosshairs"] = array(array("enabled"=>"true","width"=>1,"color"=>"#d8d8d8"));
        $data["tooltip"]["pointFormat"] = '<span style="color:{series.color}">{series.name}</span>: {point.y} <br/>';
        $data["tooltip"]["shared"] = "true";
        $data["tooltip"]["borderColor"] = "#d8d8d8";

        //$data["backgroundColor"] = "#d8d8d8";
        $data["plotOptions"]["series"]["marker"]["radius"] = 1;
        //版权信息
        $data["credits"]["text"] = "APPBK.COM";
        $data["credits"]["href"] = "http://www.appbk.com/";

        //构造y轴数据
        #构造不同类别的数据,一级key是日期，内容是内容是每天的所有的词的数据
        # 二级key是具体的词
        //搜索热度数据
        $hot_rank_data = array();
        foreach ($hot_rank_result as $item)
        {
            $hot_rank_data[ $item["fetch_date"] ][ $item["word"] ] = $item["rank"];
        }

        //搜索结果位置数据
        $search_pos_data = array();
        foreach ($search_result as $item)
        {
            $search_pos_data[ $item["fetch_date"] ][ $item["query"] ] = $item["pos"];
        }

        $y_expose_data = array();
        $y_expose_data["name"] = "搜索曝光度";

        $pre_day_expose_value = NULL; //前一天的曝光度，主要用于补充数据

        foreach ( $data["xAxis"]["categories"] as $fetch_date )
        {
            //计算每一天的值，所有词的曝光度，是每个词的曝光度的和
            if ( isset( $hot_rank_data[$fetch_date] )&&isset( $search_pos_data[$fetch_date] )  ) //如果有当天数据
            {
                $expose_value = 0; //每天所有词的曝光值
                foreach ( $hot_rank_data[$fetch_date] as $word =>$value )
                {
                    //计算这一天每个词的值
                    $hot_rank_value =  $this->normalize_hot_value((int)$value); //热度
                    if ( isset($search_pos_data[$fetch_date][$word]) )  //如果有对应的搜索词，则加上，如果没有，则不做累计
                    {
                        $search_pos_value = $this->normalize_pos_value((int)$search_pos_data[$fetch_date][$word]); //搜索位置得分
                        $expose_value = $expose_value + round( 100*( $hot_rank_value * $search_pos_value ),1 ); //曝光度得分
                    }
                }

                if ($expose_value==0)
                {
                    $expose_value = $pre_day_expose_value; //如果值为0，也用前一个的值
                }
            }
            else //如果没有当天数据，用前一天的
            {
                $expose_value = $pre_day_expose_value; //如果没有对应的数据，热度假设为1
            }
            $pre_day_expose_value = $expose_value;
            $y_expose_data["data"][] = $expose_value;
        }
        $data["series"][] = $y_expose_data;
        #echo time()."<br/>";
        return $data;
    }

    /*********************************公共函数部分********************************/
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

    //归一化搜索热度值
    private function normalize_hot_value($rank)
    {
        $rank_list = array(0,4600,5000,6000,7000,8000,9000,12000);//热度点
        $top_list =  array(0,0.1,0.15,0.18,0.23,0.4,0.6,1); //值区间


        /*
        if ($value>=10000)
        {
            $normalize_value = 1;
        }
        else if ()
        {
            $value = (float)($value-1678)/2136;
            $normalize_value = 0.4058*pow($value,3)-0.6264*pow($value,2)+0.0942*$value+0.8479;
        }
        */

        //判断$rank左边的index
        $left_index = 0;
        $index = 0;
        foreach ($rank_list as $item)
        {
            if ($rank<$item) //第一次小于一个值
            {
                $left_index = $index-1;
                break;
            }
            $index++;
        }

        //获得估算
        $left_rank = $rank_list[$left_index];
        $right_rank = $rank_list[$left_index+1];

        $left_top = $top_list[$left_index];
        $right_top = $top_list[$left_index+1];

        $top = $left_top + ( ($rank-$left_rank)/($right_rank-$left_rank) ) * ($right_top-$left_top);

        return $top;
    }

    //归一化搜索结果位置值
    private function normalize_pos_value($value)
    {
        if ($value>200)
        {
            $normalize_value = 0;
        }
        else
        {
            $value = (float)($value-55)/72.86;
            $normalize_value = -0.1266*pow($value,3)+0.3284*pow($value,2)-0.3764*$value+0.4454;
        }
        return $normalize_value;
    }

    //获得一组搜索词的优化容易程度指标，暂时用top6到35的搜索结果app的全部评论数判断
    public function get_words_optimal_prob($words)
    {
        //取评论小于500的比例
        //具体情况：如果全部全部搜索结果都大于500，则没有没有对应的词，则优化容易度设为0，但是如果搜索结果只有5个，且评论均大于500，则其实是容易的
        //因此，需要搜索结果数，具体来处理

        if (count($words) == 0 )
        {
            return NULL;
        }
        $word_list = array();
        foreach ($words as $word)
        {
            //删除关键词中可能的单引号和双引号,以及反斜杠
            $word = str_replace(array("'",'"','\\'),"",$word);
            $word_list[] =  "'". $word . "'";
        }
        $word_list_sql = join(",",$word_list);
        //获得所有词的命中的所有app
        //sql，先找到所有词命中的appid，然后与app_info join即可
        /*
        $sql = "select query,count(*)/35 as value from app_info right join
               (select app_id,query from aso_search_result_new
               where query in ($word_list_sql) and pos<36) as app_id_list
               on app_info.app_id=app_id_list.app_id
               where user_comment_num<500
               group by query
               ";
        */
        $sql = "select query,count(*)/35 as value from app_info right join
               (select app_id,query from aso_search_result_new_recommend
               where query in ($word_list_sql) and pos<36) as app_id_list
               on app_info.app_id=app_id_list.app_id
               where user_comment_num<500
               group by query
               ";
        $result = $this->db->query($sql)->result_array();

        //查看搜索结果数
        $sql = "select * from aso_result_num where  query in ($word_list_sql)";
        $result_num = $this->db->query($sql)->result_array();
        $query_result_num = array();
        foreach ($result_num as $item)
        {
            $query_result_num[ $item["query"] ] = (float)$item["num"];
        }

        //做成dict
        $data = array();
        foreach ($result as $item)
        {
            $data[ $item["query"] ] = (float)$item["value"];
        }
        //处理没有结果的，赋值为0，如"游戏"这个词，全部结果评论数均大于500，sql未命中
        foreach ($words as $item)
        {
            if (!isset($data[$item])) // 如果没有这个词
            {
                if (isset($query_result_num[$item])) //如果有搜索结果数
                {
                    if ( $query_result_num[$item] >= 35) //如果query的命中app评论均大于500，并且搜索结果数大于35，优化容易度为0
                    {
                        $data[$item] = 0;
                    }
                    else //如果query的命中app评论均大于500，并且搜索结果数小于35，优化容易度，为搜索结果数/35
                    {
                        $data[$item] =  (35-$query_result_num[$item])/35;
                    }
                }
                else
                {
                    $data[$item] = 0;
                }
            }
            else //如果有这个词的结果
            {
                if (isset($query_result_num[$item])) //如果结果数较少，需要单独处理
                {
                    if ( $query_result_num[$item] <= 35)
                    {
                        $data[$item] =( 35-($query_result_num[$item] - $data[$item]) )/35;
                    }
                }
            }
        }
        //var_dump($data);
        return $data;
    }

    //获得app所有可能的关键词，通过搜索结果倒推
    public function get_app_possible_keywords($app_id, $date,$start=0, $limit=30)
    {
        //输入参数错误判断
        if ((int)$start>50000 || (int)$limit>30000 ) //如果开始位置过大，或者取的结果过多，返回错误信息
        {
            return array("status"=>-1,"message"=>"start exceed 50000 or limit exceed 3000");
        }
        if ($date=="")  //如果没有设置日期
        {
           /*
            $sql = "select * from
                (
                    select query,pos,rank from aso_search_result_new
                    left join aso_word_rank_new
                    on aso_search_result_new.query=aso_word_rank_new.word
                    where app_id='$app_id'
                ) as word_list
                left join aso_result_num
                on aso_result_num.query=word_list.query
                where rank>100
                order by pos limit $start, $limit";
           */
            $sql = "select word_list.query,pos,rank,num,app_id,
                    word_list.fetch_date,name,ori_classes,ori_classes1,ori_classes2,ori_classes3
                    from
                        (
                            select query,pos,rank,aso_search_result_new.fetch_date,
                            aso_search_result_new.fetch_time
                            from aso_search_result_new
                            left join aso_word_rank_new
                            on aso_search_result_new.query=aso_word_rank_new.word
                            where app_id='$app_id' and rank>0
                            group by query
                            order by pos limit $start,$limit
                        ) as word_list
                        left join aso_result_num
                        on aso_result_num.query=word_list.query";
        }
        else//设置了日期,30天内，使用aso_search_result,30天之外，使用aso_search_result_recommend
        {
            /*
            $sql = "select * from
                (
                    select query,pos,rank from aso_search_result
                    left join aso_word_rank
                    on aso_search_result.query=aso_word_rank.word
                    where app_id='$app_id' and aso_search_result.fetch_date='$date'
                    and  aso_word_rank.fetch_date='$date'
                ) as word_list
                left join aso_result_num
                on aso_result_num.query=word_list.query
                where rank>100
                order by pos limit $start, $limit";
            */
            $cur_time = time();//当前时间
            $one_month_ago = time() - 30*24*60*60;//一个月之前的时间
            $select_date = strtotime($date);//选择的日期

            if ($select_date>$one_month_ago) //如果是前一个月之后的日期，选择aso_search_result
            {
                /*$sql = "select * from
                (
                    select query,pos,rank from aso_search_result
                    left join aso_word_rank
                    on aso_search_result.query=aso_word_rank.word
                    where app_id='$app_id' and aso_search_result.fetch_date='$date'
                    and  aso_word_rank.fetch_date='$date' and rank>100
                ) as word_list
                left join aso_result_num
                on aso_result_num.query=word_list.query
                order by pos limit $start, $limit";*/
                //$data_db = "aso_search_result_" . date("Y_m_d",strtotime($date));
                /*
                $sql = "select * from
                (
                    select query,pos,rank from $data_db
                    left join aso_word_rank
                    on $data_db.query=aso_word_rank.word
                    where app_id='$app_id'
                    and  aso_word_rank.fetch_date='$date' and rank>100
                ) as word_list
                left join aso_result_num
                on aso_result_num.query=word_list.query
                order by pos limit $start, $limit";
                */

                /*
                $sql = "select * from
                (
                  select query,pos,rank from
                    (
                      select query,pos from $data_db
                      where app_id='$app_id' order by pos limit $start, $limit
                    ) as search_result
                    left join aso_word_rank
                    on search_result.query=aso_word_rank.word
                    where aso_word_rank.fetch_date='$date' and rank>100
                ) as word_list
                left join aso_result_num_trend
                on aso_result_num_trend.query=word_list.query
                where aso_result_num_trend.fetch_date='$date'";
                */

                $sql = "select * from
                (
                  select query,pos,rank from
                    (
                      select query,pos from aso_search_result
                      where app_id='$app_id' and fetch_date='$date' order by pos limit $start, $limit
                    ) as search_result
                    left join aso_word_rank
                    on search_result.query=aso_word_rank.word
                    where aso_word_rank.fetch_date='$date' and rank>100
                ) as word_list
                 left join aso_result_num
                on aso_result_num.query=word_list.query";
            }
            else //如果是30天前，选择aso_search_result_recommend
            {
                /*
                $sql = "select * from
                (
                    select query,pos,rank from aso_search_result_recommend
                    left join aso_word_rank
                    on aso_search_result_recommend.query=aso_word_rank.word
                    where app_id='$app_id' and aso_search_result_recommend.fetch_date='$date'
                    and  aso_word_rank.fetch_date='$date' and rank>100
                    order by pos limit $start, $limit
                ) as word_list
                left join aso_result_num
                on aso_result_num.query=word_list.query";
                */
                $sql = "select * from
                (
                  select query,pos,rank from
                    (
                      select query,pos from aso_search_result_recommend
                      where app_id='$app_id' and fetch_date='$date' order by pos limit $start, $limit
                    ) as search_result
                    left join aso_word_rank
                    on search_result.query=aso_word_rank.word
                    where aso_word_rank.fetch_date='$date' and rank>100
                ) as word_list
                left join aso_result_num
                on aso_result_num.query=word_list.query";
            }
        }
        //echo time()."|";
        $result = $this->db->query($sql)->result_array();

        //获得优化容易度
        $words = array();
        foreach ($result as $item)
        {
            $words[] = $item["query"];
        }
        $words_optimal_prob = $this->get_words_optimal_prob($words);

        //获得每个词的曝光度
        $i = 0;
        $expose_all = 0;
        foreach ($result as $item)
        {
            //热度正则化
            $rank_normalize = $this->normalize_hot_value($item["rank"]);
            //搜索位置正则化
            $pos_normalize =  $this->normalize_pos_value($item["pos"]);
            //搜索曝光度
            $expose = round( (100*( $rank_normalize * $pos_normalize )), 2);
            $result[$i]["expose"] = $expose;

            //获得词的竞争度
            $word_optimal_prob = $words_optimal_prob[$item["query"]];
            $result[$i]["compete_index"] = round(100*(1-$word_optimal_prob), 1);
            $expose_all = $expose_all + $expose;
            $i++;
        }
        //echo time()."|";
        $num = $this->get_app_possible_keywords_num($app_id, $date);
        //echo time()."|";
        return array("status"=>0,"msg"=>"success","num"=>$num,"results"=>$result,"expose_all"=>$expose_all);
    }

    public function get_app_possible_keywords_num($app_id,$date)
    {
        if ($date=="")  //如果没有设置日期
        {
            $sql = "select count(*) as result_num from aso_search_result_new where app_id='$app_id'";
        }
        else {
            $cur_time = time();//当前时间
            $one_month_ago = time() - 30 * 24 * 60 * 60;//一个月之前的时间
            $select_date = strtotime($date);//选择的日期
            if ($select_date > $one_month_ago) //如果大于一个月之前的日期，选择aso_search_result
            {
                //$data_db = "aso_search_result_" . date("Y_m_d",strtotime($date));
                //$sql = "select count(*) as result_num from $data_db where app_id='$app_id'";
                $sql = "select count(*) as result_num from aso_search_result where app_id='$app_id' and fetch_date='$date'";
            }
            else
            {
                $sql = "select count(*) as result_num from aso_search_result_recommend where app_id='$app_id' and fetch_date='$date'";

            }
        }
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    /*********************************已经不再使用的函数*******************************/
    //根据用户和appid，获取用户填写的itunes的关键词，没有热度等信息
    public function get_user_app_keyword_list($email, $app_id)
    {
        $sql = "select word from member_word
            where email='$email' and app_id='$app_id' and user_word_type=1";
        $result = $this->db2->query($sql)->result_array();
        return $result;
    }

    /*
//获得为用户推荐的关键词,系统全自动推荐
//根据用户填写的竞品app进行关键词推荐
public function get_user_app_recommend_keywords($email, $app_id, $app_name, $start=0, $limit=10)
{
            $sql = "select feature_list.word,rank,num,name from
            (select word,rank,num,name from aso_word_rank_new left join aso_result_num
            on aso_word_rank_new.word=aso_result_num.query
            ) as feature_list right join

(select word_list.tag as word from
(
                 select tag,sum(score) as final_score
                 from aso_app_tag right join
                 (
                    select name from member_app_compete
                    left join app_info
                    on member_app_compete.compete_app_id=app_info.app_id
                    where member_app_compete.app_id='$app_id' and email='$email'
                    and from_plat='appstore'
                ) as app_list
                on aso_app_tag.name=app_list.name
                where source=2 group by tag order by final_score desc limit 30
) as word_list
) as tag_list on feature_list.word=tag_list.word
           where feature_list.word not in
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
   return $result;
}
*/
    //获得关键词扩展
    public function get_app_keyword_expand_keywords($name)
    {
        $sql = "select query,rank,num,name
                from aso_word_rank_new right join aso_result_num
                on aso_word_rank_new.word=aso_result_num.query
                where query in
                 (
                    select query from aso_query_expansion where term='$name'
                 )
                order by rank desc limit 100";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //系统全自动推荐，自动寻找关键词，然后找app,再找关键词，暂时未使用
    public function get_user_app_sys_recommend_keywords($email, $app_id, $app_name, $start=0, $limit=10)
    {
        //具体方法
        //sql语句含义为，先获得关键词推荐，然后获得关键词的搜索结果数和热度特征
        //同时需要去掉用户已经填写的关键词
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
        return $result;
    }

}
?>
