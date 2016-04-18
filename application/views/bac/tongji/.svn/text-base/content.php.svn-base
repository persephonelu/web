 <div class="row">
 <div class="col-md-8 col-md-offset-2">
 <h2>“"<?php echo $name;?>”统计结果</h2>

      <div class="panel panel-default">
  <!-- Default panel contents -->
  	<div class="panel-heading">基本信息</div>
  	<div class="panel-body">
    <p>
    厂商：<span  class="text-danger"><?php echo $app_list[0]["company"];?></span>；<br />
      总下载：<span  class="text-danger">1888</span>；<br />
      更新时间：<span  class="text-danger"><?php echo $app_list[0]["update_time"];?></span>；
    </p>
  	</div>
	</div>
    
    
    <div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">各个市场下载统计</div>
  <div class="panel-body">
  <table class="table table-striped" >
        <thead>
        <tr>
            <th>名称</th>
            <th>类型</th>
            <th>市场</th>
            <th>下载量</th>
        </tr>
        </thead>
        <tbody>
    <?php foreach ($app_list as $app) {?>
        <tr>
        <td><?php echo $app["name"]?></td>
        <td><?php echo $app["type"]?></td>
        <td><?php echo $app["from_plat"]?></td>
        <td><?php echo $app["download_times"]?></td>
        </tr>
    <?php } ?>
        </tbody>
		</table>
  </div>
</div>

<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">走势图</div>
  <div class="panel-body">
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
  </div>
</div>

<div class="panel panel-default news">
  <!-- Default panel contents -->
  <div class="panel-heading">相关新闻</div>
  <div class="panel-body">
    <ul class="list-group">
      <li class="list-group-item"><a>货币基金的走势</a></li>
      <li class="list-group-item"><a>赶紧买赶紧买</a></li>
      <li class="list-group-item"><a>财付通理财通财付通理财通</a></li>
      <li class="list-group-item"><a>余额宝真好用真好用</a></li>
      <li class="list-group-item"><a>走起！</a></li>
    </ul>
  </div>
</div>

</div>    <!-- div -->
</div> <!-- row -->

      
      
	<footer>
        <ul>
          <li><a href="">关于我们 </a> | </li>
          <li><a href="http://jos.jd.com/">京东 jos </a> | </li>
          <li><a href="http://zone.jd.com/">京东 code</a> </li>
        </ul>
        <p>&copy;如意搜 沪ICP备12031794号</p>
        <?php echo $download;?>
        </footer>
    </div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="http://app.ruyiso.com/resource/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="http://app.ruyiso.com/resource/js/bootstrap.min.js"></script>
    <script src="http://tongji.ruyiso.com/resource/js/highcharts.js"></script>
	<script type="text/javascript">
    $(function(){
		$('#container').highcharts(<?php echo $download;?>);
      });
	  
	  
    </script>
  </body>
</html>
