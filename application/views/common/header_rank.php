<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>应用宝库_ASO_App Store应用市场优化</title>
<link href="<?php echo base_url();?>resource/css/style.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/metisMenu.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/sb-admin-2.css" rel="stylesheet" type="text/css">

<meta name="description" content="专业的应用市场大数据分析平台，提供应用市场搜索优化等多项服务，基础业务完全免费">
<meta name="keywords" content="应用市场优化，应用市场分析，aso，app搜索优化，应用市场搜索优化">
<meta property="wb:webmaster" content="449348d7098e7a8d" />
</head>
<body>

<div id="wrapper">
        <!-- Navigation 头部导航-->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
            <a href="<?php echo base_url();?>" class="db logo fl">logo</a>
             </div>
             
            <ul class="nav navbar-top-links navbar-left">
            <li><a href="<?php echo base_url();?>user_app">我的应用</a></li>
            <li class="selected"><a href="<?php echo base_url();?>rank">排行统计</a></li>
            </ul>
            
            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                
                <?php if ( isset($user) && isset($user["nickname"]) ) { ?>
                <a href="<?php echo base_url()?>user_app"><?php echo $user["nickname"];?></a>
                <?php } else { ?>
                <a href="<?php echo base_url()?>user/login">登录</a>
                |<a href="<?php echo base_url()?>user/register">注册</a>
                <?php } ?> 
                      
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i>个人档</a></li>
                        <li><a href="<?php echo base_url()?>user/logout"><i class="fa fa-sign-out fa-fw"></i>退出</a></li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
               
    <!--   左侧导航栏 -->
    <div class="navbar-default sidebar" role="navigation">
      <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
        <li id="app"> <a href="<?php echo base_url()?>rank?nav=app"><i class="fa fa-th-list fa-fw"></i>app排行榜</a> </li>
        <li id="word"> <a href="<?php echo base_url()?>word?nav=word"><i class="fa fa-th-large fa-fw"></i>热门app关键词</a> </li>
        <li id="tag"> <a href="<?php echo base_url()?>tag?nav=tag"><i class="fa fa-users fa-fw"></i>热门用户标签</a> </li>
        </ul>
      </div>
      <!-- /.sidebar-collapse --> 
    </div>
    <!-- /.navbar-static-side --> 
 
 </nav>
