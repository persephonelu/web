<?php
require_once("CloudsearchClient.php");
require_once("CloudsearchIndex.php");
require_once("CloudsearchDoc.php");
require_once("CloudsearchSearch.php");

$access_key = "";
$secret = "";
//杭州公网API地址：http://opensearch-cn-hangzhou.aliyuncs.com
//北京公网API地址：http://opensearch-cn-beijing.aliyuncs.com 
$host = "http://intranet.opensearch-cn-hangzhou.aliyuncs.com";//根据自己的应用区域选择API
$key_type = "aliyun";  //固定值，不必修改
$opts = array('host'=>$host);
$client = new CloudsearchClient($access_key,$secret,$opts,$key_type);
$app_name = "appbk";

$n = $_GET["n"];

// 实例化一个搜索类
$search_obj = new CloudsearchSearch($client);
// 指定一个应用用于搜索
$search_obj->addIndex($app_name);
// 指定搜索关键词
$search_obj->setQueryString("title:'$n'"); //检索filter_name
// 指定返回的搜索结果的格式为json
$search_obj->setFormat("json");
// 执行搜索，获取搜索结果
$json = $search_obj->search();
// 将json类型字符串解码
header('Content-type:text/json;charset=utf-8');
echo $json;
#$result = json_decode($json,true);
#print_r($result);

?>
