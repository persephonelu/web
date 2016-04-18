<?php
#app关键词管理模块
class Word_test_provider extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

     #获得关键词排行榜
    public function get_word_rank($category, $start=0, $limit=10)
    {
        $sql = "select word,rank,num,name,aso_result_num.app_id
            from aso_word_rank_new left join aso_result_num 
            on aso_word_rank_new.word=aso_result_num.query
            where aso_result_num.ori_classes='$category'
            or aso_result_num.ori_classes1='$category'
            or aso_result_num.ori_classes2='$category'
            or aso_result_num.ori_classes3='$category'
            order by rank desc limit $start,$limit";

        if ( $category=="应用" )
        {
            $sql = "select word,rank,num,name
            from aso_word_rank_new left join aso_result_num 
            on aso_word_rank_new.word=aso_result_num.query
            order by rank desc limit $start,$limit";
        }
        $result = $this->db->query($sql)->result_array();
        $num = $this->get_word_rank_num($category);
        return array("num"=>$num, "results" =>$result);
    }

    #获得关键词排行榜记录个数
    public function get_word_rank_num($category)
    {
        $sql = "select count(*) as result_num
            from aso_word_rank_new left join aso_result_num
            on aso_word_rank_new.word=aso_result_num.query
            where aso_result_num.ori_classes='$category'
            or aso_result_num.ori_classes1='$category'
            or aso_result_num.ori_classes2='$category'
            or aso_result_num.ori_classes3='$category'";

        if ( $category=="应用" )
        {
            $sql = "select count(*) as result_num
            from aso_word_rank_new left join aso_result_num
            on aso_word_rank_new.word=aso_result_num.query";
        }
        //$result = $this->db->query($sql)->result_array();
        //return $result[0]['result_num'];
        return 1000;
    }

    #获得搜索建议词列表,appstore搜索提示接口
    #keyword，关键词，$cc,国家
    public function get_suggestion($keyword, $cc)
    {
        //中国区，cc=cn，美国区cc=us
        if (""==$cc) //如果没有选择国家，默认为cn
        {
            $cc = "cn";
        }

        $keyword = urlencode($keyword);
        $url = "http://search.itunes.apple.com/WebObjects/MZSearchHints.woa/wa/hints?media=software&cc=".$cc."&q=".$keyword;
        $content = file_get_contents($url);
        $xml = simplexml_load_string($content); //创建 SimpleXML对象 
        //var_dump($xml->dict->array);
        $result = array();
        foreach ($xml->dict->array->dict as $word)
        {
            $suggestion = array();
            $suggestion["word"] = (string)$word->string[0];
            $suggestion["rank"] = (string)$word->integer;
            $result[] = $suggestion; //append
        }
        return $result;
    }

    //获得某个关键词的热度
    public function get_word_hot_rank($n)
    {
        $sql = "SELECT * FROM `aso_word_rank_new` WHERE word='$n'";
        $result = $this->db->query($sql)->result_array();
        return $result?$result[0]:array("word"=>$n,"rank"=>"0");
    }

    //获得某个关键词的热度趋势
    public function get_word_rank_trend($n)
    {
        //step 1,获得关键词最近一个月的热度数据
        $day_num = -30;
        $day_num_str = (string)$day_num . " day";
        $day_threshold = date('Y-m-d', strtotime($day_num_str));//n天前数据
        $sql = "select * from aso_word_rank
            where word='$n' and fetch_date>'$day_threshold'
            order by fetch_date";
        $hot_rank_result = $this->db->query($sql)->result_array();

        //hichart数据构造
        $data = array();
        //构造日期数据,x轴数据
        for ($i=$day_num;$i<1;$i++)
        {
            $day_str = (string)$i . " day";
            $day_pre = date('Y-m-d', strtotime( $day_str ));//n天前
            $data["xAxis"]["categories"][] = $day_pre ;
        }

        #构造图表数据
        $data["chart"]["type"] = "spline";
        $data["tooltip"]["crosshairs"] = array(array("enabled"=>"true","width"=>1,"color"=>"#d8d8d8"));
        $data["tooltip"]["pointFormat"] = '<span style="color:{series.color}">{series.name}</span>: {point.y} <br/>';
        $data["tooltip"]["shared"] = "true";
        $data["tooltip"]["borderColor"] = "#d8d8d8";
        $data["plotOptions"]["series"]["marker"]["radius"] = 1;


        $data["title"]["text"] = "'" . $n . "'--热度趋势图(30天)";
        $data["title"]["style"] = "fontFamily:'微软雅黑', 'Microsoft YaHei',Arial,Helvetica,sans-serif,'宋体',";
        $data["yAxis"] = array(
            array("title"=>array("text"=>"搜索热度")),
        );

        //版权信息
        $data["credits"]["text"] = "APPBK";
        $data["credits"]["href"] = "http://www.appbk.com/";
        $data["credits"]["position"]["align"] = "right";
        $data["credits"]["position"]["x"] = -10;
        $data["credits"]["position"]["verticalAlign"] = "bottom";
        $data["credits"]["position"]["y"] = -5;

        //构造y轴数据
        #构造不同类别的数据,一级key是日期， 内容是内容是
        //搜索热度数据
        $hot_rank_data = array();
        foreach ($hot_rank_result as $item)
        {
            $hot_rank_data[ $item["fetch_date"] ] = $item["rank"];
        }

        //图表y轴真实数据
        $y_hot_data = array();
        $y_hot_data["name"] = "热度";
        $y_hot_data["yAxis"] = 0;

        $pre_rank_value = NULL; //前一天的rank值
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
        }
        $data["series"][] = $y_hot_data;
        return $data;
    }

    //两个app关键词的对比
    public function get_app_words_compare($app_id, $compete_app_id)
    {
        $sql = "select * from aso_word_rank_new right join
                (
                  select * from
                  (SELECT query as my_app_query,pos as my_app_pos FROM
                  `aso_search_result_new` WHERE `app_id`='$app_id')
                  as my_app
                  inner join
                   (SELECT query,pos FROM `aso_search_result_new`
                   WHERE `app_id`='$compete_app_id')
                  as compete_app
                  on my_app.my_app_query=compete_app.query
                ) as compare_list
                on aso_word_rank_new.word=compare_list.query
                where rank>4000
                order by my_app_pos";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //获得一个app两个日期的关键词比较
    public function get_app_word_two_date_compare($app_id, $date,$start,$limit)
    {
        $all_starttimer = microtime(true);

        $starttimer = microtime(true);
        //step 0， 获得top3和top10的关键词个数
        $sql = "select SQL_NO_CACHE count(*) as num from aso_search_result_new
            where app_id='$app_id' and pos<=3";
        $result = $this->db->query($sql)->result_array();
        $top3_num = $result[0]["num"];

        $stoptimer = microtime(true);
        $timer = $stoptimer-$starttimer;
        echo " 获得top3结果数: in $timer seconds.\n";


        $starttimer = microtime(true);

        $sql = "select SQL_NO_CACHE count(*) as num from aso_search_result_new
            where app_id='$app_id' and pos<=10";
        $result = $this->db->query($sql)->result_array();
        $top10_num = $result[0]["num"];

        $stoptimer = microtime(true);
        $timer = $stoptimer-$starttimer;
        echo " 获得top10结果数: in $timer seconds.\n";



        //step 1，获得App当前最新的关键词

        $starttimer = microtime(true);

        $cur_day_result = $this->get_app_possible_keywords($app_id, "",$start, $limit);

        $stoptimer = microtime(true);
        $timer = $stoptimer-$starttimer;
        echo " 获得最新关键词: in $timer seconds.\n";



        //step 2,获得基准比较数据
        if ($date=="") //如果没有填写日期，则放前一天的数据
        {
            $pre_day = date("Y-m-d",time()-1*24*60*60);//默认昨天的数据
        }
        else
        {
            $pre_day = $date;
        }

        $starttimer = microtime(true);

        $sql = "select SQL_NO_CACHE query,pos from aso_search_result
            where app_id='$app_id' and fetch_date='$pre_day'";
        $search_result = $this->db->query($sql)->result_array();

        $stoptimer = microtime(true);
        $timer = $stoptimer-$starttimer;
        echo " 获得前一天关键词: in $timer seconds.\n";



        //转化成dict的形式，key是关键词，value是位置
        $search_result_dict = array();
        foreach ($search_result as $app)
        {
            $search_result_dict[$app["query"]] = $app["pos"];
        }

        //step 3,进行pos的比较
        $index = 0;
        foreach ($cur_day_result["results"] as $app)
        {
            $query = $app["query"];
            if ( array_key_exists($query,$search_result_dict) ) //如果包含这个词
            {
                $cur_day_result["results"][$index]["increase"] =  (int)$search_result_dict[$query] - (int)$app["pos"];
                if ( $cur_day_result["results"][$index]["increase"]>0 )
                {
                    $cur_day_result["results"][$index]["increase_sign"] = "↑"; //排名提升
                }
                elseif ($cur_day_result["results"][$index]["increase"]<0)
                {
                    $cur_day_result["results"][$index]["increase_sign"] = "↓"; //排名下载
                }
                else
                {
                    $cur_day_result["results"][$index]["increase_sign"] = "-"; //排名不变
                }
            }
            else
            {
                $cur_day_result["results"][$index]["increase"] = 0;
                $cur_day_result["results"][$index]["increase_sign"] = "N";
            }
            $index++;
        }

        $cur_day_result["top3_num"] = $top3_num;//top3的结果个数
        $cur_day_result["top10_num"] = $top10_num;//top10的结果个数
        //return $cur_day_result;

        $all_stoptimer = microtime(true);
        $timer = $all_stoptimer-$all_starttimer;
        echo "全部流程耗时: in $timer seconds.\n";
        return $cur_day_result;

        //return array();

    }

    //获得关键词的热度，搜索结果第一名的基础信息
    public function get_word_info($n)
    {
        //分割字符串
        $delimiters = array(",","，","，"," ",'、','\n');
        $app_id_list = $this->multipleExplode($delimiters, $n);
        $value_list = array();
        foreach ($app_id_list as $app_id)
        {
            $value_list[] = "'$app_id'";
        }
        $value_list_join = join(",",$value_list);

        $sql = "select * from
                (
                    select query,pos,rank from aso_search_result_new
                    left join aso_word_rank_new
                    on aso_search_result_new.query=aso_word_rank_new.word
                    where query in ($value_list_join) and pos=1
                ) as word_list
                left join aso_result_num
                on aso_result_num.query=word_list.query";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    /*********************辅助函数********************/
    //获得app所有可能的关键词，通过搜索结果倒推
    public function get_app_possible_keywords($app_id, $date,$start=0, $limit=30)
    {
        if ($date=="")  //如果没有设置日期，使用最新数据
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
            $sql = "select SQL_NO_CACHE word_list.query as query,pos,rank,fetch_time,num,app_id,name from
                (
                    select query,pos,rank,fetch_time from aso_search_result_new
                    left join aso_word_rank_new
                    on aso_search_result_new.query=aso_word_rank_new.word
                    where app_id='$app_id' and rank>100
                    group by query
                    order by pos limit $start, $limit
                ) as word_list
                left join aso_result_num
                on aso_result_num.query=word_list.query
               ";
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

            if ($select_date>$one_month_ago) //如果大于一个月之前的日期，选择aso_search_result
            {
                $sql = "select word_list.query as query,pos,rank,fetch_time,num,app_id,name from
                (
                    select query,pos,rank from aso_search_result
                    left join aso_word_rank
                    on aso_search_result.query=aso_word_rank.word
                    where app_id='$app_id' and aso_search_result.fetch_date='$date'
                    and  aso_word_rank.fetch_date='$date' and rank>100
                    order by pos limit $start, $limit
                ) as word_list
                left join aso_result_num
                on aso_result_num.query=word_list.query";
            }
            else //如果是30天前，选择aso_search_result_recommend
            {
                $sql = "select word_list.query as query,pos,rank,fetch_time,num,app_id,name from
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
            }
        }
        $result = $this->db->query($sql)->result_array();
        $num = $this->get_app_possible_keywords_num($app_id, $date);
        //$num = 0;
        return array("num"=>$num,"results"=>$result);
    }

    public function get_app_possible_keywords_num($app_id,$date)
    {
        if ($date=="")  //如果没有设置日期
        {
            $sql = "select SQL_NO_CACHE count(*) as result_num from aso_search_result_new where app_id='$app_id'";
        }
        else {
            $cur_time = time();//当前时间
            $one_month_ago = time() - 30 * 24 * 60 * 60;//一个月之前的时间
            $select_date = strtotime($date);//选择的日期
            if ($select_date > $one_month_ago) //如果大于一个月之前的日期，选择aso_search_result
            {
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
