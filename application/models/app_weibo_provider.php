<?php
#app微博相关信息管理模块
class App_weibo_provider extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }
    
    //获得一个类别下微博用户的热门标签
    public function get_tag_rank($category)
    {
        if ($category=="总榜")
        {
            $category ="应用";//兼容以前的命名
        }
        $sql = "SELECT tag,weight,count(*) as freq from weibo_user_tag 
            left join app_info on weibo_user_tag.tag=app_info.filter_name 
            where app_info.from_plat='appstore' and 
            (ori_classes='$category' or ori_classes1='$category' or ori_classes2='$category') 
            group by tag order by freq desc limit 30";
        
        /*这个是正确的，但是速度非常慢，而且效果也不好，后续看看怎么改进
        $sql = "SELECT word_list.tag, weight_max, word_list.tag_count / weibo_user_tag_all_num.num AS score
              FROM (
                 
                  SELECT resl.tag AS tag, MAX( weight ) AS weight_max, COUNT( tag ) AS tag_count
                  FROM (
                     
                        select tag,weight from weibo_user_tag
                        left join 
                        (
                        select uid from app_weibo left join app_info 
                        on app_weibo.app_id = app_info.app_id
                        where  from_plat='appstore' and 
                            (ori_classes='$category' or ori_classes1='$category' or ori_classes2='$category')
                        ) as weibo_select
                        on weibo_user_tag.uid=weibo_select.uid

                      ) AS resl
                      GROUP BY tag
                      ORDER BY tag_count DESC
                      LIMIT 100
                  ) AS word_list
                  LEFT JOIN weibo_user_tag_all_num ON word_list.tag = weibo_user_tag_all_num.tag
                  ORDER BY score DESC
                  limit 0,30
                ";
          */  
            $result = $this->db->query($sql)->result_array();
            return $result;
        }

        //获得某个app的微博用户标签
        public function get_app_user_tags($app_id,$email)
        {
            /*
              $sql = "SELECT word_list.tag, weight_max, word_list.tag_count / weibo_user_tag_all_num.num AS score
                  FROM (
                      
                      SELECT resl.tag AS tag, MAX( weight ) AS weight_max, COUNT( tag ) AS tag_count
                      FROM (
                          
                          SELECT tag, weight
                          FROM weibo_user_tag
                          LEFT JOIN app_weibo ON weibo_user_tag.uid = app_weibo.uid
                          WHERE app_weibo.app_id='$app_id'
                      ) AS resl
                      GROUP BY tag
                      ORDER BY tag_count DESC 
                      LIMIT 100
                  ) AS word_list
                  LEFT JOIN weibo_user_tag_all_num ON word_list.tag = weibo_user_tag_all_num.tag
                  ORDER BY score DESC 
                  limit 0,30";
            */
            $sql = "SELECT word_list.tag, (weight_max+10)*10 as weight_max, word_list.tag_count / weibo_user_tag_all_num.num AS score
                    FROM (
                        SELECT resl.tag AS tag, MAX( weight ) AS weight_max, COUNT( tag ) AS tag_count
                        FROM (
                                SELECT tag, weight
                                 FROM weibo_user_tag right join
                                (
                                select distinct(uid) from app_weibo right join member_app_compete  on
                                app_weibo.app_id = member_app_compete .compete_app_id where
                                email='$email' and member_app_compete.app_id='$app_id'
                                union all
                                select distinct(uid) from app_weibo where app_id='$app_id'
                                ) as uid_list
                                on weibo_user_tag.uid = uid_list.uid
                        ) AS resl
                        GROUP BY tag
                        ORDER BY tag_count DESC
                        LIMIT 100
                    ) AS word_list
                    LEFT JOIN weibo_user_tag_all_num ON word_list.tag = weibo_user_tag_all_num.tag
                    ORDER BY score DESC
                    limit 0,30";

              $result = $this->db->query($sql);
              return $result->result_array();
        }

        //获得某个app的用户性别分布
        public function get_app_user_gender($app_id,$email)
        {
            $sql = "
                SELECT gender,count(*) as freq FROM
                weibo_user_info inner join
                (
                    select distinct(uid) from app_weibo right join member_app_compete  on
                    app_weibo.app_id = member_app_compete .compete_app_id where
                    email='$email' and member_app_compete.app_id='$app_id'
                    union all
                    select distinct(uid) from app_weibo where app_id='$app_id'
                ) as uid_list
                on  weibo_user_info.uid=uid_list.uid
                group by gender";
            $result = $this->db->query($sql)->result_array();
            //构造highchart图表数据
            $data = array();
            $data["chart"]["type"] = "pie";
            $data["options"] = array("chart"=>array("type"=>"pie"));//兼容ng-charts
            $data["title"]["text"] = "用户性别比例";
            $data["yAxis"]["title"]["text"] = "用户性别";
            
            //构造数据
            $gender = array("f"=>"女","m"=>"男");
            $pie_data = array();    
            $pie_data["name"] = "比例";
            foreach ($result as $item)
            {
                $pie_data["data"][] = array($gender[$item["gender"]], (int)$item["freq"]);//y轴数据
            }
            $data["series"][] = $pie_data;
             
            return $data;
        }

        //获得某个app的用户的地域分布
        public function get_app_user_area($app_id,$email)
        {
            $sql = "select name, user_selected.freq from weibo_province right join
                (select province,count(*) as freq FROM 
                weibo_user_info inner join
                (
                    select distinct(uid) from app_weibo right join member_app_compete  on
                    app_weibo.app_id = member_app_compete .compete_app_id where
                    email='$email' and member_app_compete.app_id='$app_id'
                    union all
                    select distinct(uid) from app_weibo where app_id='$app_id'
                ) as uid_list
                on  weibo_user_info.uid=uid_list.uid
                group by province
                ) as user_selected
                on user_selected.province=weibo_province.id
                order by freq desc";
            $result = $this->db->query($sql)->result_array();
            
            //构造highchart图表数据
            $data = array();
            $data["chart"]["type"] = "column";
            $data["options"] = array("chart"=>array("type"=>"column"));//兼容ng-charts
            $data["title"]["text"] = "用户地域分布";
            $data["yAxis"]["title"]["text"] = "数量比例";
            
            //构造数据
            $y_data = array();    
            $y_data["name"] = "用户省份分布";
            foreach ($result as $item)
            {   
                $data["xAxis"]["categories"][] = $item["name"];//x轴数据
                $y_data["data"][] = (int)$item["freq"];//y轴数据
            }   
            $data["series"][] = $y_data;
                 
            return $data; 
        }

        //获得某个app的用户上网时段分布 
        public function get_app_user_time($app_id, $email)
        {
            $sql = "select weibo_time_hour,count(*) as freq from app_weibo 
                    inner join
                    (
                        select compete_app_id as app_id from member_app_compete where email='$email' and app_id='$app_id'
                        union all
                        select '$app_id' as app_id
                    ) as app_id_list
                    on app_weibo.app_id=app_id_list.app_id
                    group by weibo_time_hour
                    order by weibo_time_hour";
            $result = $this->db->query($sql)->result_array();
            
            //构造highchart图表数据
            $data = array();
            $data["chart"]["type"] = "column";
            $data["options"] = array("chart"=>array("type"=>"column"));//兼容ng-charts
            $data["title"]["text"] = "用户上网时间分布";
            $data["yAxis"]["title"]["text"] = "数量比例";

            //构造数据
            $y_data = array();   
            $y_data["name"] = "用户上网时间段";
            foreach ($result as $item)
            {  
                $data["xAxis"]["categories"][] = $item["weibo_time_hour"];//x轴数据
                $y_data["data"][] = (int)$item["freq"];//y轴数据
            }  
            $data["series"][] = $y_data;

            return $data;
        }
}
        
?>
