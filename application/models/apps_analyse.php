<?php
/*
//Thrift  libraries
$GLOBALS['THRIFT_ROOT'] = '/usr/lib/php';

require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TTransport.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Protocol/TProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TBufferedTransport.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Type/TMessageType.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Factory/TStringFuncFactory.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/StringFunc/TStringFunc.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/StringFunc/Core.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Type/TType.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Exception/TException.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Exception/TTransportException.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Exception/TProtocolException.php';

#功能函数
require_once "resource/app_thrift/gen-php/Types.php";
require_once "resource/app_thrift/gen-php/wenwen_classify.php";


use Thrift\Protocol\TBinaryProtocol as TBinaryProtocol;
use Thrift\Transport\TSocket as TSocket;
use Thrift\Transport\TSocketPool as TSocketPool;
use Thrift\Transport\TFramedTransport as TFramedTransport;
use Thrift\Transport\TBufferedTransport as TBufferedTransport;

#功能函数
use wenwen_classifyClient as wenwen_classifyClient;
 */
class Apps_analyse extends CI_Model {

      public function __construct()
            {
                $this->load->database();
            }
      public function app_title_standard($title)
      {
          #取“-”，“——”，“_”之前的部分
         if(stripos($title,"-"))
             {
               $ipos = stripos($title,"-");
               $title = substr($title,0,$ipos + 1);
             }

         if(stripos($title,"——"))
         {
             $ipos = stripos($title,"——");
             $title = substr($title,0,$ipos + 1);
         }

         if(stripos($title,"_"))
         {
             $ipos = stripos($title,"_");
             $title = substr($title,0,$ipos + 1);
         }

         #取中英文冒号之前的部分
         if(stripos($title,":"))
         {
             $ipos = stripos($title,":");
             $title = substr($title,0,$ipos + 1);
         }
         if(stripos($title,"："))
         {
             $ipos = stripos($title,"：");
             $title = substr($title,0,$ipos + 1);
         }
         
         #去掉括号以及其内部的内容
         $regex1 ='/\(.*\)/';
         $regex2 = '/\（.*\）/';
         $title = preg_replace($regex1,'',$title);
         $title = preg_replace($regex2,'',$title);


        return $title;
      }

      public function get_tags($filter_name)
      {
          $sql = "SELECT word_list.tag, weight_max, word_list.tag_count / weibo_user_tag_all_num.num AS score
              FROM (
                  
                  SELECT resl.tag AS tag, MAX( weight ) AS weight_max, COUNT( tag ) AS tag_count
                  FROM (
                      
                      SELECT tag, weight
                      FROM weibo_user_tag
                      LEFT JOIN app_weibo ON weibo_user_tag.uid = app_weibo.uid
                      WHERE app_weibo.keyword like '$filter_name%'
                  ) AS resl
                  GROUP BY tag
                  ORDER BY tag_count DESC 
                  LIMIT 100
              ) AS word_list
              LEFT JOIN weibo_user_tag_all_num ON word_list.tag = weibo_user_tag_all_num.tag
              ORDER BY score DESC 
              limit 0,30";
          //echo $sql;
          $result = $this->db->query($sql);
          return $result->result_array();
          
      }

      #对象转换成数组函数
       public function object_to_array($obj)
      {
          $_arr = is_object($obj)? get_object_vars($obj) :$obj;
          foreach ($_arr as $key => $val){
              $val=(is_array($val)) || is_object($val) ? object_to_array($val) :$val;
              $arr[$key] = $val;
          }
          return $arr;
      }

      public function get_classes($text)
    {
        try{
            //Open an HTTP Connection to $phpServerPath
            $socket = new TSocket('10.161.210.172',50303);
            $socket->setRecvTimeout(30000);
            $socket->setSendTimeout(30000);
            $transport = new TBufferedTransport($socket, 1024, 1024);
            $protocol = new TBinaryProtocol($transport);

            //set client 
            $client = new wenwen_classifyClient($protocol);
            $transport->open();
            $result = $client->get_classify($text);
            $transport->close();

            $rtn =array();

            if(strlen($result)>2)
            {
              $classes_list = json_decode($result);

              if(count($classes_list))
              {
                $resl_list = $this->apps_analyse->object_to_array($classes_list);
                arsort($resl_list);
                $rtn = array_slice($resl_list,1,5);
            }
            }    
              return $rtn;
        }
        catch (TException $tx)
        {
            print 'classify wrong: '.$tx->getMessage()."\n";
            return -1;
        }
    }

}

?>
