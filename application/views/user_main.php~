<!doctype html>
<!-- 用户主页面 -->
<html ng-app="user_app" ng-controller="main_controller">
<head>
<meta charset="utf-8">
<title>APP宝库-Appstore应用市场优化</title>
<meta name="description" content="专业的应用市场大数据分析平台，提供应用市场搜索优化等多项服务，基础业务完全免费">
<meta name="keywords" content="应用市场优化，应用市场分析，aso，app搜索优化，应用市场搜索优化">
<meta property="wb:webmaster" content="449348d7098e7a8d" />
<link href="<?php echo base_url();?>resource/css/style.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/metisMenu.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/sb-admin-2.css" rel="stylesheet" type="text/css">
<script>
    var base_url = "<?php echo base_url()?>";
    var email = "<?php echo $email?>";
</script>
<script src="http://cdn.appbk.com/js/angular.min.js"></script>
<script src="http://cdn.appbk.com/js/angular-route.min.js"></script>
<script src="<?php echo base_url();?>resource/js/user_app.js"></script>
</head>
<body>
<div id="wrapper"> 
  
  <!-- 导航-->
  <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0"> 
    
    <!-- 头部导航-->
    <div class="navbar-header"> <a href="" class="db logo fl">logo</a> </div>
    <ul class="nav navbar-top-links navbar-left">
      <li class="selected"><a href="user_app">我的应用</a></li>
      <li><a href="rank">排行统计</a></li>
    </ul>
    <ul class="nav navbar-top-links navbar-right">
      <a href="user_app">{{user_info.nickname}}</a>
      <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"> <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i> </a>
        <ul class="dropdown-menu dropdown-user">
          <li><a href="#"><i class="fa fa-user fa-fw"></i>个人档</a></li>
             <li><a ng-href="{{base_url}}main/logout"> <i class="fa fa-sign-out fa-fw"></i>退出</a></li>
        </ul>
      </li>
    </ul>
    <!-- 头部导航结束--> 
    
    <!--   左侧导航栏 -->
    <div class="navbar-default sidebar" role="navigation" id="left_nav" ng-controller="user_app_manage_controller">
      <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
          <li id="index"> <a href="index"> <i class="fa fa-dashboard fa-fw"></i>我的app</a> </li>
        </ul>
      </div>
    </div>
    <!--   /#left_nav左侧导航栏结束 --> 
    
  </nav>
  <!-- 导航结束--> 
  
</div>
<!-- /#wrapper --> 

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

<!-- footer 开始 -->
<div class="footer">
  <h5 class="copy_link"><a href="http://blog.appbk.com/about/" target="_blank">关于我们</a> <a href="http://blog.appbk.com/" target="_blank" >团队博客</a> 用户Q群:<a href="http://jq.qq.com/?_wv=1027&amp;k=KcLnh5" target="_blank">39351116</a></h5>
  </h6>
  Copyright © 2014-2015 应用宝库 版权所有　沪ICP备12031794号 
  <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1253052544'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s19.cnzz.com/z_stat.php%3Fid%3D1253052544%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));</script>
  </h6>
</div>
<!-- ./footer结束 --> 


<!-- jQuery --> 
<script src="http://cdn.appbk.com/js/jquery.js"></script> 

<!-- Bootstrap Core JavaScript --> 
<script src="http://cdn.appbk.com/js/bootstrap.min.js"></script> 

<!-- Metis Menu Plugin JavaScript --> 
<script src="http://cdn.appbk.com/js/metisMenu.min.js"></script> 

<!-- Custom Theme JavaScript --> 
<script src="http://cdn.appbk.com/js/sb-admin-2.js"></script> 

<!-- highcharts JavaScript --> 
<script src="http://cdn.appbk.com/js/highcharts.js"></script> 

</body>
</html>
