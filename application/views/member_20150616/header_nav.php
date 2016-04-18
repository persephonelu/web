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

<div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url()?>">应用宝库 用户中心</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <?php if ( isset($user) && isset($user["nickname"]) ) { ?>
                <a href="<?php echo base_url()?>user_app"><?php echo $user["nickname"];}?></a>
 
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
            <!-- /.navbar-top-links -->

        <!--   左侧导航栏 -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li id="index">
                        <a href="<?php echo base_url()?>user_app?nav=index"><i class="fa fa-dashboard fa-fw"></i>我的app</a>
                        </li>
                         <li id="add_app">
                         <a href="<?php echo base_url()?>user_app/add_app?nav=add_app"><i class="fa fa-plus-square fa-fw"></i>添加app</a>
                        </li>
                        <li class="active">
                        <a href="#"><i class="fa fa-sitemap fa-fw"></i><?php echo $app_info["name"];?><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li id="app_process_app_info">
                                <a href="<?php echo base_url() . "user_app/app_process_app_info?app_id=" . $app_info["app_id"] . "&nav=app_process_app_info"?>"><i class="fa fa-area-chart fa-fw"></i>app信息</a>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-search fa-fw"></i>市场优化 <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li id="keywords_manage">
                                        <a href="<?php echo base_url() . "user_app_process/keywords_manage?app_id=" . $app_info["app_id"] . "&nav=keywords_manage"?>"><i class="fa fa-database fa-fw"></i>关键词管理</a>
                                        </li>
                                        <li id="keywords_optimal">
                                        <a href="<?php echo base_url() . "user_app_process/keywords_optimal?app_id=" . $app_info["app_id"] . "&nav=keywords_optimal"?>"><i class="fa fa-info fa-fw"></i>现有关键词检测</a>
                                        </li>
                                        <li id="keywords_recommend">
                                        <a href="<?php echo base_url() . "user_app_process/keywords_recommend?app_id=" . $app_info["app_id"] ."&nav=keywords_recommend"?>"><i class="fa fa-star fa-fw"></i>新关键词推荐</a>
                                        </li> 
                                      <li id="app_recommend">
                                       <a href="<?php echo base_url() . "user_app_process/app_recommend?app_id=" . $app_info["app_id"] . "&nav=app_recommend"?>"><i class="fa fa-eye fa-fw"></i>竞品app</a>
                                       </li> 
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li>
                                  <li class="active">
                                    <a href="#"><i class="fa fa-users fa-fw"></i>用户画像 <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li id="inquery_tags">
                                        <a href="<?php echo base_url() . "user_app_analyse/inquery_tags?app_id=" . $app_info["app_id"] . "&nav=inquery_tags" ?>"><i class="fa fa-tags fa-fw"></i>兴趣标签</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li> 
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
