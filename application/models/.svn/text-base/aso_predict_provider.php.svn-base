<?php
#市场ASO
class Aso_predict_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    #根据一个app的关键词列表，获得包含这些关键词的app列表
    #按照app包含关键词的数目排序取top，类似于TF*IDF搜索相关性
    public function keyword_to_app($query)
    {
        $delimiters = array(",","，","，"," ");
        $word_list = $this->multipleExplode($delimiters, $query);
        $word_list_new = array();
        foreach ($word_list as $word)
        {
            $word_list_new[] = "'".$word."'";
        }
        $word_list_in = implode(",",$word_list_new);
        //echo $word_list_in;
        $sql = "select name, count(*) as match_num from aso_app_tag 
            where tag in ($word_list_in) group by name 
            order by match_num desc limit 10";

        $result = $this->db->query($sql)->result_array();
        return $result;
    } 
    #获得关键词预测信息
    public function predict($query)
    {
        $delimiters = array(",","，","，"," ");
        $word_list = $this->multipleExplode($delimiters, $query);
        $word_list_new = array();
        foreach ($word_list as $word)
        {
            $word_list_new[] = "'".$word."'";
        }
        $word_list_in = implode(",",$word_list_new);
        //echo $word_list_in;
        $sql = "select tag,count(*) as tag_freq from aso_app_tag inner join 
        (select name, count(*) as same_num from aso_app_tag where tag in ($word_list_in) 
        group by name order by same_num desc limit 10) as app_sim_list 
        on aso_app_tag.name=app_sim_list.name group by aso_app_tag.tag 
        order by tag_freq desc limit 20";
        
        $result = $this->db->query($sql)->result_array();
        $tag_list = array();
        foreach ($result as $item)
        {
            $tag_list[] = $item["tag"];
        } 
        $new_query = implode(",",$tag_list);
        //echo $new_query;
        $result_new = $this->aso_provider->get_recommend($new_query);
        //var_dump($result_new);
        return $result_new;
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

?>
