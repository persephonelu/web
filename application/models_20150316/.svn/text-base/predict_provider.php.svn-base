<?php
#市场SAO
class Predict_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    #获得预测值
    public function get_top($select_day,  $start=0)
    {
        $from_plat = "appstore";
        $per_page = 10; //每页最多10个词
        $sql = "select * 
            from app_predict join app_info
            on app_predict.download_url = app_info.download_url
            where app_predict.from_plat='$from_plat' and fetch_date='$select_day' 
            order by score desc limit $start,$per_page";
        $result = $this->db->query($sql)->result_array();
        //var_dump($result);
        return $result;
    }

    #获得关键词个数
    public function get_keywords_num($select_day, $from_plat)
    {
        $sql = "select count(distinct app_name) as result_num from app_term_hotindex
             where from_plat='$from_plat' and fetch_date='$select_day'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    #搜索关键词
    public function search_keyword($keyword, $select_day,$start=0)
    {
        $per_page = 30; //每页最多30个词
        $sql = "select * from app_term_hotindex where 
            MATCH (app_name) AGAINST ('$keyword' IN BOOLEAN MODE)
            order by hot_index desc limit $start,$per_page";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    #搜索关键词个数
    public function search_keyword_num($keyword, $select_day)
    {
        $sql = "select count(*) as result_num from 
            app_term_hotindex where 
            MATCH (app_name) AGAINST ('$keyword' IN BOOLEAN MODE)";
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }

    #下载趋势图,及其预测
    public function get_download_trend($name, $type)
    {
        $day_threshold = date('Y-m-d', strtotime("-21 day"));//30天前
        $sql = "select * from app_trend 
            where name='$name' and from_plat='appstore'
            and fetch_date>'$day_threshold'
            order by fetch_time";
        $result = $this->db->query($sql)->result_array();
        //var_dump($result);


        #构造图表数据
        $data = array();
        $data["chart"]["type"] = "line";
        $data["title"]["text"] = "appstore下载量趋势图";
        $data["yAxis"]["title"]["text"] = "累计下载量";

        #构造不同市场的数据
        $plat_data = array();
        foreach ($result as $item)
        {
            $platform = $item["from_plat"];
            $day_data = array();
            $day_data["fetch_time"] = date( "Y-m-d",strtotime($item["fetch_time"]) );
            $day_data["download_times"] = (int)$item["download_times"];
            $plat_data[$platform][] = $day_data;
        }

        //构造y轴数据
        //市场名为X轴，下载量为Y轴
        $i = 0;
        foreach ($plat_data as $key=>$plat)
        {
            //处理一个市场的数据
            $y_data = array();
            $y_data["name"] = $key; #市场的name
            $pre_download_times = 0;
            foreach ($plat as $item) //处理该市场每一天的数据
            {
                //平滑数据问题，数据只能单增
                if ( $item["download_times"] >  $pre_download_times )
                {
                    $y_data["data"][] = $item["download_times"];
                }
                else
                {
                    $y_data["data"][] = $pre_download_times;
                }
                $pre_download_times = $item["download_times"];
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

        return json_encode($data);

    }
}

?>
