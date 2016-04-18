<?php
#市场ASO相关服务
class Aso_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    #获得关键词列表信息
    public function get_keywords($category, $start=0)
    {
        $per_page = 20; //每页最多30个词


        $sql = "select word,rank,num,name
            from aso_word_rank_new left join aso_result_num 
            on aso_word_rank_new.word=aso_result_num.query
            where ori_classes='$category'
            order by rank desc limit $start,$per_page";
        
        if ( $category=="应用" )
        {
            $sql = "select word,rank,num,name
            from aso_word_rank_new left join aso_result_num 
            on aso_word_rank_new.word=aso_result_num.query
            order by rank desc limit $start,$per_page";    
        }
        $result = $this->db->query($sql)->result_array();
        //var_dump($result);
        return $result;
    }

    #获得关键词个数
    public function get_keywords_num($category)
    {
        $sql = "select count(*) as result_num from aso_word_rank_new where ori_classes='$category'";
        
        if ( $category=="应用" )
        {
            $sql = "select count(*) as result_num from aso_word_rank_new";
        }    
        $result = $this->db->query($sql)->result_array();
        return $result[0]['result_num'];
    }


    #获得搜索建议词列表
    public function get_suggestion($keyword)
    {
        $url = "http://search.itunes.apple.com/WebObjects/MZSearchHints.woa/wa/hints?media=software&cc=cn&q=".$keyword;
        $content = file_get_contents($url);
        $xml = simplexml_load_string($content); //创建 SimpleXML对象 
        //var_dump($xml->dict->array);
        $result = array();
        foreach ($xml->dict->array->dict as $word)
        {
            $suggestion = array();
            $suggestion["word"] = (string)$word->string[0];
            $suggestion["value"] = (string)$word->integer;
            $result[] = $suggestion; //append
        }
        //var_dump($result);
        return $result;
    }


    //根据搜索热度和搜索结果数确定推荐度
    //search_rank: 搜索热度
    //search_num: 搜索结果数
    //返回，好，中，差
    public function recommend_level($search_rank, $search_num)
    {
        $recommend_level = "";

        if ( $search_num < 30 )
        {
            if ( $search_rank<300 )
            {
                $recommend_level = "差";
            }
            elseif ( $search_rank>=300 and $search_rank<2000 )
            {
                $recommend_level = "中";
            }
            else
            {
                $recommend_level = "好";
            }
        }
        elseif ( $search_num>=30 and $search_num<90 )
        {
            if ( $search_rank<2000 )
            {
                $recommend_level = "差";
            }
            else
            {
                $recommend_level = "中";
            }
        }
        else
        {
            if ( $search_rank<4000 )
            {
                $recommend_level = "差";
            }
            else
            {
                $recommend_level = "中";
            }
        }
        return $recommend_level;
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
