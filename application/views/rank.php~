<!doctype html>
<html ng-app="rank_app"  ng-controller="main_controller">
<head>
<meta charset="utf-8">
<title>应用宝库_ASO_App Store应用市场优化</title>
<script>
    var base_url = "<?php echo base_url()?>";
    var email = "<?php echo $email?>";
</script>
<!-- angular -->

<link href="http://42.121.128.6/app_tongji_git/resource/css/style_main.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/bower_components/sb-admin-2/css/sb-admin-2.css" rel="stylesheet" type="text/css">



<meta name="description" content="专业的应用市场大数据分析平台，提供应用市场搜索优化等多项服务，基础业务完全免费">
<meta name="keywords" content="应用市场优化，应用市场分析，aso，app搜索优化，应用市场搜索优化">
<meta property="wb:webmaster" content="449348d7098e7a8d" />
</head>
<body>

<div id="wrapper">
        <!-- Navigation 头部导航-->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
            <a href="{{base_url}}" class="db logo fl">logo</a>
             </div>
             
            <ul class="nav navbar-top-links navbar-left">
            <li><a href="{{base_url}}main/user_app">我的应用</a></li>
            <li class="selected"><a href="{{base_url}}main/rank">排行统计</a></li>
            </ul>
            
            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                 <a ng-show="user_login_show" href="user_app">{{user_info.nickname}}</a>
                 <a ng-show="user_not_login_show" href="{{base_url}}main/login">登录|</a>
                <a ng-show="user_not_login_show" href="{{base_url}}main/register">注册</a>
                 
                      
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href=""><i class="fa fa-user fa-fw"></i>个人档</a></li>
                        <li><a href="{{base_url}}main/logout"><i class="fa fa-sign-out fa-fw"></i>退出</a></li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
               
    <!--   左侧导航栏 -->
    <div class="navbar-default sidebar" role="navigation">
      <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
        <li id="app"> <a href="#app_rank"><i class="fa fa-th-list fa-fw"></i>app排行榜</a> </li>
        <li id="word"> <a href="#word_rank"><i class="fa fa-th-large fa-fw"></i>热门app关键词</a> </li>
        <li id="tag"> <a href="#tag_rank"><i class="fa fa-users fa-fw"></i>热门用户标签</a> </li>
        </ul>
      </div>
      <!-- /.sidebar-collapse --> 
    </div>
    <!-- /.navbar-static-side --> 
 
 </nav>
<!-- Page Content -->
  <div id="page-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
         	<!-- 具体内容区域 -->
            <div ng-view></div>
         	
        </div>
        <!-- /.col-lg-12 --> 
      </div>
      <!-- /.row --> 
    </div>
    <!-- /.container-fluid --> 
  </div>
  <!-- /#page-wrapper -->  

</div>
<!-- /#wrapper --> 

<!-- footer 开始 -->
    <div class="footer">
        <h5 class="copy_link"><a href="http://blog.appbk.com/about/" target="_blank">关于我们</a> <a href="http://blog.appbk.com/" target="_blank" >团队博客</a> 用户Q群:<a href="http://jq.qq.com/?_wv=1027&amp;k=KcLnh5" target="_blank">39351116</a></h5>
</h6>       Copyright © 2014-2015 应用宝库 版权所有　沪ICP备12031794号
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1253052544'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s19.cnzz.com/z_stat.php%3Fid%3D1253052544%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));</script> </h6>
    </div>
    <!-- ./footer始 -->

   <script src="http://cdn.appbk.com/bower_components/jquery/dist/jquery.min.js"></script>
<script src="http://cdn.appbk.com/bower_components/angular/angular.min.js"></script>
<script src="http://cdn.appbk.com/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="http://cdn.appbk.com/bower_components/angular-cookies/angular-cookies.min.js"></script>

<script src="http://cdn.appbk.com/bower_components/angular-resource/angular-resource.min.js"></script>
<script src="http://cdn.appbk.com/bower_components/angular-route/angular-route.min.js"></script>
<script src="http://cdn.appbk.com/bower_components/hightcharts/highcharts.js"></script>
<script src="http://cdn.appbk.com/bower_components/hightcharts/modules/exporting.js"></script>
<script src="http://cdn.appbk.com/bower_components/highcharts-ng/dist/highcharts-ng.min.js"></script>
<script src="http://cdn.appbk.com/bower_components/angular-bootstrap/ui-bootstrap-tpls.js"></script>
<script src="http://cdn.appbk.com/bower_components/angular-sanitize/angular-sanitize.min.js"></script>
<script src="http://cdn.appbk.com/bower_components/angular-ui-select/dist/select.min.js?v=20151113"></script>
<script src="http://cdn.appbk.com/bower_components/ngStorage/ngStorage.min.js"></script>
<script src="http://cdn.appbk.com/bower_components/ui-grid/ui-grid.min.js"></script>
<script src="http://cdn.appbk.com/bower_components/angular-strap/dist/angular-strap.min.js"></script>
<script src="http://cdn.appbk.com/bower_components/angular-strap/dist/angular-strap.tpl.min.js"></script> 
    <script src="<?php echo base_url()?>resource/js/rank.js"></script> 
</body>
</html>

