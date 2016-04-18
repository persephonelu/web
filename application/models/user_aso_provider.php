<?php
/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2015/12/22
 * Time: 16:59
 */
//用户portal监控页面
class User_aso_provider extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

    //获得app所有可能的关键词，通过搜索结果倒推
    public function get_app_possible_keywords($email,$app_id, $start=0, $limit=30)
    {
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
        $result = $this->db->query($sql)->result_array();

        //获得用户选择的扩展词,判断是否选择
        $user_final_words = $this->get_aso_dict($email, $app_id);
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

        $num = $this->get_app_possible_keywords_num($app_id);
        return array("num"=>$num,"results"=>$result);
    }

    public function get_app_possible_keywords_num($app_id)
    {
        $sql = "select count(*) as result_num from aso_search_result_new where app_id='$app_id'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    //获得用户选择的一个app的aso词，返回一个dict
    public function get_aso_dict($email, $app_id)
    {
        $sql = "select * from member_word_expand
            where email='$email' and app_id ='$app_id' and user_word_type=5";
        $result = $this->db2->query($sql)->result_array();
        $result_dict = array();
        foreach ($result as $item)
        {
            $result_dict[$item["word"]] = 1;
        }
        return $result_dict;
    }

    //删除aso关键词use_word_type=5
    public function del_aso_keyword($email, $app_id, $keyword)
    {
        $sql = "delete from member_word_expand where
            email='$email' and app_id='$app_id' and word='$keyword' and user_word_type=5";
        $result = $this->db2->query($sql);
        return 0;
    }

    //添加aso关键词use_word_type=5
    public function add_aso_keyword($email, $app_id, $word_list)
    {
        //分割字符串
        $delimiters = array(",","，","，"," ",'、');
        $word_list = $this->multipleExplode($delimiters, $word_list);
        $word_type = 5;
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

    //获得aso刷排名方案
    public function get_aso_solution($email,$app_id)
    {
        //获得用户的刷词和热度
        /*
        $sql = "select member_word_expand.word as word,rank from member_word_expand left JOIN
                aso_word_rank_new on  member_word_expand.word=aso_word_rank_new.word
                where email='$email' and app_id ='$app_id' and user_word_type=5";
        */
        $sql = "select * from
                (
                select  user_word_pos.word,pos,rank,update_time,fetch_time from
                (
                    select word,pos,update_time,fetch_time from
                    (select * from member_word_expand  where email='$email' and app_id='$app_id' and user_word_type=5) as user_word_list
                    left join
                        (select * from aso_search_result_new where app_id='$app_id') as query_list
                        on user_word_list.word=query_list.query
                        ) as user_word_pos
                    left join aso_word_rank_new
                    on user_word_pos.word=aso_word_rank_new.word
                ) as user_word_pos_rank
                left join aso_result_num
                on user_word_pos_rank.word=aso_result_num.query
                ORDER by update_time DESC";
        $result = $this->db2->query($sql)->result_array();

        //获得冲榜方案
        $index = 0;
        foreach ($result as $item)
        {
            $aso_amount = $this->get_aso_amount($item["rank"],$item["pos"]);
            $result[$index]["top_amount"] = $aso_amount["top"];//冲top的量
            $result[$index]["hold_amount"] = $aso_amount["hold"];//维持的量
            $index++;
        }
        return $result;
    }

    //内部函数，判断一个词需要的刷量
    //按照区间来做,区间内平滑划分
    //输入：rank，词热度
    //输入：pos，app在词下的当前排名
    //返回冲榜和维榜的量
    public function get_aso_amount($rank,$pos)
    {
        $rank_list = array(0,4600,5000,6000,7000,8000,9000,12000);//热度点
        $top_list =  array(0,1000,1500,1800,2300,4000,6000,10000); //冲top3 排行榜
        $hold_list = array(0, 600, 900,1100,1500,2500,4000,6000); //维持量

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

        //获得冲top3的值
        $left_rank = $rank_list[$left_index];
        $right_rank = $rank_list[$left_index+1];

        $left_top = $top_list[$left_index];
        $right_top = $top_list[$left_index+1];

        $top = $left_top + ( ($rank-$left_rank)/($right_rank-$left_rank) ) * ($right_top-$left_top);

        //获得保榜的值
        $left_hold = $hold_list[$left_index];
        $right_hold = $hold_list[$left_index+1];

        $hold = $left_hold + ( ($rank-$left_rank)/($right_rank-$left_rank) ) * ($right_hold-$left_hold);

        //根据pos进行规则处理冲榜量打折
        if ( $pos == 1) //如果排名为1，不需要冲榜
        {
            $top = 0;
            $hold = 0;
        }
        else if ($pos>1 and $pos<=3) //如果App在前3，冲排名的量 等于 维护的量 即可
        {
            $top = $hold;
        }
        else if ($pos>3 and $pos<=5) //如果排名在4~5，冲排名的量*0.7
        {
            $top = 0.7*$top;
        }
        else if ($pos>5 and $pos<=10) //取冲排名再6~10，冲排名的量*0.8
        {
            $top = 0.8*$top;
        }
        else if ($pos>10 and $pos<=20) //如果排名在11~20，冲排名的量0.9
        {
            $top = 0.9*$top;
        }
        else if ($pos>=100)
        {
            $top = 0;
            $hold = 0;
        }


        return array("top"=>100*ceil($top/100),"hold"=>100*ceil($hold/100));
        
    }

    /*
     * 功能:获得一个关键词的top10搜索结果app是否机器刷的信息
     * 输入：n，关键词
     * 返回：搜索结果和是否刷榜信息
     */
    public function get_word_brush($n)
    {
        //step 1,获得搜索词top3的搜索结果
        $sql = "select app_id from aso_search_result_new_recommend
                where query='$n' and pos<=10";
        $result = $this->db->query($sql)->result_array();
        $brush_num = 0;
        $brush_list = array();
        foreach ($result as $app)
        {
            $brush = $this->check_app_brush($app["app_id"]);
            if ( 1==$brush)
            {
                $brush_num++;
                $brush_list[] = array($app["app_id"]=>1);
            }
            else
            {
                $brush_list[] = array($app["app_id"]=>0);
            }
        }

        return array("brush_app_num"=>$brush_num,"app_num"=>10,"brush_list"=>$brush_list);

    }

    /*
     * 功能：断某个app是否刷关键词
     * 方法：覆盖词中大于7000的，排名小于等于5的个数，如果大于等于10，则为刷榜App
     * 返回：1，是刷榜app；0，不是
    */
    function check_app_brush($app_id)
    {
        $sql = "select count(*) as result_num from aso_search_result_new_recommend
                left join aso_word_rank_new_recommend
                on aso_search_result_new_recommend.query=aso_word_rank_new_recommend.word
                where app_id='$app_id' and rank>7000 and pos<=10";
        $result = $this->db2->query($sql)->result_array();
        $result_num = $result[0]["result_num"];
        if ($result_num>=10)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    //实时刷新搜索结果
    public function update_search_result($n)
    {
        //分割字符串
        $delimiters = array(",","，","，"," ",'、');
        $word_list = $this->multipleExplode($delimiters, $n);
        foreach ($word_list as $word)
        {
            if ( $word=="" || $word==" ")
            {
                continue;
            }
            $sql = "insert into aso_keyword_real_time
                (word, fetch_time, is_download)
                values
                ('$word', now(),0)";
            $this->db2->query($sql);
        }
        return 0;
    }
    /*********************************关键词监控部分********************************/
    //获得刷排名词的信息，word_type=5的词，
    public function get_app_keywords_rank_and_pos($email, $app_id)
    {
        //获得搜索热度数据
        $sql = "select * from aso_word_rank_new
                where word in
                (
                   select word from member_word_expand
                   where email='$email' and app_id='$app_id' and user_word_type=5
                )";

        $word_rank_result = $this->db2->query($sql)->result_array();//搜索热度结果数据

        //获得app在搜索结果中的位置信息，部分词的命中结果中可能暂时不包括这个app
        $sql = "select * from aso_search_result_new
                where query in
                (
                   select word from member_word_expand
                   where email='$email' and app_id='$app_id'  and user_word_type=5
                )
                and app_id='$app_id'";

        $word_pos_result = $this->db2->query($sql)->result_array();//搜索热度结果位置数据
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

    //获得一个app在一个关键词下的排名变化趋势
    //limit,距今多少小时
    public function get_app_keyword_trend($app_id, $name, $limit=96)
    {
        //step 1,获得一个app最近一个月在关键词下排名的数据
        $time_threshold = date("Y-m-d H:i:s",time()-60*60*$limit);//距当前limit小时前的时间
        $sql = "select * from aso_search_result_hourly
            where query='$name' and fetch_time>'$time_threshold' and app_id='$app_id'
            order by fetch_date";
        $result = $this->db->query($sql)->result_array();

        #构造图表数据
        $data = array();
        $data["chart"]["type"] = "spline";
        $data["title"]["text"] ="App在 '" . $name . "' 的搜索排名(最近" .(string)$limit ."小时)";
        $data["title"]["style"] = "fontFamily:'微软雅黑', 'Microsoft YaHei',Arial,Helvetica,sans-serif,'宋体',";
        $data["yAxis"]["title"]["text"] = "排名";
        $data["yAxis"]["reversed"] = "true";

        $data["tooltip"]["crosshairs"] = array(array("enabled"=>"true","width"=>1,"color"=>"#d8d8d8"));
        $data["tooltip"]["pointFormat"] = '<span style="color:{series.color}">{series.name}</span>: {point.y} <br/>';
        $data["tooltip"]["shared"] = "true";
        $data["tooltip"]["borderColor"] = "#d8d8d8";

        //$data["backgroundColor"] = "#d8d8d8";
        $data["plotOptions"]["series"]["marker"]["radius"] = 1;
        //版权信息
        $data["credits"]["text"] = "APPBK";
        $data["credits"]["href"] = "http://www.appbk.com/";

        //构造日期数据,x轴数据
        for ($i=$limit;$i>0;$i--)
        {
            $time_pre = date("Y-m-d H",time()-60*60*$i);//距当前limit小时前的时间
            $data["xAxis"]["categories"][] = date("Y-m-d H",time()-60*60*$i);
        }

        //构造y轴数据
        #构造数据key是日期， 内容是内容是
        $hot_rank_data = array();
        foreach ($result as $item)
        {
            $fetch_time = date("Y-m-d H",strtotime($item["fetch_time"]));
            $hot_rank_data[ $fetch_time ] = $item["pos"];
        }

        //图表y轴真实数据
        $y_hot_data = array();
        $y_hot_data["name"] = "搜索排名";
        $y_hot_data["yAxis"] = 0;

        $pre_rank_value = NULL; //前一个时间的值
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

    //获得一个app所有用户填写itunes关键词的总曝光度的趋势图,user_word_type=5
    public function get_app_keywords_trend($email, $app_id)
    {
        //step 1,获得关键词最近7天的热度数据
        $day_num = -7;
        $day_num_str = (string)$day_num . " day";
        $day_threshold = date('Y-m-d', strtotime($day_num_str));//n天前数据
        $sql = "select * from aso_word_rank
            where word in
            (
               select word from member_word_expand
               where email='$email' and app_id='$app_id' and user_word_type=5
            )
            and fetch_date>'$day_threshold'
            order by fetch_date";
        $hot_rank_result = $this->db->query($sql)->result_array();

        //step 2,获得一个app最近一个月在关键词下排名的数据
        $sql = "select * from aso_search_result
            where query in
            (
               select word from member_word_expand
               where email='$email' and app_id='$app_id' and user_word_type=5
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
}
?>