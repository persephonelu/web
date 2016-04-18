<?php
/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2016/1/18
 * Time: 13:49
 */

#app关键词管理模块
class App_inter_provider extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
        $this->db3 = $this->load->database('inter', TRUE); //美国的数据库
    }

    #获得关键词排行榜
    public function get_word_rank($cc, $category,$start=0, $limit=10)
    {
        $aso_result_num = $cc. "aso_result_num";
        $aso_word_rank_new = $cc . "aso_word_rank_new";
        $category_id = $this->get_category_id($category);
        $sql = "select word,rank,num,name
            from $aso_word_rank_new left join $aso_result_num
            on $aso_word_rank_new.word=$aso_result_num.query
            where $aso_result_num.ori_classes='$category_id'
            or $aso_result_num.ori_classes1='$category_id'
            or $aso_result_num.ori_classes2='$category_id'
            or $aso_result_num.ori_classes3='$category_id'
            order by rank desc limit $start,$limit";
        //echo $sql;
        if ( $category=="应用" )
        {
            $sql = "select word,rank,num,name
            from $aso_word_rank_new left join $aso_result_num
            on $aso_word_rank_new.word=$aso_result_num.query
            order by rank desc limit $start,$limit";
        }
        $result = $this->db3->query($sql)->result_array();
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

    #根据中文的类别名，获得类别id
    public function get_category_id($category)
    {
        $sql = "select classes_id from th_app_map_classes
                WHERE ori_classes='$category'";
        $result = $this->db3->query($sql)->result_array();
        return $result?$result[0]["classes_id"]:"6017";
    }
}
?>