<?php
//ali search api
require_once("resource/ali_search/CloudsearchClient.php");
require_once("resource/ali_search/CloudsearchIndex.php");
require_once("resource/ali_search/CloudsearchDoc.php");
require_once("resource/ali_search/CloudsearchSearch.php");
require_once("resource/ali_search/CloudsearchSuggest.php");

//用户相关的model
class App_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

    /**************app和类别基础信息****************/
    //根据appid，获得app的详细信息,一个app id可能对应多个app，主要是语言差异
    public function get_app_info($app_id)
    {
        $sql = "select * from app_info 
            where app_id='$app_id' order by fetch_time desc";
        $result = $this->db->query($sql)->result_array();
        $app_info = array();
        if ($result)
        {
            $app_info = $result[0];
            $app_info["brief"] = str_replace("\n","<br/>",$app_info["brief"]);
        }
        return $app_info;
    }

    #获得appstore全部类别
    public function get_categories()
    {
        $sql = "select * from app_map_classes
            where from_plat='appstore' and level=1";
        $result = $this->db->query($sql)->result_array();

        //把"应用"换成 "总榜"
        $result[0]["ori_classes"] = "总榜";
        return $result;
    }

    #获得appstore游戏类别
    public function get_game_categories()
    {
        $sql = "select * from app_map_classes
            where from_plat='appstore' and level=2";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    /**************app榜单相关功能****************/
    //获得app的排行榜
    //category：类别
    //rank_type: 榜单类型
    public function get_app_rank($category, $rank_type, $start=0, $limit=10)
    {
        /*
        if ($category=="总榜" || $category=="应用")
        {
            $category = "应用"; //兼容以前的类别命名
        }
        */
        if ($category == "总榜" || $category == "应用")
        {
            $category = "app";
        }

        $sql = "SELECT app_info.name, app_info.icon, app_info.app_id,rank,
                app_rank_new.fetch_time
                FROM app_info
                RIGHT JOIN app_rank_new ON app_info.app_id = app_rank_new.app_id
                WHERE rank_type =  '$rank_type'
                AND app_rank_new.ori_classes =  '$category'
                ORDER BY rank
                LIMIT $start,$limit";
        $result = $this->db->query($sql)->result_array();
        $num = $this->get_app_rank_num($category, $rank_type);
        return array("status"=>0,"msg"=>"success","num"=>$num,"results"=>$result);
    }

    //获得榜单的结果数
    public function get_app_rank_num($category, $rank_type)
    {
        $sql = "select count(*) as result_num from app_rank_new
                    where ori_classes='$category' and rank_type='$rank_type'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    //获得app的排名变化趋势
    //limit,最近limit天的数据
    public function get_app_rank_trend($app_id,$limit,$start,$end,$rank_type)
    {
        #构造图表数据
        $data = array();

        if (10!=(int)$limit && ""!=$limit) //如果没有选择开始日期,只选择了距离当前的时间
        {
            /*
            if ( (int)$limit<=7 ) //如果是7天内的,返回小时级别数据
            {
                return $this->get_app_rank_hourly_trend($app_id,24*(int)($limit), $rank_type);
            }
            */
            //获得day_num天前的数据
            if ((int)$limit > 15)
            {
                $limit = 15;
            }
            $day_num = -1 * (int)($limit - 1);
            $day_num_str = (string)$day_num . " day";
            $day_threshold = date('Y-m-d', strtotime($day_num_str));//n天前

            if (""==$rank_type)  //如果没有选择榜单类型,默认为全部榜单
            {
                $sql = "select rank,ori_classes,rank_type,
                date_sub(fetch_date,interval 1 day) as fetch_date
                from app_rank where app_id='$app_id'
                and fetch_date>='$day_threshold'
                UNION
                (
                  select rank,ori_classes,rank_type,DATE_FORMAT(fetch_time, '%Y-%m-%d') as fetch_date
                  from app_rank_new where app_id='$app_id'
                )
                ";
            }
            else
            {
                $sql = "select rank,ori_classes,rank_type,
                date_sub(fetch_date,interval 1 day) as fetch_date
                from app_rank where app_id='$app_id'
                and fetch_date>='$day_threshold' and rank_type='$rank_type'
                UNION
                (
                  select rank,ori_classes,rank_type,
                  DATE_FORMAT(fetch_time, '%Y-%m-%d') as fetch_date
                  from app_rank_new where app_id='$app_id'and rank_type='$rank_type'
                )
                ";
            }
            $data["title"]["text"] = "app rank (recent " .(string)$limit ." days)";
            //构造日期数据,x轴数据
            for ($i=$day_num;$i<1;$i++)
            {
                $day_str = (string)$i . " day";
                $day_pre = date('Y-m-d', strtotime( $day_str ));//n天前
                $data["xAxis"]["categories"][] = $day_pre ;
            }
        }
        else //如果选择了开始和结束日期
        {
            //如果选择了今天的日期,返回最近24小时的数据
            $cur_day = date('Y-m-d');

            if ($start==$cur_day && $end==$cur_day)
            {
                return $this->get_app_rank_hourly_trend($app_id,24, $rank_type);
            }
            /*
            //如果选择了昨天的数据,返回最近48小时的以内的数据
            $pre_day = date('Y-m-d', time()-24*60*60);
            if ($start==$pre_day && $end == $pre_day)
            {
                return $this->get_app_rank_hourly_trend($app_id,48, $rank_type);
            }

            //如果选择了七天以内的,都出小时级数据
            $start_time = strtotime($start);
            $interval = round( (time() - $start_time)/3600, 0);//相差的小时
            if ( $interval<=7*24 ) //如果是7天内
            {
                return $this->get_app_rank_hourly_trend($app_id,$interval, $rank_type);
            }
            */

            //如果两个日期一致,把start往前提前一天
            if ($start == $end)
            {
                $start = date('Y-m-d', strtotime($start)-24*60*60);
            }

            if (""==$rank_type)  //如果没有选择榜单类型,默认为全部榜单
            {
                if ($end != $cur_day) //如果结束日期不是当天
                {
                    $sql = "select * from app_rank where app_id='$app_id'
                    and fetch_date>='$start' and fetch_date<='$end'";
                }
                else //如果结束日期是当天,当天的数据,使用rank_new的数据
                {
                    $sql = "select app_id,rank,ori_classes,rank_type,fetch_date
                    from app_rank where app_id='$app_id'
                    and fetch_date>='$start' and fetch_date<='$end'
                          UNION
                    select app_id,rank,ori_classes,rank_type,
                    DATE_FORMAT(fetch_time,'%Y-%m-%d') as fetch_date
                    from app_rank_new where app_id='$app_id'";
                }
            }
            else
            {
                if ($end != $cur_day) //如果结束日期不是当天
                {
                    $sql = "select * from app_rank where app_id='$app_id'
                    and fetch_date>='$start' and fetch_date<='$end'
                    and rank_type='$rank_type'";
                }
                else //如果结束日期是当天,当天的数据,使用rank_new的数据
                {
                    $sql = "select app_id,rank,ori_classes,rank_type,fetch_date
                    from app_rank where app_id='$app_id'
                    and fetch_date>='$start' and fetch_date<='$end' and rank_type='$rank_type'
                          UNION
                    select app_id,rank,ori_classes,rank_type,
                    DATE_FORMAT(fetch_time,'%Y-%m-%d') as fetch_date
                    from app_rank_new where app_id='$app_id' and rank_type='$rank_type'";
                }
            }
            $data["title"]["text"] = "app rank (from" .$start ." to ".$end.")";
            //构造日期数据,x轴数据
            $day_num = -1*(round( ( strtotime($end)-strtotime($start) )/(3600*24) ));
            for ($i=$day_num;$i<=0;$i++)
            {
                $day_pre = date('Y-m-d', strtotime($end)+$i*24*60*60);//n天前
                $data["xAxis"]["categories"][] = $day_pre ;
            }

        }
        $result = $this->db->query($sql)->result_array();

        #构造图表数据
        //$data = array();
        $data["chart"]["type"] = "spline";
        //$data["title"]["text"] = "app排名(最近" .(string)$limit ."天)";
        $data["title"]["style"] = "fontFamily:'Microsoft YaHei', 'Microsoft YaHei',Arial,Helvetica,sans-serif,'Song typeface',";
        $data["yAxis"]["title"]["text"] = "Rank";
        $data["yAxis"]["reversed"] = "true";

        $data["tooltip"]["crosshairs"] = array(array("enabled"=>"true","width"=>1,"color"=>"#d8d8d8"));
        $data["tooltip"]["pointFormat"] = '<span style="color:{series.color}">{series.name}</span>: {point.y} <br/>';
        $data["tooltip"]["shared"] = "true";
        $data["tooltip"]["borderColor"] = "#d8d8d8";

        //$data["backgroundColor"] = "#d8d8d8";
        $data["plotOptions"]["series"]["marker"]["radius"] = 2;
        //版权信息
        $data["credits"]["text"] = "APPBK.COM";
        $data["credits"]["href"] = "http://www.appbk.com/";

        $data["xAxis"]["gridLineWidth"] = 1; //纵向网格线宽度
        $data["yAxis"]["min"] = 1; //y轴范围



        //构造排名数据
        #构造不同类别的数据,一级key是类别 内容是｛日期:排名}
        $category_data = array();
        $rank_type_dict = array( "topfreeapplications"=>"free",
            "toppaidapplications"=>"paid","topgrossingapplications"=>"top grossing" );

        foreach ($result as $item)
        {
            if ($item["ori_classes"]=="app")
            {
                $key = "app_" . $rank_type_dict[ $item["rank_type"] ];
            }
            else
            {
                $key = $item["ori_classes"] . "_" . $rank_type_dict[ $item["rank_type"] ];
            }
            $category_data[$key][ $item["fetch_date"] ] = $item["rank"];
        }

        //构造y轴数据，如果某个日期没有数据，则设置为前一天的数据
        $pre_day_rank = null;
        foreach ($category_data as $category=>$day_data)
        {
            //处理一个类别的数据
            $y_data = array();
            $y_data["name"] = $category; #类别的name
            $pre_day_rank = null;
            foreach ( $data["xAxis"]["categories"] as $fetch_date )
            {
                if ( isset( $day_data[$fetch_date] ) )
                {
                    $y_data["data"][] = (int)$day_data[$fetch_date];
                    //$pre_day_rank = (int)$day_data[$fetch_date];
                }
                //暂时不做平滑,如果没有数据
                else
                {
                    //$y_data["data"][] = $pre_day_rank; //如果没有对应的排名数据，为201，即榜单之外
                    $y_data["data"][]  = null;
                }
            }
            $data["series"][] = $y_data;
        }

        //只展示一个数据列,其他都不展示
        //优先级别,总榜>（游戏，应用二级）> 游戏三级类别 | (付费>畅销,暂时不考虑)
        $score = array();//以此记录每个列$data["series"]的得分
        foreach ($data["series"] as $item)
        {
            $item_list = explode("_",$item["name"]);
            $ori_classes = $item_list[0];
            $rank_type = $item_list[1];
            $score[] = $this->get_cagegory_score($ori_classes, $rank_type);//获得类别得分
        }
        $max_index = array_search(max($score), $score); //max得分的index位置

        $i = 0;
        foreach ($data["series"] as $item)
        {
            if ($i==$max_index)
            {
                $data["series"][$i]["visible"] = true;
            }
            else
            {
                $data["series"][$i]["visible"] = false;
            }
            $i++;
        }

        return $data;
    }

    //上升最快
    public function get_app_rank_up($category, $rank_type, $start, $limit)
    {
        if ($category=="总榜" || $category=="应用")
        {
            $category = "app"; //兼容以前的类别命名
        }

        $cur_date = date("Y-m-d",time());
        //当前最新的榜单情况
        $sql = "select name,icon,rank,app_rank_new.app_id
                from app_info RIGHT join app_rank_new
                on app_rank_new.app_id=app_info.app_id
                where app_rank_new.ori_classes='$category'
                and rank_type='$rank_type'";
        $cur_result = $this->db->query($sql)->result_array();

        //今天凌晨的榜单情况
        $sql = "select app_id,rank from app_rank
                where ori_classes='$category'
                and rank_type='$rank_type'
                and fetch_date='$cur_date'";
        $pre_result = $this->db->query($sql)->result_array();

        $pre_result_dict = array();
        foreach ($pre_result as $item)
        {
            $pre_result_dict[$item["app_id"]] = $item["rank"];
        }

        //比较,以当前为基准,如果之前有排名,取之前排名和当前排名的差,如果是正的
        //表示上升,添加到结果中.
        //如果之前没有排名,当做1500,进行处理
        $num = 0;//结果数
        $result = array();
        foreach ($cur_result as $item)
        {
            if (array_key_exists($item["app_id"], $pre_result_dict))
            {
                //如果之前包含当前的 app id
                $dif = (int)$pre_result_dict[ $item["app_id"] ] - (int)$item["rank"];
                if ($dif>0)
                {
                    $item["up"] = $dif;
                    $result[] = $item;
                    $num ++;
                }
            }
            else //如果之前不包含当前的 app id
            {
                $dif = 1501 - (int)$item["rank"];
                $item["up"] = $dif;
                $result[] = $item;
                $num ++;
            }
        }

        //排序
        $final_result = $this->array_sort($result, "up", "desc");


        /*
        $sql = "select name,icon,app_list.* FROM app_info LEFT JOIN
                (
                    select app_rank_new.app_id,app_rank_new.rank,(app_rank.rank-app_rank_new.rank) as up from app_rank_new right join
                    app_rank on app_rank_new.app_id=app_rank.app_id
                    where app_rank_new.ori_classes='$category'
                    and app_rank_new.rank_type='$rank_type'
                    and app_rank.ori_classes='$category'
                    and app_rank.rank_type='$rank_type'
                    and app_rank.fetch_date='$cur_date'
                ) as app_list
                on `app_info`.app_id=app_list.app_id
                where up>0
                ORDER BY up DESC limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        */
        //$num = $this->get_app_rank_up_num($category, $rank_type,$cur_date);
        return array("status"=>0,"msg"=>"success","num"=>$num,"results"=>array_slice($final_result, $start, $limit));
    }

    public function get_app_rank_up_num($category, $rank_type,$cur_date)
    {
        $sql = "select count(*) as num from
                (
                    select app_rank_new.app_id,app_rank_new.rank,(app_rank.rank-app_rank_new.rank) as up from app_rank_new right join
                    app_rank on app_rank_new.app_id=app_rank.app_id
                    where app_rank_new.ori_classes='$category'
                    and app_rank_new.rank_type='$rank_type'
                    and app_rank.ori_classes='$category'
                    and app_rank.rank_type='$rank_type'
                    and app_rank.fetch_date='$cur_date'
                ) as app_list
                where up>0";
        $result = $this->db->query($sql)->result_array();
        return $result[0]["num"];
    }

    //下降最快
    public function get_app_rank_down($category, $rank_type, $start, $limit)
    {
        if ($category=="总榜" || $category=="应用")
        {
            $category = "app"; //兼容以前的类别命名
        }

        $cur_date = date("Y-m-d",time());
        /*
        $sql = "select name,icon,app_list.* FROM app_info LEFT JOIN
                (
                    select app_rank_new.app_id,app_rank_new.rank,(app_rank_new.rank-app_rank.rank) as down from app_rank_new right join
                    app_rank on app_rank_new.app_id=app_rank.app_id
                    where app_rank_new.ori_classes='$category'
                    and app_rank_new.rank_type='$rank_type'
                    and app_rank.ori_classes='$category'
                    and app_rank.rank_type='$rank_type'
                    and app_rank.fetch_date='$cur_date'
                ) as app_list
                on `app_info`.app_id=app_list.app_id
                where down>0
                ORDER BY down DESC limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        $num = $this->get_app_rank_down_num($category, $rank_type,$cur_date);
        */
        //当前最新的榜单情况
        $sql = "select app_id,rank from app_rank_new
                where app_rank_new.ori_classes='$category'
                and rank_type='$rank_type'";
        $cur_result = $this->db->query($sql)->result_array();

        $cur_result_dict = array();
        foreach ($cur_result as $item)
        {
            $cur_result_dict[$item["app_id"]] = $item["rank"];
        }

        //今天凌晨的榜单情况
        $sql = "select name,icon,rank,app_rank.app_id
                from app_info RIGHT join app_rank
                on app_rank.app_id=app_info.app_id
                where app_rank.ori_classes='$category'
                and rank_type='$rank_type'
                and fetch_date='$cur_date'";
        $pre_result = $this->db->query($sql)->result_array();


        //比较,以过去为基准,如果当前有排名,取当前和过去排名的差,如果是正的
        //表示上升,添加到结果中.
        //如果当前没有排名,当做1501,进行处理
        $num = 0;//结果数
        $result = array();
        foreach ($pre_result as $item)
        {
            if (array_key_exists($item["app_id"], $cur_result_dict))
            {
                //如果当前包含之前的 app id
                $dif = (int)$cur_result_dict[ $item["app_id"] ] - (int)$item["rank"];
                if ($dif>0)
                {
                    $item["down"] = $dif;
                    $item["rank"] = $cur_result_dict[ $item["app_id"] ];
                    $result[] = $item;
                    $num ++;
                }
            }
            else //如果当前不包含之前的 app id
            {
                $dif = 1501 - (int)$item["rank"];
                $item["down"] = $dif;
                $item["rank"] = 1501;
                $result[] = $item;
                $num ++;
            }
        }

        //排序
        $final_result = $this->array_sort($result, "down", "desc");

        //改写rank=1501的提示
        $i = 0;
        foreach ($final_result as $item)
        {
            if ( 1501 == (int)($item["rank"]))
            {
                $final_result[$i]["rank"] = "落榜/-";
            }
            $i++;
        }

        return array("status"=>0,"msg"=>"success","num"=>$num,"results"=>array_slice($final_result, $start, $limit));
    }

    public function get_app_rank_down_num($category, $rank_type,$cur_date)
    {
        $sql = "select count(*) as num from
                (
                    select app_rank_new.app_id,app_rank_new.rank,(app_rank_new.rank-app_rank.rank) as down from app_rank_new right join
                    app_rank on app_rank_new.app_id=app_rank.app_id
                    where app_rank_new.ori_classes='$category'
                    and app_rank_new.rank_type='$rank_type'
                    and app_rank.ori_classes='$category'
                    and app_rank.rank_type='$rank_type'
                    and app_rank.fetch_date='$cur_date'
                ) as app_list
                where down>0";
        $result = $this->db->query($sql)->result_array();
        return $result[0]["num"];
    }

    //新上架,首次上架时间update_time = 所选时间的
    public function get_relase_app($category, $date, $start, $limit)
    {
        if ("" == $date)
        {
            $date = date("Y-m-d");
        }
        if ($category=="总榜" || $category=="所有" ||$category=="应用")
        {
            $sql = "select app_id,icon,name,ori_classes from app_info
                where update_time='$date' limit $start, $limit";
        }
        else
        {
            $sql = "select app_id,icon,name,ori_classes from app_info
                where (ori_classes='$category' or ori_classes1='$category'
                or ori_classes2='$category' or ori_classes3='$category')
                and update_time='$date' limit $start, $limit";
        }
        $result = $this->db->query($sql)->result_array();
        $num = $this->get_relase_app_num($category, $date);
        return array("num"=>$num, "results"=>$result);
    }

    //新上架数量
    public function get_relase_app_num($category, $date)
    {
        if ($category=="总榜" || $category=="所有" ||$category=="应用")
        {
            $sql = "select count(*) as num from app_info
                where update_time='$date'";
        }
        else
        {
            $sql = "select count(*) as num from app_info
                where (ori_classes='$category' or ori_classes1='$category'
                or ori_classes2='$category' or ori_classes3='$category')
                and update_time='$date'";
        }
        $result = $this->db->query($sql)->result_array();
        return $result[0]["num"];
    }


    //下架App监控,选择某一个日期,check_available_time在日期内,同时download_level = -1
    //默认按照fetch_time排序,最近的排在前面
    public function get_offline_app($date, $start, $limit)
    {
        if ("" == $date)
        {
            $date = date("Y-m-d");
        }
        $start_time = $date . " 00:00:00";
        $end_time = $date . " 23:59:59";
        //注,app rank的date取当前的天,是当天0点左右下载的
        $sql = " select app_list.*,rank from
                (
                    select app_id,icon,name,ori_classes,user_comment_num_all from app_info
                    where check_available_time>='$start_time' and check_available_time<='$end_time'
                    and download_level=-1
                ) as app_list
				left join
	            (
		             SELECT rank,app_id from app_rank where fetch_date='$date'
                     and rank_type='topfreeapplications' and ori_classes='app'
                ) as app_rank_list
                on app_list.app_id=app_rank_list.app_id
                where name!=''
                order by  case when rank is null then 1 else 0 end ,rank,user_comment_num_all desc
                limit $start, $limit";
        //echo time(). "|";
        $result = $this->db->query($sql)->result_array();
        //echo time(). "|";
        $num = $this->get_offline_app_num($date);
        //echo time(). "|";
        return array("num"=>$num, "results"=>$result);
    }

    public function get_offline_app_num($date)
    {
        if ("" == $date)
        {
            $date = date("Y-m-d");
        }
        $start_time = $date . " 00:00:00";
        $end_time = $date . " 23:59:59";
        $sql = "select count(*) as num from app_info
                where check_available_time>='$start_time' and check_available_time<='$end_time'
                and download_level=-1";
        $result = $this->db->query($sql)->result_array();
        return $result[0]["num"];
    }

    public function get_cagegory_score($ori_classes, $rank_type)
    {
        $score = 0;
        if (strpos($ori_classes,"games")>=1) //如果是游戏子类 3分
        {
            $score = 3;
        }
        elseif ("app"==$ori_classes) //总榜10分
        {
            $score = 10;
        }
        else   //其他一级榜单 8 分
        {
            $score = 8;
        }

        if ("free" == $rank_type)
        {
            $score = $score * 10;
        }
        elseif ("paid" == $rank_type)
        {
            $score = $score * 8;
        }
        else //畅销榜
        {
            $score = $score * 3;
        }

        return $score;
    }

    //获得最近n个小时,某个app的排行榜变化
    //limit，小时数，默认值为24个小时
    public function get_app_rank_hourly_trend($app_id,$limit, $rank_type)
    {
        //获得limit小时前的数据
        if (10 == $limit) //默认的值
        {
            $limit  = 23;
        }

        $time_threshold = date("Y-m-d H:i:s",time()-60*60*($limit+1));//距当前limit小时前的时间
        /*
        $sql = "select * from app_rank_hourly where app_id='$app_id'
            and fetch_time>='$time_threshold'
            UNION
            select * from app_rank_new where where app_id='$app_id'";
        */
        //本sql的含义是,最后一个时间点,使用new表里面的数据,是最新的,10分钟更新一次的
        //rank_hourly表是1小时更新一次的,只在小时初更新
        //trick,后续构造每个小时的数据的时候,使用的是dict,而new的数据在后面
        //故会覆盖前面rank_hourly的数据
        if (""==$rank_type)  //如果没有选择榜单类型,默认为全部榜
        {
        $sql = "select * FROM
                (
                  select app_id,rank,ori_classes,rank_type,fetch_time from app_rank_hourly where app_id='$app_id'
                   and fetch_time>='$time_threshold'
                      UNION
                  select app_id,rank,ori_classes,rank_type,fetch_time from app_rank_new  where app_id='$app_id'
                ) as app_rank ORDER BY fetch_time";
        }
        else
        {
            $sql = "select * FROM
                (
                  select app_id,rank,ori_classes,rank_type,fetch_time from app_rank_hourly where app_id='$app_id'
                   and fetch_time>='$time_threshold' and rank_type='$rank_type'
                      UNION
                  select app_id,rank,ori_classes,rank_type,fetch_time
                  from app_rank_new  where app_id='$app_id' and rank_type='$rank_type'
                ) as app_rank ORDER BY fetch_time";
        }
        $result = $this->db->query($sql)->result_array();

        #构造图表数据
        $data = array();
        $data["chart"]["type"] = "spline";
        $data["title"]["text"] = "app rank (recent " .(string)round($limit/24,0) ." day)";
        $data["title"]["style"] = "fontFamily:'微软雅黑', 'Microsoft YaHei',Arial,Helvetica,sans-serif,'宋体',";
        $data["yAxis"]["title"]["text"] = "排名";
        $data["yAxis"]["reversed"] = "true";
        $data["yAxis"]["min"] = 1; //y轴范围

        $data["tooltip"]["crosshairs"] = array(array("enabled"=>"true","width"=>1,"color"=>"#d8d8d8"));
        $data["tooltip"]["pointFormat"] = '<span style="color:{series.color}">{series.name}</span>: {point.y} <br/>';
        $data["tooltip"]["shared"] = "true";
        $data["tooltip"]["borderColor"] = "#d8d8d8";

        //$data["backgroundColor"] = "#d8d8d8";
        $data["plotOptions"]["series"]["marker"]["radius"] = 2;
        //版权信息
        $data["credits"]["text"] = "APPBK.COM";
        $data["credits"]["href"] = "http://www.appbk.com/";
        $data["xAxis"]["gridLineWidth"] = 1; //纵向网格线宽度

        //构造日期数据,x轴数据
        $fetch_time_list = array();
        for ($i=$limit;$i>=0;$i--)
        {
            $time_pre = date("Y-m-d H",time()-60*60*$i);//距当前limit小时前的时间
            $fetch_time_list[] = $time_pre;
            $data["xAxis"]["categories"][] = date("Y-m-d H",time()-60*60*$i)."点" ;
        }

        //构造排名数据
        #构造不同类别的数据,一级key是类别 内容是｛日期:排名}
        $category_data = array();
        $rank_type_dict = array( "topfreeapplications"=>"free",
            "toppaidapplications"=>"付费","topgrossingapplications"=>"top grossing" );
        foreach ($result as $item)
        {
            if ($item["ori_classes"]=="app")
            {
                $key = "app_" . $rank_type_dict[ $item["rank_type"] ];
            }
            else
            {
                $key = $item["ori_classes"] . "_" . $rank_type_dict[ $item["rank_type"] ];
            }

            $data_time = date('Y-m-d H', strtotime( $item["fetch_time"] ));
            $category_data[$key][ $data_time ] = $item["rank"];
        }

        //构造y轴数据，如果某个日期没有数据，则设置为前一天的数据
        $data["series"] = array();
        foreach ($category_data as $category=>$day_data)
        {
            //处理一个类别的数据
            $y_data = array();
            $y_data["name"] = $category; #类别的name
            $pre_day_rank = null;
            $error_point_num = 0 ;//错误点的数目
            foreach ( $fetch_time_list as $fetch_time )
            {
                if ( isset( $day_data[$fetch_time] ) )
                {
                    $y_data["data"][] = (int)$day_data[$fetch_time];
                    $pre_day_rank = (int)$day_data[$fetch_time];
                }
                // 暂时不需要平滑
                else
                {
                    //$y_data["data"][] = null; //如果没有对应的排名数据，为201，即榜单之外
                    if ($error_point_num<15) //断点不能超过5个
                    {
                        $y_data["data"][] = $pre_day_rank;
                    }
                    else
                    {
                        $y_data["data"][] = null;//如果超过5个,就不处理了
                    }
                    $error_point_num = $error_point_num + 1;
                }

            }
            $data["series"][] = $y_data;
        }

        //只展示一个数据列,其他都不展示
        //优先级别,总榜>（游戏，应用二级）> 游戏三级类别 | (付费>畅销,暂时不考虑)
        $score = array();//以此记录每个列$data["series"]的得分
        foreach ($data["series"] as $item)
        {
            $item_list = explode("_",$item["name"]);
            $ori_classes = $item_list[0];
            $rank_type = $item_list[1];
            $score[] = $this->get_cagegory_score($ori_classes, $rank_type);//获得类别得分
        }

        if (!empty($score)) //如果数据不为空
        {
            $max_index = array_search(max($score), $score); //max得分的index位置
            $i = 0;
            foreach ($data["series"] as $item)
            {
                if ($i==$max_index)
                {
                    $data["series"][$i]["visible"] = true;
                }
                else
                {
                    $data["series"][$i]["visible"] = false;
                }
                $i++;
            }
        }


        return $data;
    }

    //获得某个app的最新排名列表,app_id可以是多个
    public function get_app_rank_list($app_id)
    {
        //分割字符串
        $delimiters = array(",","，","，"," ",'、','\n');
        $app_id_list = $this->multipleExplode($delimiters, $app_id);
        $value_list = array();
        foreach ($app_id_list as $app_id)
        {
            $value_list[] = "'$app_id'";
        }
        $value_list_join = join(",",$value_list);
        $sql = "select * from app_rank_new WHERE
              app_id in ($value_list_join) and ori_classes!='儿童游戏'";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //获得某个app当前最好的两个排名
    //规则,总榜/应用 10分,游戏榜 8分, 其它6分
    public function get_app_best_rank($app_id)
    {
        //step 1,总榜,获得排名最好的
        $sql = "select * from app_rank_new WHERE
              app_id='$app_id' and ori_classes='app' order by rank";
        $result = $this->db->query($sql)->result_array();
        $all_rank = array();
        $rank_type_dict = array( "topfreeapplications"=>"free",
            "toppaidapplications"=>"paid","topgrossingapplications"=>"grossing" );
        if ($result)
        {
            $all_rank["ori_classes"] = "app";
            $all_rank["rank_type"] = $rank_type_dict[$result[0]["rank_type"]];
            $all_rank["rank"] = $result[0]["rank"];
            //更新时间,分钟计算
            $update_time =(int)((time() - strtotime($result[0]["fetch_time"]))/60) - 3 ;

            //最长时间实际为15分钟,这里设置下,最长展示11分钟.
            if ($update_time>11)
            {
                $update_time = 11;
            }

            if ($update_time<0)
            {
                $update_time = 0;
            }


            $all_rank["update_time"] = (string)($update_time) . " minutes ago";
        }

        //step 2,分类榜单
        $category_rank = array();
        $sql = "select * from
              (select app_rank_new.ori_classes,rank,level,
              rank_type,fetch_time
              from app_rank_new
              left join app_map_classes
              on app_rank_new.ori_classes=app_map_classes.ori_classes
              WHERE from_plat='appstore' and
              app_id='$app_id' and app_rank_new.ori_classes!='app')
              as new_rank where level=1 order by rank";
        $result = $this->db->query($sql)->result_array();
        if ($result)
        {
            $category_rank["ori_classes"] = $result[0]["ori_classes"];;
            $category_rank["rank_type"] = $rank_type_dict[$result[0]["rank_type"]];
            $category_rank["rank"] = $result[0]["rank"];
            //更新时间,分钟计算
            $update_time =(int)((time() - strtotime($result[0]["fetch_time"]))/60) - 3;

            //最长时间实际为15分钟,这里设置下,最长展示11分钟.
            if ($update_time>11)
            {
                $update_time = 11;
            }

            if ($update_time<0)
            {
                $update_time = 0;
            }

            $category_rank["update_time"] = (string)($update_time) . " minutes ago";
        }

        return array($all_rank,$category_rank);
    }

    /**************app搜索功能****************/
    #app搜索,这里调用了阿里云的搜索，对英文，使用注释中的mysql like搜索
    #name 检索词,也可以是appid
    #start, 记录开始位置
    #limit，记录个数
    public function get_app_search_results($name, $start=0, $limit=10)
    {
        if ( preg_match("/^\d{6,10}$/", $name) ) //如果输入的是app_id
        {
            $sql = "select count(*) as num from app_info
            where app_id='$name'";
            
            $result = $this->db->query($sql)->result_array();
            $num = (int)$result["0"]["num"];
            
            //如果没有收录此app，下载搜索结果,插入数据库
            if ($num == 0)
            {
                //下载
                $app_info = $this->download_app_info($name);
                if ( -1 != $app_info)
                {
                    $name = $app_info["trackName"];
                    //插入app_info数据库
                    $this->insert_app_info($app_info);
                    $num = 1; 
                }
                else
                {
                    $num = 0;
                }
            }
            
            $sql = "select * from app_info
                    where app_id='$name'";
            $result = $this->db->query($sql)->result_array();
        }
        else
        {
            ///**********************英文等西方语言使用
            $sql = "select * from app_info
            where name like '%$name%' and from_plat='appstore'
            order by download_times desc limit $start,$limit";
            $result = $this->db->query($sql)->result_array();
            $num = $this->search_app_search_results_num($name);
            //*********************************/

            /*****************中文使用************************
            $ali_searh_result = $this->get_app_ali_search_results($name, $start, $limit);
            $item_list = $ali_searh_result["result"]["items"];
            $result = array();
            //字段名称转换
            foreach ($item_list as $item)
            {
                $one_result = array();
                $one_result["app_id"] = $item["id"];
                $one_result["name"] = str_replace("<em>","",$item["title"]);
                $one_result["name"] = str_replace("</em>","",$one_result["name"]);
                $one_result["icon"] = $item["icon"];
                $one_result["user_comment_num"] = $item["comment_count"];
                $one_result["ori_classes"] = $item["source"];
                $result[] = $one_result;

            }
            $num = $ali_searh_result["result"]["total"];
            *****************中文使用************************/
        }

        return array("num"=>$num,"results"=>$result);
    }

    #获得app检索结果数
    public function search_app_search_results_num($name)
    {
        $sql = "select count(*) as result_num from app_info
            where name like '$name%'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    //在线搜索app信息
    public function get_api_app_search_results($keyword)
    {
        $url = "http://itunes.apple.com/search?entity=software&country=us&explicit=NO&limit=20&term=$keyword";
        //美国的
        //$url = "http://itunes.apple.com/search?entity=software&country=us&explicit=NO&limit=20&term=$keyword";
        $content = file_get_contents($url);
        $result = json_decode($content, true);

        if ($result["resultCount"] == 0)
        {
            return array("num"=>0,"results"=>array());
        }
        else
        {
            //将数据插入数据库
            foreach ($result["results"] as $app)
            {
                $this->add_app($app);
            }
        }
        return $this->get_app_search_results($keyword);
    }

    //阿里云搜索结果
    public function get_app_ali_search_results($name, $start=0, $limit=10)
    {
        $access_key = "HoFZrmdnBheFen1y";
        $secret = "hagWeBWw6s9270Avjjni933KiGvIgh";
        $host = "http://intranet.opensearch-cn-hangzhou.aliyuncs.com";//根据自己的应用区域选择API
        $key_type = "aliyun";  //固定值，不必修改
        $opts = array('host'=>$host);
        $client = new CloudsearchClient($access_key,$secret,$opts,$key_type);
        $app_name = "appbk";

        // 实例化一个搜索类
        $search_obj = new CloudsearchSearch($client);
        // 指定一个应用用于搜索
        $search_obj->addIndex($app_name);
        // 指定搜索关键词
        $search_obj->setQueryString("title:'$name'"); //检索filter_name
        // 指定返回的搜索结果的格式为json
        $search_obj->setFormat("json");
        //设置开始位置
        $search_obj->setStartHit($start);
        //每页获取记录条数
        $search_obj->setHits($limit);
        // 执行搜索，获取搜索结果
        $json = $search_obj->search();
        $result = json_decode($json,true);
        return $result;
    }

    //获得一个关键词下全部的app搜索结果，即离线下载的全量搜索结果
    //部分app id可能没有数据
    public function get_all_app_search_results($name,$start=0,$limit=35)
    {
        $sql = "select app_search_results.app_id,name,icon,user_comment_num,pos,
               download_times, price,company,device,ori_classes,fetch_time,bundleId from
            (
            select aso_search_result_new.app_id, aso_search_result_new.fetch_time,
            app_info.name,icon,user_comment_num,pos,
            price,company,device,ori_classes,bundleId
                        from aso_search_result_new
                        left join app_info
                        on aso_search_result_new.app_id = app_info.app_id
                        where query='$name'
                        order by pos
                        limit $start, $limit
            ) as app_search_results
            left join app_predict
            on app_predict.app_id=app_search_results.app_id";

        $result = $this->db->query($sql)->result_array();

        //去掉结果为空的
        $final_result = array();
        foreach ($result as $app)
        {
            if ($app["name"]) //如果name不为空
            {
                $final_result[] = $app;
            }
        }
        $num = $this->get_all_app_search_results_num($name);
        return array("num"=>$num,"results"=>$final_result);
    }

    //搜索全部结果的结果数目
    public function get_all_app_search_results_num($name)
    {
        $sql = "select count(*) as num from aso_search_result_new
                where query='$name'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]["num"];
    }

    //获得搜索结果,根据搜索调用不同的搜索接口
    public function get_search_result($n,$start=0,$limit=30)
    {
        if ( preg_match("/^\d{6,13}$/", $n) ) //如果输入的是app_id
        {
            $sql = "select count(*) as num from app_info
            where app_id='$n'";

            $result = $this->db->query($sql)->result_array();
            $num = (int)$result["0"]["num"];

            //如果没有收录此app，下载搜索结果,插入数据库
            if ($num == 0)
            {
                //下载
                $app_info = $this->download_app_info($n);
                if ( -1 != $app_info)
                {
                    $name = $app_info["trackName"];
                    //插入app_info数据库
                    $this->insert_app_info($app_info);
                    $num = 1;
                }
                else
                {
                    $num = 0;
                }
            }

            $sql = "select * from app_info
                    where app_id='$n'";
            $result = $this->db->query($sql)->result_array();
            return array("num"=>$num,"results"=>$result);
        }

        //如果不是输入的app_id
        //获得搜索热度
        $sql = "select rank from aso_word_rank_new
               where word='$n'";
        $rank_result = $this->db->query($sql)->result_array();
        $word_rank = 0; //默认热度为0

        if ( $rank_result ) //如果有热度数据
        {
            $word_rank = (int) $rank_result[0]["rank"];
        }

        $result = array();
        if ($word_rank<4605) //获得实时搜索结果？出错处理，暂不考虑
        {
            $result = $this->get_real_app_search_results($n, $start, $limit);
            //因为获取的是实时结果,故按照当前时间来给
            $result["update_time"] = date('Y-m-d H:i:s');
        }
        else //获得数据库的结果，暂时认为>4506的都有一个小时内的搜索结果
        {
            $result = $this->get_all_app_search_results($n, $start, $limit);

            //如果大于4800,实际是每小时下载的,获取真实的下载时间
            //如果小于等于4800,时间是每天更新的,则使用距今1个半小时的时间.
            if ($word_rank>4800)
            {
                //数据的下载时间是每个小时的10分左右,插入完成在22分钟左右.
                //处理流程. 如果当前的分钟是大于22的,则使用真实的时间,最多差40分钟.
                //如果当前分钟是小于22的,则直接为当前时间减去30分钟.
                $hour = (int)date('i');
                if ($hour>22)
                {
                    $result["update_time"] = $result["results"] ? $result["results"][0]["fetch_time"] : 0;
                }
                else
                {
                    $result["update_time"] = date('Y-m-d H:i:s',time()-30*60);
                }
            }
            else
            {
                $result["update_time"] = date('Y-m-d H:i:s',time()-1.1*60*60);
            }
        }
        return $result;
    }


    //获得实时搜索结果
    public function get_real_app_search_results($n,$start=0,$limit=30)
    {
        $n =urlencode($n);
        $url = "http://47.88.28.30:8080/app_search?n=$n&start=$start&limit=$limit";
        $content = file_get_contents($url);
        $result = json_decode($content, true);
        return $result;
    }


    /**************app数据挖掘功能功能****************/
    //获得一个app的预测信息，主要是下载预测
    public function get_app_predict($app_id,$date)
    {

        if ($date=="")//如果没有设置日期,默认昨天的数据
        {
            $date = date("Y-m-d", time()-1*24*60*60);    //昨天
            /*
            $sql = "select app_predict.app_id,app_info.user_comment_num,app_predict.download_times
            from app_predict left join app_info
            on app_predict.app_id=app_info.app_id
            where app_predict.app_id='$app_id'";
            $result = $this->db->query($sql)->result_array();
            */
        }
        /*
        else //如果设置了日期，按照日期来计算
        {

            $sql = "select app_id,floor(comment_download_times/100) as user_comment_num,download_times
            from appbase_ios_trend
            where app_id='$app_id' and fetch_date='$date'";

        }
        */
        //根据排行榜计算得到的下载量预测
        $sql = "select app_id,floor(sum(800000*factor*power(rank,-0.6))) as download_times
                    from app_rank left join app_category_download on app_rank.ori_classes=app_category_download.ori_classes
                    where app_rank.rank_type='topfreeapplications' and fetch_date='$date'
                    and app_id='$app_id' group by app_id";
        $result_rank = $this->db->query($sql)->result_array();

        //根据评论量预测的下载量，主要是根据日均评论量*100计算
        $sql = "SELECT count(*) as comment_num, min(comment_date) as min_comment_date,round(100*count(*)/datediff('$date',min(comment_date))) as download_times
                    FROM app_appstore_comment where app_id='$app_id' and comment_date<'$date'";
        $result_comment = $this->db->query($sql)->result_array();

        $download_times = 0;
        if ($result_rank)
        {
            $download_times = $download_times + $result_rank[0]["download_times"];
            $download_times_by_rank = $result_rank[0]["download_times"];//根据排行榜预测的日均下载量
        }
        else
        {
            $download_times_by_rank =0;
        }
        if ($result_comment)
        {
            $download_times = $download_times + $result_comment[0]["download_times"];
            $min_comment_date = $result_comment[0]["min_comment_date"];
            $comment_num = $result_comment[0]["comment_num"]; //评论数
            $download_times_by_comment = $result_comment[0]["download_times"];//根据评论预测的日均下载量
        }
        else
        {
            $min_comment_date = $date;
            $comment_num = 0;
            $download_times_by_comment = 0;
        }


        $result = array("app_id"=>$app_id,"download_times"=>$download_times,
                        "download_times_by_rank" =>$download_times_by_rank,
                        "download_times_by_comment" =>$download_times_by_comment,
                        "user_comment_num"=>$comment_num,
                        "min_comment_date"=>$min_comment_date,"max_comment_date"=>$date);
        return $result;
    }

    //获得一个app在一个关键词下的排名位置
    public function get_app_search_pos($app_id, $n)
    {
        //获得搜索位置
        $sql = "select pos from aso_search_result_new
            where app_id='$app_id' and query='$n'";
        $result = $this->db->query($sql)->result_array();
        if (!empty($result))
        {
            $pos = $result[0]["pos"];
        }
        else
        {
            $pos = 10000;
        }

        //获得当前词的热度
        $sql = "select * from aso_word_rank_new
            where word='$n'";
        $result = $this->db->query($sql)->result_array();
        if (!empty($result))
        {
            $rank = $result[0]["rank"];
        }
        else
        {
            $rank = 0;
        }

        //获得词的搜索结果数
        $sql = "select * from aso_result_num
            where query='$n'";
        $result = $this->db->query($sql)->result_array();
        if (!empty($result))
        {
            $num = $result[0]["num"];
        }
        else
        {
            $num = 0;
        }

        return array("pos"=>$pos,"rank"=>$rank,"num"=>$num);

    }

    //系统推荐的相关app，主要根据搜索结果，推荐相关app
    //步骤1：获得app的搜索词，pos<11的
    //步骤2：获得这些关键词搜到的app_id,并按照app_id分组计算相似度得分
    //步骤3：和app info关联
    public function get_app_relate_apps($app_id, $start=0, $limit=10)
    {
        /*
        $sql = "select * from
            (select name,sum(0.5+0.5/pos) as score from aso_search_result_new  
            where query in 
           (select query from aso_search_result_new where name='$name' and pos<11) 
           and pos<11 and name !='$name' group by name) as app_list  
           left join app_info on app_list.name=app_info.name
           where app_info.from_plat='appstore'
           order by score desc limit $start,$limit";
        */
        $sql = "select * from
                    (
                    select app_id,sum(0.5+0.5/pos) as score from aso_search_result_new
                     where query in
                    (select query from aso_search_result_new where app_id='$app_id' and pos<11 )
                    and pos<11 and app_id!='$app_id' group by app_id
                    ) as app_list
                left join app_info on app_list.app_id=app_info.app_id
                order by score desc limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        $num = $this->get_app_relate_app_num($app_id);
        return array("num"=>$num,"results"=>$result);
    } 

    //系统推荐app的数量
    public function get_app_relate_app_num($app_id)
    {
        $sql = "select count(*) as result_num from (select * from aso_search_result_new
            where query in
                (select query from aso_search_result_new where app_id='$app_id' and pos<11)
            and pos<11 and app_id !='$app_id' group by app_id) as app_list";
        $result = $this->db->query($sql)->result_array();
        return $result[0]["result_num"];
    }

    //获得一个app用户同时购买的app
    public function get_app_user_also_buy_apps($app_id)
    {
        $sql = "select * from aso_user_also_buy 
              left join app_info on 
              aso_user_also_buy.relate_app_id=app_info.app_id
              where aso_user_also_buy.app_id='$app_id' order by
              fetch_date DESC";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //下载来源页，购买了这些App的，还购买了我的这个App
    public function get_app_refer_apps($app_id)
    {
        $sql = "select * from aso_user_also_buy
              left join app_info on
              aso_user_also_buy.app_id=app_info.app_id
              where aso_user_also_buy.relate_app_id='$app_id'
              order by download_times desc limit 20";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //阿里云提供的搜索hints
    public function get_app_search_hints($n)
    {
        $access_key = "HoFZrmdnBheFen1y";
        $secret = "hagWeBWw6s9270Avjjni933KiGvIgh";
        $host = "http://intranet.opensearch-cn-hangzhou.aliyuncs.com";//根据自己的应用区域选择API
        $key_type = "aliyun";  //固定值，不必修改
        $opts = array('host'=>$host);
        $client = new CloudsearchClient($access_key,$secret,$opts,$key_type);
        $app_name = "appbk";
        $suggest_name = "title_hints";

        $suggest = new CloudsearchSuggest($client);
        $suggest->setIndexName($app_name);
        $suggest->setSuggestName($suggest_name);
        $suggest->setHits(10); //多少个结果
        $suggest->setQuery($n);
        $result = json_decode($suggest->search(), true);
        return $result["suggestions"];
    }

    /**************公共函数****************/
    //将从app info api接口获得的app信息插入数据库
    public function add_app($app_info)
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
        $data["update_time"] = $app_info["releaseDate"];
        $data["company"] = $app_info["artistName"];
        $result = $this->insert_mysql($data, $table_name);
        return $result;
    }    
    
    //将k-v数组的数据插入mysql数据库
    public function insert_mysql($data, $table_name)
    {
        $key_list = array();
        $value_list = array();

        foreach ($data as $key=>$value)
        {
            $key_list[] = $key;
            $value_list[] = "'" . addslashes($value) . "'";//注意去掉mysql禁止符号
        }
        $key = implode(",",$key_list);
        $value = implode(",",$value_list);

        $sql = "insert ignore  into  " .  $table_name . " (" . $key . ") values (" . $value . ")";
        $result = $this->db2->query($sql);
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

    //根据指定键值,对数组进行排序
    public function array_sort($arr, $keys, $type = 'asc')
    {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            //对数组进行排序并保持索引关系
            asort($keysvalue);
        } else {
            //对数组进行逆向排序并保持索引关系
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[] = $arr[$k];
        }
        return $new_array;
    }

    //给定一个分类榜单和对应排名，给出对应的总榜排名。目前仅支持免费类型的榜单
    //$c,子类类别
    //$rank, 子类别排名
    public function get_equal_all_category($c, $rank)
    {
        if ($c == '游戏')
        {
            $c = 'games';
        }
        $sql = "select app_list.*,name,icon from app_info right JOIN
                (
                  select app_rank_new.* from app_rank_new right join
                   (
                       select * from app_rank_new where ori_classes='$c' and rank=$rank
                   ) as sub_app_list on app_rank_new.app_id = sub_app_list.app_id
                    and app_rank_new.`rank_type` = sub_app_list.rank_type
                    where app_rank_new.`ori_classes`='app'
                )
                as app_list on `app_info`.`app_id`=app_list.app_id";
        $result = $this->db->query($sql)->result_array();
        $rank_type_dict = array( "topfreeapplications"=>"免费",
            "toppaidapplications"=>"付费","topgrossingapplications"=>"畅销" );

        $result_dict = array();
        foreach ($result as $item)
        {
            $ori_rank_type = $item["rank_type"];
            $item["rank_type"] = $rank_type_dict[$item["rank_type"]];
            $item["ori_classes"] = "总榜";
            $item["sub_ori_classes"] = $c;
            $item["sub_rank"] = $rank;
            $result_dict[$ori_rank_type] = $item;
        }

        if ( 0 == count($result_dict))
        {
            $result_dict["msg"] = "";

        }
        return $result_dict;
    }
}

?>