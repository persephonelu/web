<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>应用宝库_ASO_App Store应用市场优化</title>
<link href="http://cdn.appbk.com/css/style.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/metisMenu.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/sb-admin-2.css" rel="stylesheet" type="text/css">

<meta name="description" content="专业的应用市场大数据分析平台，提供应用市场搜索优化等多项服务，基础业务完全免费">
<meta name="keywords" content="应用市场优化，应用市场分析，aso，app搜索优化，应用市场搜索优化">
<meta property="wb:webmaster" content="449348d7098e7a8d" />
</head>
<body>

<div class="header">
  <div class="headerCon"> <a href="http://www.appbk.com/" class="db logo fl">logo</a>
    <ul class="fl">
        <li><a href="<?php echo base_url();?>main/rank">统计排行</a></li>
        <li class="sLine">|</li>
        <li><a href="<?php echo base_url();?>aso">优化工具</a></li>
        <li class="sLine">|</li>
        <li><a href="<?php echo base_url();?>user_app">用户中心</a></li>
        <li class="sLine">|</li>
       <li><a href="http://blog.appbk.com/intro/" target="_blank">服务介绍</a></li> 
    </ul>
  
           <div class="regLogin fr pr">
                    <?php if ( isset($user) && isset($user["nickname"]) ) { ?>
                <a href="<?php echo base_url()?>user_app"><?php echo $user["nickname"];?></a>
                |<a href="<?php echo base_url()?>user/logout">退出</a>
                <?php } else { ?>
                <a href="<?php echo base_url()?>user/login">登录</a>
                |<a href="<?php echo base_url()?>user/register">注册</a>
                <?php } ?>
            </div>

    </div>
</div>
