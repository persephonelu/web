<!doctype html>
<html ng-app>
<head>
<meta charset="utf-8">
<title>应用宝库_ASO_App Store应用市场优化</title>
<link href="<?php echo base_url();?>resource/css/style.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/metisMenu.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="http://cdn.appbk.com/css/sb-admin-2.css" rel="stylesheet" type="text/css">
<script>
    var base_url = "<?php echo base_url()?>";
</script>
<!-- angular -->
<script src="http://cdn.appbk.com/js/angular.min.js"></script>
<script src="<?php echo base_url();?>resource/js/register.js"></script>

<meta name="description" content="专业的应用市场大数据分析平台，提供应用市场搜索优化等多项服务，基础业务完全免费">
<meta name="keywords" content="应用市场优化，应用市场分析，aso，app搜索优化，应用市场搜索优化">
<meta property="wb:webmaster" content="449348d7098e7a8d" />
</head>
<body ng-controller="controller">
<div id="wrapper">
        <!-- Navigation 头部导航-->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
            <a href="{{base_url}}" class="db logo fl">logo</a>
             </div>
             
            <ul class="nav navbar-top-links navbar-left">
            <li id="user_app"><a href="{{base_url}}main/user_app">我的应用</a></li>
            <li id="paihang"><a href="{{base_url}}main/rank">排行统计</a></li>
            </ul>
            
            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                
                <a ng-href="{{base_url}}main/login">登录</a>
                |<a ng-href="{{base_url}}main/register">注册</a>
                 
                      
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a ng-href=""><i class="fa fa-user fa-fw"></i>个人档</a></li>
                        <li><a ng-href="{{base_url}}main/logout"><i class="fa fa-sign-out fa-fw"></i>退出</a></li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
          </nav>
            <!-- /.navbar-top-links 头部导航结束 -->
            
    </div>
    <!-- /#wrapper -->
<div class="container">
  <div class="row">
    <div class="col-md-8">
      <form role="form" method="post" ng-submit="register_user()">
        <div class="form-group">
          <label for="exampleInputEmail1">E-mail:</label>
          <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" ng-model="email">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">密码:</label>
          <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" ng-model="password">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">确认密码:</label>
          <input type="password" name="password_check" class="form-control" id="exampleInputPassword1" placeholder="Password" ng-model="password_check">
        </div>
        <button type="submit" class="btn btn-primary">注册</button>
        <br/>
        <br/>
        <p><a ng-href="{{base_url}}user/login">已经注册过？登陆</a> &nbsp;|&nbsp; <a ng-href="{{base_url}}user/weibo_login">新浪微博直接登陆</a></p>
      </form>
      <br/>
      <br/>
      <h3 class="text-danger">{{error_message}}</h3>
    </div>
  </div>
</div>
<!-- footer 开始 -->
<div class="footer footer_main">
  <h5 class="copy_link"><a href="http://blog.appbk.com/about/" target="_blank">关于我们</a> <a href="http://blog.appbk.com/" target="_blank" >团队博客</a> 用户Q群:<a href="http://jq.qq.com/?_wv=1027&amp;k=KcLnh5" target="_blank">39351116</a></h5>
  </h6>
  Copyright © 2014-2015 应用宝库 版权所有　沪ICP备12031794号 
  <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1253052544'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s19.cnzz.com/z_stat.php%3Fid%3D1253052544%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));</script>
  </h6>
</div>
<!-- ./footer始 --> 

<!-- jQuery --> 
<script src="http://cdn.appbk.com/js/jquery.js"></script> 

<!-- Bootstrap Core JavaScript --> 
<script src="http://cdn.appbk.com/js/bootstrap.min.js"></script> 

<!-- Metis Menu Plugin JavaScript --> 
<script src="http://cdn.appbk.com/js/metisMenu.min.js"></script> 

<!-- Custom Theme JavaScript --> 
<script src="http://cdn.appbk.com/js/sb-admin-2.js"></script>
</body>
</html>
