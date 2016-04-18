<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2015/8/26
 * Time: 11:03
 */
require_once "resource/phpanalysis/phpanalysis.class.php";
class app_tools_provider extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库

    }

    public function get_word_segments($n)
    {
        $pa=new PhpAnalysis();
        $pa->SetSource($n);
        $pa->resultType=2; //生成的分词结果数据类型(1 为全部， 2为 词典词汇及单个中日韩简繁字符及英文， 3 为词典词汇及英文)
        $pa->differMax=true; //使用最大切分模式对二元词进行消岐
        $pa->StartAnalysis();
        $seg=$pa->GetFinallyResult(",");
        return array("segments"=>$seg);
    }

    //获得最近一周的appstore搜索热词
    public function get_appstore_hotwords()
    {
        $day_num = -7;
        $day_num_str = (string)$day_num . " day";
        $pre_week_day = date('Y-m-d', strtotime( $day_num_str ));//n天前
        /*
        $sql = "select group_concat(DISTINCT(word) SEPARATOR  ' , ' ) as words,fetch_date from aso_appstore_hot_word where fetch_date>'$pre_week_day'
                group by fetch_date order by fetch_date desc ";
        */
        $sql = "select group_concat(DISTINCT(word) ORDER  by pos SEPARATOR  ' , ' ) as words,fetch_time as fetch_date,
                fetch_date as date
                from aso_appstore_hot_word where fetch_date>'$pre_week_day'
                group by fetch_time order by fetch_time";
        $result = $this->db->query($sql)->result_array();

        //去掉连续重复的记录
        $i = 0;
        $final_result = array();
        $pre_result = array();
        foreach ( $result as $item )
        {
            if ( 0==$i ) //初次
            {
                $item["first_day"] = 1;  //是否是当天的第一次出现
                $final_result[] = $item;
            }
            else
            {
                if ($item["words"]!=$pre_result["words"]) //如果和上一个记录不重复
                {
                    if ($item["date"]!=$pre_result["date"])
                    {
                        $item["first_day"] = 1;
                    }
                    else
                    {
                        $item["first_day"] = 0;
                    }
                    $final_result[] = $item;
                }
            }
            $pre_result = $item;
            $i++;
        }

        $result = array_reverse($final_result);
        $max_interval = round(  (time() - strtotime($result[0]["fetch_date"]))/(60*60) , 1); //距离现在多少小时没有更新
        if ($max_interval > 10)
        {
            $message = "热搜榜已经有" .$max_interval ."小时未更新,疑似锁榜";
        }
        else
        {
            $message = "热搜榜未发现异常";
        }
        return $result;
    }

    //获得最新的appstore搜索热词
    public function get_appstore_hotwords_new($limit=10)
    {
        $sql = "select word,pos,fetch_time from aso_appstore_hot_word
                where fetch_time=
                (select max(fetch_time) as fetch_time
                from aso_appstore_hot_word)
                order by pos limit 0,$limit";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //aso检测
    public function get_aso_check($n, $words)
    {
        $all_word_list = array(); //所有词列表
        //step 1 ，解析关键词词组
        $delimiters = array(",","，","，"," ","、");
        $word_list = $this->multipleExplode($delimiters, $words);
        $all_word_list = array_merge($all_word_list, $word_list);

        //step 2 , 对于较长的词(大于等于4个汉字的(12)),进行分词,获得可能的组合
        $keyword_sub_list = array(); //关键词的子字符串
        foreach ($word_list as $word)
        {
            if (strlen($word)>=12)
            {
                $keyword_sub = $this->build_keyword_sub($word); //构建子字符串
                $keyword_sub_list = array_merge($keyword_sub_list, $keyword_sub);//数组扩展
            }
        }
        $all_word_list = array_merge($all_word_list, $keyword_sub_list);

        //step 3，对标题进行分词处理
        $title_segments = $this->get_word_segments($n);
        $title_segments_list = $this->multipleExplode($delimiters, $title_segments["segments"]);
        $all_word_list = array_merge($all_word_list, $title_segments_list);


        //step 4, 构造mysql in字符串,在mysql处理阶段会自动去重复,这里不需要去重复
        $word_in_list = array();//构造mysql in
        foreach ($all_word_list as $word)
        {
            if ( $word=="" || $word==" ")
            {
                continue;
            }
            $word_in_list[] = "'" . $word .  "'";
        }

        $word_in = join(",",$word_in_list);

        $sql = " select query as word,rank,num,name
                from aso_word_rank_new right join aso_result_num
                on aso_word_rank_new.word=aso_result_num.query
                where query in
                 (
                    $word_in
                 )";

        $result = $this->db->query($sql)->result_array();

        //获得优化容易度
        $keywords = array();
        foreach ($result as $item)
        {
            $keywords[] = $item["word"];
        }
        $words_optimal_prob = $this->get_words_optimal_prob($keywords);

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

        //获得aso建议
        $suggestion = $this->get_aso_suggestion($n, $words);
        return array("suggestion"=>$suggestion,"results"=>$result);
    }

    //获得关键词的拓词
    public function get_word_expand($n)
    {
        //step1 ，解析词组
        $delimiters = array(",","，","，","、");
        $word_list = $this->multipleExplode($delimiters, $n);
        $word_in_list = array();//构造mysql in
        foreach ($word_list as $word)
        {
            if ( $word=="" || $word==" ")
            {
                continue;
            }
            $word_in_list[] = "'" . $word .  "'";
        }

        $word_in = join(",",$word_in_list);

        //step 2,获得推荐词
        $sql = "select  word,rank,num,name,app_id,score
                from aso_word_rank_new inner join
                (
                    select aso_result_num.query, num,name,app_id,score from aso_result_num right join
                    (
                        select query, sum(1/pos) as score from aso_search_result_new_recommend right join
                            (
                                select aso_search_result_new_recommend.app_id
                                from aso_search_result_new_recommend
                                where query in ($word_in) and pos<11
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

        /*
        //echo "step 2 ". date("h:i:s"). "<br/>";
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
        */
        return $result;
    }

    //获得一组搜索词的优化容易成都指标，暂时用top6到35的搜索结果app的全部评论
    //取评论小于500的比例
    //具体情况：如果全部全部搜索结果都大于500，则没有没有对应的词，则优化容易度设为0，但是如果搜索结果只有5个，且评论均大于500，则其实是容易的
    //因此，需要搜索结果数，具体来处理
    public function get_words_optimal_prob($words)
    {
        if (count($words) == 0 )
        {
            return NULL;
        }
        $word_list = array();
        foreach ($words as $word)
        {
            //删除关键词中可能的单引号和双引号
            $word = str_replace(array("'",'"','\\'),"",$word);
            $word_list[] =  "'". $word . "'";
        }
        $word_list_sql = join(",",$word_list);
        //获得所有词的命中的所有app
        //sql，先找到所有词命中的appid，然后与app_info join即可
        $sql = "select query,count(*)/35 as value from app_info right join
               (select app_id,query from aso_search_result_new
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

    //归一化搜索热度值
    private function normalize_hot_value($value)
    {
        if ($value>=5000)
        {
            $normalize_value = 1;
        }
        else
        {
            $value = (float)($value-1678)/2136;
            $normalize_value = 0.4058*pow($value,3)-0.6264*pow($value,2)+0.0942*$value+0.8479;
        }
        return $normalize_value;
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

    //分词,并找到左右的组合形式,主要处理组词的词
    public function build_keyword_sub($word)
    {
        $delimiters = array(",","，","，"," ","、");
        $word_segments = $this->get_word_segments($word);
        $word_segments_list = $this->multipleExplode($delimiters, $word_segments["segments"]);

        //找出所有的组合
        $word_list = array();
        foreach($word_segments_list as $item_left)
        {
            foreach($word_segments_list as $item_right)
            {
                $word_list[] = $item_left . $item_right; //目前只处理二元词
            }
        }
        return  $word_list;
    }

    //获得aso建议
    public function get_aso_suggestion($n, $words)
    {
        $suggestion = array();
        //step 1,标题检测,标题长度如果小于等于15个字节(5个汉字),提示增加附标题
        if (strlen($n)<15)
        {
            $suggestion[] =  "应用名称稍短，建议以附标题等形式,增加更多的词";
        }

        //step 2, 检测标题和关键词中重复的词
        $word_suggestion = "";
        $delimiters = array(",","，","，"," ","、");
        $word_segments = $this->get_word_segments($n.$words);
        $word_segments_list = $this->multipleExplode($delimiters, $word_segments["segments"]);
        //统计重复的词
        $word_count = array_count_values($word_segments_list);
        foreach ($word_count as $key=>$value)
        {
            if ($value>=2)
            {
                $word_suggestion = $word_suggestion . "「".$key ."」出现" . $value ."次,";
            }
        }

        //如果包含了重复出现的词,加上提示语
        if (""!=$word_suggestion)
        {
            $word_suggestion = "应用名称和关键词中" . $word_suggestion . "建议可通过组词等方法减少重复出现的词";
            $suggestion[] =  $word_suggestion;
        }

        return $suggestion;
    }

    //榜单更新周期监控
    //$day,日期,如果不选,则默认为当前日期,时间选择从$day开始之前24小时.
    public function get_app_rank_update($day="2016-04-08")
    {
        if ("" == $day) //如果没选择日期,默认选择当前时间
        {
            $cur_time =time();
        }
        else
        {
            $cur_time = strtotime($day . "00:00:00");
        }
        $start_time = date("Y-m-d H:i:s", $cur_time - 24*60*60); //24小时前
        $end_time = date("Y-m-d H:i:s", $cur_time + 24*60*60); //24小时后

        //step 1,构造真实更新时间点
        $sql = "select md5( group_concat(app_id ORDER  by rank SEPARATOR  ' , ' ) ) as ids,
                fetch_time from `watch_app_rank` where fetch_time>='$start_time' and
                fetch_time<='$end_time' group by fetch_time order by fetch_time";

        //echo $sql;
        $result = $this->db->query($sql)->result_array();

        //统计发生变化的时间点
        $pre_ids = "";//前一个周期的id列表
        $change_point_list = array();
        foreach ( $result as $item )
        {
            $point = array();
            $point["fetch_time"] = $item["fetch_time"];

            if ( $item["ids"]!=$pre_ids ) //如果和上一个记录不重复
            {
                $point["change"] = 1;
                $change_point_list[] = $point;
            }
            else
            {
                $point["change"] = null; //暂时不加入
            }

            //$change_point_list[] = $point;
            $pre_ids = $item["ids"];
        }

        //var_dump($change_point_list);

        //step 2,构造预测的时间点
        //start时间和基准时间的差,除以2.5小时,得到的整数
        $base_time = strtotime("2016-04-01 00:00:30"); //基准时间
        $period = 2.5*60*60;//周期
        $time_dif = ($cur_time - 24*60*60) - $base_time;//start_time - base_time
        $period_num = (int) ($time_dif/$period) ;//开始时间距离基准时间经过的多少个周期.周期为2.5小时

        $start_time_predict = $base_time + $period_num * $period;

        //做图表
        //hichart数据构造
        $data = array();







        $data["tooltip"]["crosshairs"] = array(array("enabled"=>"true","width"=>1,"color"=>"#d8d8d8"));
        $data["tooltip"]["headerFormat"] = '<span style="color:{series.color}">{point.key}</span> <br/>';
        $data["tooltip"]["pointFormat"] = '<span style="color:{series.color}">{series.name}</span> <br/>';
        $data["tooltip"]["shared"] = "true";
        $data["tooltip"]["borderColor"] = "#d8d8d8";
        $data["tooltip"]["xDateFormat"] = "%Y-%m-%d %H:%M";
        //$data["plotOptions"]["series"]["marker"]["radius"] = 5;



        $data["plotOptions"]["series"]["marker"]["enabled"] = true;
        $data["plotOptions"]["series"]["marker"]["radius"] = 5;





        #构造图表数据
        $data["chart"]["type"] = "spline";
        $data["title"]["text"] = "Appstore榜单更新时间点预测";
        $data["title"]["style"] = "fontFamily:'微软雅黑', 'Microsoft YaHei',Arial,Helvetica,sans-serif,'宋体',";
        $data["yAxis"] = array(
            array("title"=>array("text"=>""),
                "labels"=>array("enabled"=>false)),
        );


        //$data["xAxis"]["gridLineWidth"] = 1; //纵向网格线宽度
        $data["xAxis"]["type"] = "datetime";//
        $data["xAxis"]["dateTimeLabelFormats"]["day"] = "%m-%e";//x周日期显示方法



        //版权信息
        $data["credits"]["text"] = "APPBK.COM";
        $data["credits"]["href"] = "http://www.appbk.com/";
        $data["credits"]["position"]["align"] = "right";
        $data["credits"]["position"]["x"] = -10;
        $data["credits"]["position"]["verticalAlign"] = "bottom";
        $data["credits"]["position"]["y"] = -5;



        $actual_data = array();
        $actual_data["name"] = "实际更新时间点";
        foreach($change_point_list as $item)
        {
            $actual_data["data"][] = array((strtotime($item["fetch_time"]) + 8*60*60)*1000, 1);
        }


        //做预测的更新时间点,预测48个小时的
        $predict_data = array();
        $predict_data["name"] = "预测更新时间点";
        $data["plotOptions"]["series"]["dashStyle"] = "Dash";
        for ($i=0;$i<(int)(48*60*60/$period);$i++)
        {
            $fetch_time = date("Y-m-d H:i:s", $start_time_predict + $i*$period);
            $predict_data["data"][] = array((strtotime($fetch_time) + 8.5*60*60)*1000, 2);
            $data["xAxis"]["plotLines"][] = array("color"=>'gray', "width"=>1,"dashStyle"=>'Dash', "value"=>(strtotime($fetch_time)+8.5*60*60)*1000);
        }

        //$data["xAxis"]["min"] = $predict_data["data"][0][0];
        //$data["xAxis"]["startOnTick"] = true;
        //$data["xAxis"]["tickmarkPlacement"] = "on";

        $data["xAxis"]["tickInterval"] = (3)*60*60*1000;



        $data["series"][] = $predict_data;
        $data["series"][] = $actual_data;

        return $data;
    }
}
?>