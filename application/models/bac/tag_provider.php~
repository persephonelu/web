<?php
//bing search api
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
require_once "resource/thrift/word_process/gen-php/word_process/Types.php";
require_once "resource/thrift/word_process/gen-php/word_process/WordProcess.php";


use Thrift\Protocol\TBinaryProtocol as TBinaryProtocol;
use Thrift\Transport\TSocket as TSocket;
use Thrift\Transport\TSocketPool as TSocketPool;
use Thrift\Transport\TFramedTransport as TFramedTransport;
use Thrift\Transport\TBufferedTransport as TBufferedTransport;
use word_process\WordProcessClient as WordProcessClient;

class Tag_provider extends CI_Model {
    
    //根据app name，获得标签
    public function get_tag_list($app_name)
    {
        $sql = "select * from aso_app_tag where name='$app_name' order by source desc";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    //获得两个app共同的tag
    public function get_same_keywords($app_name1, $app_name2)
    {
        $sql = "select * from aso_app_tag 
            where tag in ( select tag from aso_app_tag where name='$app_name1' )
            and name='$app_name2' order by source desc
            ";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    //根据app name，获得标签
    public function get_keywords($app_name)
    {
        $sql = "select group_concat(tag) as tags from aso_app_tag where name='$app_name' group by name";
        $result = $this->db->query($sql)->result_array();
        return $result[0]["tags"];
    }
    //根据app name，获得类别和介绍
    public function get_app_info($app_name)
    {
        $sql = "select * from app_info where name like '$app_name%' and from_plat='appstore'";
        $result = $this->db->query($sql)->result_array();
        return $result[0];
    }
    
    //输出json的搜索结果
    public function get_name_tag($app_name)
    {
        try 
        {
            //Open an HTTP Connection to $phpServerPath
            $socket = new TSocket('10.160.38.173', 10090);
            $socket->setRecvTimeout(30000);
            $socket->setSendTimeout(30000);
            $transport = new TBufferedTransport($socket, 1024, 1024);
            $protocol = new TBinaryProtocol($transport);

            //set client 
            $client = new WordProcessClient($protocol);
            $transport->open();
            $result = $client->rankRoutine($app_name);
            $transport->close();
            //var_dump($result);
            return $result;
        } 
        catch (TException $tx) 
        {
            print 'Something went wrong: '.$tx->getMessage()."\n";
            return -1;
        }
    }   

    public function get_description_tag($app_name, $app_description,$app_ori_classes)
    {
        try
        {
            //Open an HTTP Connection to $phpServerPath
            $socket = new TSocket('10.160.38.173', 10090);
            $socket->setRecvTimeout(30000);
            $socket->setSendTimeout(30000);
            $transport = new TBufferedTransport($socket, 1024, 1024);
            $protocol = new TBinaryProtocol($transport);

            //构建请求
            $req = new \word_process\RequestBrief();
            $req->appname = $app_name;
            $req->category = $app_ori_classes;
            $req->brief = $app_description;
            //set client 
            $client = new WordProcessClient($protocol);
            $transport->open();
            $result = $client->getBriefTags($req);
            $transport->close();
            var_dump($result);
            return $result;
        } 
            catch (TException $tx)
        {
            print 'Something went wrong: '.$tx->getMessage()."\n";
            return -1;
        }
    }
}

?>
