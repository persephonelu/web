<?php
#趋势图
class Trend_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    #name,名称
    public function get_app_list($name, $type)
    {
        #$sql = "select * from app_info where name='$name' and classes='$type'
        #    order by download_times desc";
        $sql = "select * from app_info where name='$name' and from_plat='appstore'
            order by download_times desc limit 1";
        $result = $this->db->query($sql)->result_array();
        #var_dump($result);
        return $result?$result[0]:null;
    }

    #总下载情况,构造hightchart数据
    public function get_download($name, $type)
    {
        $sql = "select * from app_info where filter_name='$name'
            order by download_times desc";
        $result = $this->db->query($sql)->result_array();
         
        #构造图表数据
        $data = array();
        $data["chart"]["type"] = "column";
        $data["title"]["text"] = "各个市场下载总量统计";
        $data["yAxis"]["title"]["text"] = "总下载量";

        #具体数据
        $series = array();

        //市场名为X轴，下载量为Y轴
        foreach ($result as $item)
        {
            $data["xAxis"]["categories"][] = $item["from_plat"];
            $series["data"][] = (int)$item["download_times"];    
        }
        //var_dump($data["xAxis"]["categories"]);
        //添加到主变量
        $series["name"] = "总下载量";
        $data["series"][] = $series;
        //echo json_encode($data);
        return json_encode($data);
    }

    #下载趋势图，提供highcharts数据
    #X轴，用categories做时间，y轴为下载量
    public function get_download_trend($name, $type)
    {
        //get one month data
        $day_threshold = date('Y-m-d', strtotime("-30 day"));//30天钱
        $sql = "select * from app_trend where filter_name='$name' 
            and fetch_data>'$day_threshold'
            order by fetch_date";
        $result = $this->db->query($sql)->result_array();
        //var_dump($result);


        #构造图表数据
        $data = array();
        $data["chart"]["type"] = "line";
        $data["title"]["text"] = "各个市场日下载量趋势图";
        $data["yAxis"]["title"]["text"] = "下载量";

        
        #构造不同市场的数据
        $plat_data = array();
        foreach ($result as $item)
        {
            $platform = $item["from_plat"];
            $day_data = array();
            $day_data["fetch_time"] = date( "Y-m-d",strtotime($item["fetch_time"]) );
            $day_data["download_times"] = $item["download_times"];
            $plat_data[$platform][] = $day_data;
        }
        

        //构造y轴数据
        //市场名为X轴，下载量为Y轴
        $app_life = 100;//假设app的生命周期为100天

        $i = 0;
        foreach ($plat_data as $key=>$plat)
        {
            //处理一个市场的数据
            $pre_download_times = 0;//前一天的下载总量
            $y_data = array();
            $y_data["name"] = $key; #市场的name
            foreach ($plat as $item)
            {
                if ( 0 == $pre_download_times ) //第一天
                {
                    //假设为平均下载量
                    $day_download_times = (int)$item["download_times"]/$app_life;
                    $pre_download_times = (int)$item["download_times"];
                 }
                else
                {
                    //后续天数，为和前一天的下载量差
                    $day_download_times = (int)$item["download_times"] - 
                        $pre_download_times; //数据
                    if ( 0 == $day_download_times ) //如果数量变化为0,进行估算
                    {
                        $day_download_times = (int)$item["download_times"]/$app_life +
                            rand(-0.1*(int)$item["download_times"]/$app_life, 
                            0.1*(int)$item["download_times"]/$app_life);
                    }
                }
                
                $y_data["data"][] = $day_download_times;
                
                //x轴数据
                if ( 0 == $i)
                {
                    //假设所有的数据x轴相同
                    $data["xAxis"]["categories"][] = $item["fetch_time"];//暂时只添加一次日期
                }
            }
            $data["series"][] = $y_data;
            $i++;
        }
        #echo json_encode($data);
        return json_encode($data);
    }

    //获得排名的变化趋势
    public function get_rank_trend($app_id)
    {
        //获得一个月内的数据
        $day_num = -10;
        $day_num_str = (string)$day_num . " day";
        $day_threshold = date('Y-m-d', strtotime( $day_num_str ));//n天前
        $sql = "select * from app_rank where app_id='$app_id' 
            and fetch_date>'$day_threshold' and from_plat='appstore' 
            order by fetch_date";
        $result = $this->db->query($sql)->result_array();
        //var_dump($result);


        #构造图表数据
        $data = array();
        $data["chart"]["type"] = "line";
        $data["title"]["text"] = "app排名";
        $data["yAxis"]["title"]["text"] = "排名"; 

        //构造日期数据,x轴数据
        for ($i=$day_num;$i<0;$i++)
        {
            $day_str = (string)$i . " day";
            $day_pre = date('Y-m-d', strtotime( $day_str ));//n天前
            $data["xAxis"]["categories"][] = $day_pre ;
        }
        
        //构造排名数据
        #构造不同类别的数据,一级key是类别 内容是｛日期:排名}
        $category_data = array();
        foreach ($result as $item)
        {
            $key = $item["ori_classes"] . "_" . $item["rank_type"];
            $category_data[$key][ $item["fetch_date"] ] = $item["rank"];
        }
        //var_dump($category_data);
        //构造y轴数据，如果某个日期没有数据，则设置为0
        foreach ($category_data as $category=>$day_data)
        {
            //处理一个类别的数据
            $y_data = array();
            $y_data["name"] = $category; #类别的name
            foreach ( $data["xAxis"]["categories"] as $fetch_date )
            {
                if ( isset( $day_data[$fetch_date] ) )
                {
                    $y_data["data"][] = (int)$day_data[$fetch_date];
                }
                else
                {
                    $y_data["data"][] = ""; //如果没有对应的排名数据，为空
                }
            }
            $data["series"][] = $y_data;
        }

        //echo json_encode($data);
        return json_encode($data);
        
    }
}

?>
