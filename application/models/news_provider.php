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
require_once "/var/www/html/ruyiso_app/resource/thrift/gen-php/Bing/Types.php";
require_once "/var/www/html/ruyiso_app/resource/thrift/gen-php/Bing/BingSearchServer.php";


use Thrift\Protocol\TBinaryProtocol as TBinaryProtocol;
use Thrift\Transport\TSocket as TSocket;
use Thrift\Transport\TSocketPool as TSocketPool;
use Thrift\Transport\TFramedTransport as TFramedTransport;
use Thrift\Transport\TBufferedTransport as TBufferedTransport;
use Bing\BingSearchServerClient as BingSearchServerClient;

class News_provider extends CI_Model {
    //输出json的搜索结果
    public function search_json($query, $search_type, $start, $limit)
    {
        try 
        {
            //Open an HTTP Connection to $phpServerPath
            $socket = new TSocket('localhost', 60301);
            $socket->setRecvTimeout(30000);
            $socket->setSendTimeout(30000);
            $transport = new TBufferedTransport($socket, 1024, 1024);
            $protocol = new TBinaryProtocol($transport);

            //set client 
            $client = new BingSearchServerClient($protocol);
            $transport->open();
            $result = $client->get_result($query, $search_type, $start, $limit);
            $transport->close();
            return $result;
        } 
        catch (TException $tx) 
        {
            print 'Something went wrong: '.$tx->getMessage()."\n";
            return -1;
        }
    }   
 
    //输出array结果
    public function search($query, $search_type, $start, $limit)
    {
        try
        {
            //Open an HTTP Connection to $phpServerPath
            $socket = new TSocket('localhost', 60301);
            $socket->setRecvTimeout(30000);
            $socket->setSendTimeout(30000);
            $transport = new TBufferedTransport($socket, 1024, 1024);
            $protocol = new TBinaryProtocol($transport);

            //set client 
            $client = new BingSearchServerClient($protocol);
            $transport->open();
            $json_result = $client->get_result($query, $search_type, $start, $limit);
            $transport->close();
            $result_array = json_decode($json_result, true);
            if ( !empty($result_array["d"]["results"]) )
            {
                $result = $result_array["d"]["results"];
            }
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
