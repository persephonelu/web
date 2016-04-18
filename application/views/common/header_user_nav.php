<!doctype html>
<!-- app信息页面 -->
<html ng-app>
<head>
<meta charset="utf-8">
<title>应用宝库_ASO_App Store应用市场优化</title>
<link href="<?php echo base_url();?>resource/css/style.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/metisMenu.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/sb-admin-2.css" rel="stylesheet" type="text/css">
<script src="http://cdn.appbk.com/js/angular.min.js"></script>
<script src="<?php echo base_url()?>resource/js/keyword.js"></script>
<script>
    //定义全局变量
    var base_url = "<?php echo base_url()?>";
    var app_id = "<?php echo $app_info["app_id"]?>";
</script>
<meta name="description" content="专业的应用市场大数据分析平台，提供应用市场搜索优化等多项服务，基础业务完全免费">
<meta name="keywords" content="应用市场优化，应用市场分析，aso，app搜索优化，应用市场搜索优化">
<meta property="wb:webmaster" content="449348d7098e7a8d" />
</head>
<body ng-controller="controller">
    <div id="wrapper">
        <!-- Navigation 头部导航-->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
            <a href="<?php echo base_url();?>" class="db logo fl">logo</a>
             </div>

            <ul class="nav navbar-top-links navbar-left">
            <li class="selected"><a href="<?php echo base_url();?>user_app">我的应用</a></li>
            <li><a href="<?php echo base_url();?>rank">排行统计</a></li>
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
                        <li><a href="<?php echo base_url()?>user/logout">
                        <i class="fa fa-sign-out fa-fw"></i>退出</a></li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>

        <!--   左侧导航栏 -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li id="index">
                        <a href="<?php echo base_url()?>user_app?nav=index"><i class="fa fa-dashboard fa-fw"></i>我的app</a>
                        </li>
                        <li class="active">
                        <a href="#"><i class="fa fa-sitemap fa-fw"></i><?php echo $app_info["name"];?><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li id="app_process_app_info">
                                <a href="<?php echo base_url() . "user_app/app_process_app_info?app_id=" . $app_info["app_id"] . "&nav=app_process_app_info"?>"><i class="fa fa-area-chart fa-fw"></i>app基本信息</a>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-search fa-fw"></i>市场优化 <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li id="keywords_manage">
                                        <a href="<?php echo base_url() . "user_app_process/keywords_manage?app_id=" . $app_info["app_id"] . "&nav=keywords_manage"?>"><i class="fa fa-database fa-fw"></i>关键词优化</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li>
                                  
                                 <li class="active">
               <a href="#"><i class="fa fa-users fa-fw"></i>用户画像 <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li id="inquery_tags">
                          <a href="<?php echo base_url() . "user_app_analyse/inquery_tags?app_id=" . 
                  $app_info["app_id"] . "&nav=inquery_tags" ?>"><i class="fa fa-tags fa-fw"></i>兴趣标签</a>
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
