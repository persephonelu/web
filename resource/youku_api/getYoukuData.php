<?php
include_once( 'YoukuSearcher.class.php' );
include_once( 'Config.class.php' );
/*
 * topfreeapplications 免费排行榜
 * toppaidapplications 付费排行榜
 * topgrossingapplications 畅销榜
 *
 */

//$apptype	= array("topfreeapplications", "toppaidapplications", "topgrossingapplications");

$client_id = "";
$client_secret = "";

$params['word'] = "dota";
$params['category'] = "游戏";
$params['period']       = 'history';
$params['orderby']      = 'published';
$params['page']         = '1';
$params['count']        = '30';

$youkuSearcher = new YoukuSearcher(YK_AKEY, YK_SKEY);

$ret = $youkuSearcher->searchYoukuData($params);
var_dump($ret);
$videos	= $ret->videos;//var_dump($ret);
foreach($videos as $obj)
{
	$users	= $obj->user;
	echo $obj->id."\t".$obj->thumbnail.
		"\t".$obj->title."\t".$obj->link."\t".$obj->category."\n";
	echo $users->id."\t".$users->name."\t".$users->link."\n";
	foreach($obj->streamtypes as $type)
	{
		$videotype = $videotype.$type.",";
	}

	$videotype	= trim($videotype, ",");
	echo $videotype."\n";
}

?>
