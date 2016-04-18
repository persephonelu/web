<?php
//用户相关的model
class Appbase_provider extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

    //获得一个领域的app最新排行信息
    //store_ratio，安卓和ios指数的比例调整，默认0.7,包括用户指数，影响力指数，mo指数，
    //rd_ratio, 研发指数的比例，和市场指数对比，默认0.5
    public function get_category_apps($c,$start=0,$limit=10
        ,$store_ratio=0.7,$rd_ratio=0.5)
    {
        /*
        $sql = "select *,round((mo+pd)/2,2) as mix from app_info right JOIN
            appbase_testin_ios on app_info.app_id=appbase_testin_ios.app_id
            where appLabel like '%,$c,%' and appbase_testin_ios.app_id is not NULL
            group by appbase_testin_ios.app_id
            order by mix DESC limit $start,$limit";
        */

        $sql = "select *,app_info.filter_name as ios_filter_name,
              app_info.filter_name as ios_filter_name,
              appbase_item.filter_name as testin_filter_name
              from app_info right join
                ( select *,
                round((ios_mo*(1-$store_ratio) + mo*$store_ratio),1) as combin_mo,
                round((ios_mo*(1-$store_ratio)*(1-$rd_ratio) + mo*$store_ratio*(1-$rd_ratio) + pd*$rd_ratio),1) as combin_mix
                from
                    (
                    select appbase_online.*,
                    round(appbase_ios_new.ios_appstore_score,1) as ios_appstore_score,appbase_ios_new.community_score,
                    round((appbase_ios_new.ios_appstore_score*0.7+appbase_ios_new.community_score*0.3),1) as ios_mo,
                    round((appbase_ios_new.ios_appstore_score*(1-$store_ratio)+dcScore*$store_ratio),1) as combine_dc_score,
                    round((appbase_ios_new.community_score*(1-$store_ratio)+effectRatio*$store_ratio),1) as combine_effect_ratio
                    from appbase_online left JOIN
                    appbase_ios_new on appbase_online.app_id=appbase_ios_new.app_id
                    where appLabel like '%,$c,%'
                    order by mo DESC
                    limit $start,$limit
                    )
                    as first_data
                )
                as appbase_item
                on appbase_item.app_id=app_info.app_id";
        $result = $this->db->query($sql)->result_array();
        //获得数目
        $num = $this->get_category_apps_num($c);
        return array("num"=>$num,"results"=>$result);
    }

    //获得一个领域的app最新排行信息
    //store_ratio，安卓和ios指数的比例调整，默认0.7,包括用户指数，影响力指数，mo指数，
    //rd_ratio, 研发指数的比例，和市场指数对比，默认0.5
    //order,排序规则，默认按照combin_mix，即为综合指数
    //可选的为：combin_mo，市场指数，pd，研发指数
    public function get_category_media_apps($c,$start=0,$limit=10
        ,$order="combin_mix",$store_ratio=0.7,$rd_ratio=0.5)
    {

        /*
        $sql = "select * from app_info right join
                ( select *,
                round((ios_mo*(1-$store_ratio) + mo*$store_ratio),1) as combin_mo,
                round((ios_mo*(1-$store_ratio)*(1-$rd_ratio) + mo*$store_ratio*(1-$rd_ratio) + pd*$rd_ratio),1) as combin_mix
                from
                    (
                    select appbase_online.*,
                    round(appbase_ios_new.ios_appstore_score,1) as ios_appstore_score,appbase_ios_new.community_score,
                    round((appbase_ios_new.ios_appstore_score*0.7+appbase_ios_new.community_score*0.3),1) as ios_mo,
                    round((appbase_ios_new.ios_appstore_score*(1-$store_ratio)+dcScore*$store_ratio),1) as combine_dc_score,
                    round((appbase_ios_new.community_score*(1-$store_ratio)+effectRatio*$store_ratio),1) as combine_effect_ratio
                    from appbase_online left JOIN
                    appbase_ios_new on appbase_online.app_id=appbase_ios_new.app_id
                    where appLabel like '%,$c,%'
                    )
                    as first_data
                )
                as appbase_item
                on appbase_item.app_id=app_info.app_id
                where appbase_item.app_id<>''
                ORDER by $order DESC
                limit $start,$limit";
        */
        $sql = "select * from app_info right join
                ( select *,
                round((ios_mo*(1-$store_ratio) + mo*$store_ratio),1) as combin_mo,
                round((ios_mo*(1-$store_ratio)*(1-$rd_ratio) + mo*$store_ratio*(1-$rd_ratio) + pd*$rd_ratio),1) as combin_mix
                from
                    (
                    select appbase_online.*,
                    round(appbase_ios_new.ios_appstore_score,1) as ios_appstore_score,appbase_ios_new.community_score,
                    round((appbase_ios_new.ios_appstore_score*0.7+appbase_ios_new.community_score*0.3),1) as ios_mo,
                    round((appbase_ios_new.ios_appstore_score*(1-$store_ratio)+dcScore*$store_ratio),1) as combine_dc_score,
                    round((appbase_ios_new.community_score*(1-$store_ratio)+effectRatio*$store_ratio),1) as combine_effect_ratio
                    from appbase_online left JOIN
                    appbase_ios_new on appbase_online.app_id=appbase_ios_new.app_id
                    where appLabel like '%,$c,%'
                    )
                    as first_data
                )
                as appbase_item
                on appbase_item.app_id=app_info.app_id
                where appbase_item.app_id<>''
                group by app_info.app_id
                ORDER by $order DESC
                limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        //获得数目
        $num = $this->get_category_apps_num($c);
        return array("num"=>$num,"results"=>$result);
    }

    //获得一个领域的app排行榜app数目
    public function get_category_apps_num($c)
    {
        $sql = "select count(distinct(appbase_online.app_id)) as result_num from app_info right JOIN
            appbase_online on app_info.app_id=appbase_online.app_id
            where appLabel like '%,$c,%' and appbase_online.app_id";
        $result = $this->db->query($sql)->result_array();
        return $result[0]["result_num"];
    }

    //获取一个appbase中的app的完整信息
    public function get_app($app_id)
    {
        $store_ratio = 0.7;
        $rd_ratio = 0.5;
        $order = "combin_mix";//按照综合排序
        /*
        $sql = "select * from app_info right join
                ( select *,
                round((ios_mo*(1-$store_ratio) + mo*$store_ratio),1) as combin_mo,
                round((ios_mo*(1-$store_ratio)*(1-$rd_ratio) + mo*$store_ratio*(1-$rd_ratio) + pd*$rd_ratio),1) as combin_mix
                from
                    (
                    select appbase_online.*,
                    round(appbase_ios_new.ios_appstore_score,1) as ios_appstore_score,appbase_ios_new.community_score,
                    round((appbase_ios_new.ios_appstore_score*0.7+appbase_ios_new.community_score*0.3),1) as ios_mo,
                    round((appbase_ios_new.ios_appstore_score*(1-$store_ratio)+dcScore*$store_ratio),1) as combine_dc_score,
                    round((appbase_ios_new.community_score*(1-$store_ratio)+effectRatio*$store_ratio),1) as combine_effect_ratio
                    from appbase_online left JOIN
                    appbase_ios_new on appbase_online.app_id=appbase_ios_new.app_id
                    where appbase_online.app_id='$app_id'
                    )
                    as first_data
                )
                as appbase_item
                on appbase_item.app_id=app_info.app_id";
        */
        $sql = "select * from app_info right join
                (
                select *,
                round((ios_mo*(1-$store_ratio) + mo*$store_ratio),1) as combin_mo,
                round((ios_mo*(1-$store_ratio)*(1-$rd_ratio) + mo*$store_ratio*(1-$rd_ratio) + pd*$rd_ratio),1) as combin_mix
                from
                (
                select third_data .*,
                round(ios_appstore_score,1) as ios_appstore_score,community_score,
                round((ios_appstore_score*0.7+community_score*0.3),1) as ios_mo,
                round((ios_appstore_score*(1-$store_ratio)+dcScore*$store_ratio),1) as combine_dc_score,
                round((community_score*(1-$store_ratio)+effectRatio*$store_ratio),1) as combine_effect_ratio
                from appbase_ios_new
                inner join
                (
                select second_data.* from
                (
                select appbase_testin_new .*,app_id from appbase_testin_new inner join
                (
                select appbase_testin_ios_match.app_id, id from appbase_testin_ios_match
                where app_id='$app_id'
                ) as first_data
                on appbase_testin_new.id=first_data.id
                ) as second_data
                inner join
                (
                select app_id,max(mo) as mo from appbase_testin_new inner join
                (
                select appbase_testin_ios_match.app_id, id from appbase_testin_ios_match
                where app_id='$app_id'
                ) as first_data
                on appbase_testin_new.id=first_data.id
                group by app_id
                ) as max_data
                on second_data.app_id=max_data.app_id  and
                second_data.mo=max_data.mo
                ) as third_data
                on appbase_ios_new.app_id=third_data.app_id
                ) as fourth_data
                )
                as appbase_item
                on appbase_item.app_id=app_info.app_id
                ORDER by $order DESC";
        $result = $this->db->query($sql)->result_array();
        return empty($result)?array():$result[0];
    }

    //获得一个类别的文章列表,包括文章标题等基础信息
    public function get_category_reports($c="1003",$start=0,$limit=30)
    {
        $sql = "select * from appbase_report
            order by publish_date DESC limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //获得appbase的所有类别，已经上线的,online=1
    public function get_categories()
    {
        $sql = "select * from appbase_category where online=1";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //获得一个标签下的子类别
    public function get_child_categories($c)
    {
        $sql = "select * from appbase_category where
              parent='$c'";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //获得一个类别标签的信息
    //获得一个标签下的子类别
    public function get_category_info($c)
    {
        $sql = "select * from appbase_category where
              id='$c'";
        $result = $this->db->query($sql)->result_array();
        return $result[0];
    }
    //获得一个类别标签下的竞争图，highcharts图
    public function get_category_apps_compete($c,$start=0,$limit=30)
    {

        $sql = "select *,(mo+pd)/2 as mix from app_info right JOIN
            appbase_testin_ios on app_info.app_id=appbase_testin_ios.app_id
            where appLabel like '%,$c,%' and appbase_testin_ios.app_id is not NULL
            order by mix DESC limit $start,$limit";
        $result = $this->db->query($sql)->result_array();

        #构造图表数据
        $data = array();
        $data["chart"]["type"] = "scatter"; //散点图
        $data["chart"]["zoomType"] = "xy"; //可xy缩放
        $data["title"]["text"] = "领域竞争图";
        $data["xAxis"]["title"]["text"] = "产品开发";
        $data["yAxis"]["title"]["text"] = "市场运营";

        //版权信息
        $data["credits"]["text"] = "APPBASE";
        $data["credits"]["href"] = "http://www.appbase.im/";

        $data["plotOptions"]["scatter"]["dataLabels"]["enabled"] = true;
        $data["plotOptions"]["scatter"]["dataLabels"]["useHTML"] = true;
        $data["plotOptions"]["scatter"]["dataLabels"]["format"] =  '{point.icon}';
        $data["plotOptions"]["scatter"]["dataLabels"]["x"] =  5;
        $data["plotOptions"]["scatter"]["dataLabels"]["y"] =  16;

        $data["tooltip"]["useHTML"] = true;
        $data["tooltip"]["headerFormat"] = '';
        $data["tooltip"]["pointFormat"] = '<b>{point.app_name}</b><br/>产品开发：{point.x},市场运营：{point.y}';


        $app_list = array();
        $app_list["name"] = "app";
        foreach($result as $item)
        {
            $app_data = array();
            $app_data["x"] = (float)$item["pd"];
            $app_data["y"] = (float)$item["mo"];
            $app_data["app_name"] = $item["appName"];
            $app_data["icon"] = "<img src='" . $item['icon'] . "'  width='30px' height='30px' class='img-rounded'>";
            $app_list["data"][] = $app_data ;
        }
        $data["series"][] = $app_list;


        return $data;
    }

    //获得一个类别标签下的竞争图，highcharts图,专供媒体使用
    public function get_category_media_apps_compete($c,$start=0,$limit=30)
    {
        $order = "combin_mix";//按照综合排序
        $store_ratio = 0.7;
        $rd_ratio = 0.5;
        $sql = "select * from app_info right join
                ( select *,
                round((ios_mo*(1-$store_ratio) + mo*$store_ratio),1) as combin_mo,
                round((ios_mo*(1-$store_ratio)*(1-$rd_ratio) + mo*$store_ratio*(1-$rd_ratio) + pd*$rd_ratio),1) as combin_mix
                from
                    (
                    select appbase_online.*,
                    round(appbase_ios_new.ios_appstore_score,1) as ios_appstore_score,appbase_ios_new.community_score,
                    round((appbase_ios_new.ios_appstore_score*0.7+appbase_ios_new.community_score*0.3),1) as ios_mo,
                    round((appbase_ios_new.ios_appstore_score*(1-$store_ratio)+dcScore*$store_ratio),1) as combine_dc_score,
                    round((appbase_ios_new.community_score*(1-$store_ratio)+effectRatio*$store_ratio),1) as combine_effect_ratio
                    from appbase_online left JOIN
                    appbase_ios_new on appbase_online.app_id=appbase_ios_new.app_id
                    where appLabel like '%,$c,%'
                    )
                    as first_data
                )
                as appbase_item
                on appbase_item.app_id=app_info.app_id
                where appbase_item.app_id<>''
                ORDER by $order DESC
                limit $start,$limit";
        $result = $this->db->query($sql)->result_array();

        #构造图表数据
        $data = array();
        $data["chart"]["type"] = "scatter"; //散点图
        $data["chart"]["zoomType"] = "xy"; //可xy缩放
        $data["title"]["text"] = "领域竞争图";
        $data["xAxis"]["title"]["text"] = "产品开发";
        $data["yAxis"]["title"]["text"] = "市场运营";
        //版权信息
        $data["credits"]["text"] = "APPBASE";
        $data["credits"]["href"] = "http://www.appbase.im/";

        $data["plotOptions"]["scatter"]["dataLabels"]["enabled"] = true;
        $data["plotOptions"]["scatter"]["dataLabels"]["useHTML"] = true;
        $data["plotOptions"]["scatter"]["dataLabels"]["format"] =  '{point.icon}';
        $data["plotOptions"]["scatter"]["dataLabels"]["x"] =  5;
        $data["plotOptions"]["scatter"]["dataLabels"]["y"] =  16;

        $data["tooltip"]["useHTML"] = true;
        $data["tooltip"]["headerFormat"] = '';
        $data["tooltip"]["pointFormat"] = '<b>{point.app_name}</b><br/>产品开发：{point.x},市场运营：{point.y}';


        $app_list = array();
        $app_list["name"] = "app";
        foreach($result as $item)
        {
            $app_data = array();
            $app_data["x"] = (float)$item["pd"];
            $app_data["y"] = (float)$item["combin_mo"];
            $app_data["app_name"] = $item["appName"];
            $app_data["icon"] = "<img src='" . $item['icon'] . "'  width='30px' height='30px' class='img-rounded'>";
            $app_list["data"][] = $app_data ;
        }
        $data["series"][] = $app_list;


        return $data;
    }
    //获得app搜索结果，暂时只出top30
    public function get_app_search_results($n,$start=0,$limit=30)
    {
        /*
        $sql = "select *,round((mo+pd)/2,2) as mix from app_info right JOIN
            appbase_testin_ios on app_info.app_id=appbase_testin_ios.app_id
            where appName like '%$n%' and appbase_testin_ios.app_id is not NULL
            group by appbase_testin_ios.app_id
            order by mix DESC limit $start,$limit";
        */
        $order = "combin_mix";//按照综合排序
        $store_ratio = 0.7;
        $rd_ratio = 0.5;
        /*
        $sql = "select * from app_info right join
                ( select *,
                round((ios_mo*(1-$store_ratio) + mo*$store_ratio),1) as combin_mo,
                round((ios_mo*(1-$store_ratio)*(1-$rd_ratio) + mo*$store_ratio*(1-$rd_ratio) + pd*$rd_ratio),1) as combin_mix
                from
                    (
                    select appbase_online.*,
                    round(appbase_ios_new.ios_appstore_score,1) as ios_appstore_score,appbase_ios_new.community_score,
                    round((appbase_ios_new.ios_appstore_score*0.7+appbase_ios_new.community_score*0.3),1) as ios_mo,
                    round((appbase_ios_new.ios_appstore_score*(1-$store_ratio)+dcScore*$store_ratio),1) as combine_dc_score,
                    round((appbase_ios_new.community_score*(1-$store_ratio)+effectRatio*$store_ratio),1) as combine_effect_ratio
                    from appbase_online left JOIN
                    appbase_ios_new on appbase_online.app_id=appbase_ios_new.app_id
                    where appName like '%$n%'
                    )
                    as first_data
                )
                as appbase_item
                on appbase_item.app_id=app_info.app_id
                where appbase_item.app_id<>''
                ORDER by $order DESC
                limit $start,$limitORDER by $order DESC
                limit $start,$limit";
        */
        $sql = "select * from app_info right join
                (
                select *,
                round((ios_mo*(1-$store_ratio) + mo*$store_ratio),1) as combin_mo,
                round((ios_mo*(1-$store_ratio)*(1-$rd_ratio) + mo*$store_ratio*(1-$rd_ratio) + pd*$rd_ratio),1) as combin_mix
                from
                (
                select third_data .*,
                round(ios_appstore_score,1) as ios_appstore_score,community_score,
                round((ios_appstore_score*0.7+community_score*0.3),1) as ios_mo,
                round((ios_appstore_score*(1-$store_ratio)+dcScore*$store_ratio),1) as combine_dc_score,
                round((community_score*(1-$store_ratio)+effectRatio*$store_ratio),1) as combine_effect_ratio
                from appbase_ios_new
                inner join
                (
                select second_data.* from
                (
                select appbase_testin_new .*,app_id from appbase_testin_new inner join
                (
                select appbase_testin_ios_match.app_id, id from appbase_testin_ios_match
                where app_id in (select app_id from app_info where name like '$n%')
                ) as first_data
                on appbase_testin_new.id=first_data.id
                ) as second_data
                inner join
                (
                select app_id,max(mo) as mo from appbase_testin_new inner join
                (
                select appbase_testin_ios_match.app_id, id from appbase_testin_ios_match
                where app_id in (select app_id from app_info where name like '$n%')
                ) as first_data
                on appbase_testin_new.id=first_data.id
                group by app_id
                ) as max_data
                on second_data.app_id=max_data.app_id  and
                second_data.mo=max_data.mo
                ) as third_data
                on appbase_ios_new.app_id=third_data.app_id
                ) as fourth_data
                )
                as appbase_item
                on appbase_item.app_id=app_info.app_id
                ORDER by $order DESC
                limit $start,$limit";
        $result = $this->db->query($sql)->result_array();
        //获得数目
        $num = $this->get_app_search_results_num($n);
        return array("num"=>$num,"results"=>$result);
    }

    //获得app搜索结果数
    public function get_app_search_results_num($n)
    {
        /*
        $sql = "select count(distinct(appbase_testin_ios.app_id)) as result_num from app_info right JOIN
            appbase_testin_ios on app_info.app_id=appbase_testin_ios.app_id
            where appName like '%$n%' and appbase_testin_ios.app_id is not NULL";
        */
        $sql = "select count(*) as result_num from app_info where name like '$n%'";
        $result = $this->db->query($sql)->result_array();
        return $result[0]["result_num"];
    }

    //app所在行业pd和mo二维在竞争图中的位置。highcharts图
    public function get_app_category_compete($app_id, $c)
    {
        //获得app所在领域的竞争图
        $data = $this->get_category_media_apps_compete($c,0,10);

        //获得app的信息
        $app_info = $this->get_app($app_id);
        $app_data = array();
        //版权信息
        $app_data["credits"]["text"] = "APPBASE";
        $app_data["credits"]["href"] = "http://www.appbase.im/";

        $app_data["x"] = (float)$app_info["pd"];
        $app_data["y"] = (float)$app_info["combin_mo"];
        $app_data["app_name"] = $app_info["appName"];
        $app_data["icon"] = "<img src='" . $app_info['icon'] . "'  width='50px' height='50px' class='img-rounded'>";

        //获得类别信息
        $category_info = $this->get_category_info($c);

        //将本app的信息，插入所在领域竞争图
        $data["series"][0]["name"] = $app_info["appName"] ." 在 " . $category_info["name"] ."领域竞争图示(与该领域top10 app对比)";
        $data["series"][0]["data"][] = $app_data;
        return $data;

    }

    //app和该领域top5 app的各项指标对比图。highcharts图
    public  function get_app_feature_competes($app_id,$c)
    {
        //step 1,获得c领域 top 5的数据
        $start = 0;
        $limit = 5;
        $order = "combin_mix";//按照综合排序
        $store_ratio = 0.7;
        $rd_ratio = 0.5;
        $sql = "select * from app_info right join
                ( select *,
                round((ios_mo*(1-$store_ratio) + mo*$store_ratio),1) as combin_mo,
                round((ios_mo*(1-$store_ratio)*(1-$rd_ratio) + mo*$store_ratio*(1-$rd_ratio) + pd*$rd_ratio),1) as combin_mix
                from
                    (
                    select appbase_online.*,
                    round(appbase_ios_new.ios_appstore_score,1) as ios_appstore_score,appbase_ios_new.community_score,
                    round((appbase_ios_new.ios_appstore_score*0.7+appbase_ios_new.community_score*0.3),1) as ios_mo,
                    round((appbase_ios_new.ios_appstore_score*(1-$store_ratio)+dcScore*$store_ratio),1) as combine_dc_score,
                    round((appbase_ios_new.community_score*(1-$store_ratio)+effectRatio*$store_ratio),1) as combine_effect_ratio
                    from appbase_online left JOIN
                    appbase_ios_new on appbase_online.app_id=appbase_ios_new.app_id
                    where appLabel like '%,$c,%'
                    )
                    as first_data
                )
                as appbase_item
                on appbase_item.app_id=app_info.app_id
                where appbase_item.app_id<>'' and appbase_item.app_id<>'$app_id'
                ORDER by $order DESC
                limit $start,$limit";
        $result = $this->db->query($sql)->result_array();

        //制作chart图
        #构造图表数据
        $data = array();
        $data["chart"]["type"] = "column"; //散点图
        $data["title"]["text"] = "APP详细得分对比图";
        $data["yAxis"]["title"]["text"] = "分值";
        $data["xAxis"]["categories"] = array("用户系数","迭代系数","发行系数","持续系数","质量系数","影响系数");

        $data["credits"]["text"] = "APPBASE";
        $data["credits"]["href"] = "http://www.appbase.im/";

        foreach($result as $app_info)
        {
            $app_list = array();
            $app_list["name"] = $app_info["appName"];
            $app_list["data"] = array((float)$app_info["combine_dc_score"],(float)$app_info["verScore"],
                (float)$app_info["marketScore"],(float)$app_info["lastDateScore"],
                (float)$app_info["testScore"],(float)$app_info["combine_effect_ratio"]);
            $data["series"][] = $app_list;
        }


        //添加该app数据
        $app_info = $this->get_app($app_id);
        $app_data = array();
        //版权信息
        $data["credits"]["text"] = "APPBASE";
        $data["credits"]["href"] = "http://www.appbase.im/";

        //$app_data["name"] = $app_info["appName"];
        $app_data["name"] ="本App";
        $app_data["data"] =  array((float)$app_info["combine_dc_score"],(float)$app_info["verScore"],
            (float)$app_info["marketScore"],(float)$app_info["lastDateScore"],
            (float)$app_info["testScore"],(float)$app_info["combine_effect_ratio"]);
        $data["series"][] = $app_data;

        return $data;
    }

    //app所属的类别标签
    public function get_app_categories($app_id)
    {
        //step 1,获得app的基础信息
        //$app_info = $this->get_app($app_id);
        $sql = "select appbase_testin.* from appbase_testin right join
                (
                    select appbase_testin_ios_match.app_id, id from appbase_testin_ios_match
                    where app_id='$app_id'
                ) as first_data
                on appbase_testin.id=first_data.id
                where appLabel!=''
                order by downloadCount DESC";
        $result = $this->db->query($sql)->result_array();
        $app_info = $result[0];

        //获得testin类别
        $c_split = explode(",", $app_info["appLabel"]);
        $c_list = array();
        foreach ($c_split as $item)
        {
            if ($item!="")
            {
                $c_list[] =  "'". $item . "'";
            }
        }

        $c_list_sql = join(",",$c_list);//用于mysql查询的类别数据
        $sql = "select * from appbase_category where
              id in ($c_list_sql)";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    // app各项特征的极地蛛网图。highcharts图
    public function  get_app_feature($app_id)
    {
        $app_info = $this->get_app($app_id);
        //构造highcharts数据
        $data = array();
        //版权信息
        $data["credits"]["text"] = "APPBASE";
        $data["credits"]["href"] = "http://www.appbase.im/";

        $data["chart"]["polar"] = true; //线条
        $data["chart"]["type"] = "line"; //线条
        $data["title"]["text"] = "APP详细得分";
        $data["xAxis"] = array();
        $data["xAxis"]["categories"] = array("用户系数","迭代系数","发行系数","持续系数","质量系数","影响系数");
        $data["yAxis"] = array();
        $data["yAxis"]["gridLineInterpolation"] = "polygon";

        $app_data = array();
        $app_data["name"] ="本App";
        $app_data["data"] = array((float)$app_info["combine_dc_score"],(float)$app_info["verScore"],
            (float)$app_info["marketScore"],(float)$app_info["lastDateScore"],
            (float)$app_info["testScore"],(float)$app_info["combine_effect_ratio"]);;
        $data["series"][] = $app_data;
        return $data;
    }

    //领域top5 app的各项指标对比图。highcharts图
    public function get_top_app_feature_competes($c)
    {
        //step 1,获得c领域 top 5的数据
        $start = 0;
        $limit = 5;
        $order = "combin_mix";//按照综合排序
        $store_ratio = 0.7;
        $rd_ratio = 0.5;
        $sql = "select * from app_info right join
                ( select *,
                round((ios_mo*(1-$store_ratio) + mo*$store_ratio),1) as combin_mo,
                round((ios_mo*(1-$store_ratio)*(1-$rd_ratio) + mo*$store_ratio*(1-$rd_ratio) + pd*$rd_ratio),1) as combin_mix
                from
                    (
                    select appbase_online.*,
                    round(appbase_ios_new.ios_appstore_score,1) as ios_appstore_score,appbase_ios_new.community_score,
                    round((appbase_ios_new.ios_appstore_score*0.7+appbase_ios_new.community_score*0.3),1) as ios_mo,
                    round((appbase_ios_new.ios_appstore_score*(1-$store_ratio)+dcScore*$store_ratio),1) as combine_dc_score,
                    round((appbase_ios_new.community_score*(1-$store_ratio)+effectRatio*$store_ratio),1) as combine_effect_ratio
                    from appbase_online left JOIN
                    appbase_ios_new on appbase_online.app_id=appbase_ios_new.app_id
                    where appLabel like '%,$c,%'
                    )
                    as first_data
                )
                as appbase_item
                on appbase_item.app_id=app_info.app_id
                where appbase_item.app_id<>''
                ORDER by $order DESC
                limit $start,$limit";
        $result = $this->db->query($sql)->result_array();

        //制作chart图
        #构造图表数据
        $data = array();
        //版权信息
        $data["credits"]["text"] = "APPBASE";
        $data["credits"]["href"] = "http://www.appbase.im/";

        $data["chart"]["type"] = "column"; //散点图
        $data["title"]["text"] = "APP详细得分对比图";
        $data["yAxis"]["title"]["text"] = "分值";
        $data["xAxis"]["categories"] = array("用户系数","迭代系数","发行系数","持续系数","质量系数","影响系数");

        foreach($result as $app_info)
        {
            $app_list = array();
            $app_list["name"] = $app_info["appName"];
            $app_list["data"] = array((float)$app_info["combine_dc_score"],(float)$app_info["verScore"],
                (float)$app_info["marketScore"],(float)$app_info["lastDateScore"],
                (float)$app_info["testScore"],(float)$app_info["combine_effect_ratio"]);
            $data["series"][] = $app_list;
        }

        /*
        //添加该app数据
        $app_info = $this->get_app($app_id);
        $app_data = array();
        $app_data["name"] = $app_info["appName"];
        $app_data["data"] =  array((float)$app_info["combine_dc_score"],(float)$app_info["verScore"],
            (float)$app_info["marketScore"],(float)$app_info["lastDateScore"],
            (float)$app_info["testScore"],(float)$app_info["combine_effect_ratio"]);
        $data["series"][] = $app_data;
        */
        return $data;
    }

    // 更新app匹配
    public function update_app_match($id,$app_id)
    {
        $match_method = 5;
        //step 1，更新appbase_onine
        $sql = "update appbase_online set app_id='$app_id',
                match_method=$match_method
              where id='$id'";
        $result = $this->db2->query($sql);

        //step 2,更新appbase_testin_ios_match
        $sql = "update appbase_testin_ios_match set app_id='$app_id',
              match_type=$match_method
              where id='$id'";
        $result = $this->db2->query($sql);
        return 0;
    }

}