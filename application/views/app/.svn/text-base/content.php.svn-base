    <div class="index_content clearfix">
        <form action="<?php echo base_url();?>main/search" method="POST">
        <div>
          <input name="q" type='text' placeholder='请输入要搜索的应用'/>
          <button name="search" class="glyphicon glyphicon-search"></button>
        </div>
      </form>

       <div class="row">
         <div>
            <h2><b><?php echo $name;?></b>统计结果</h2>

            
 <!-- Default panel contents --> 
       <div class="panel panel-default">
       <div class="panel-heading"><?php echo $app_list[0]["name"];?></div>
            <div class="panel-body container">

            <div class="row">
                <div class="col-md-2">
                    <img src="<?php echo $app_list[0]["icon"];?>">
                </div>
                <div class="col-md-4">
                    <strong>开发者：</strong><span><?php echo $app_list[0]["company"];?></span><br/>
                    <strong>类别：</strong><span><?php echo $app_list[0]["classes"];?></span><br/>
                    <strong>语言：</strong><span><?php echo $app_list[0]["language"];?></span>
                </div>
               <div class="col-md-4">
                    <strong>版本：</strong><span><?php echo $app_list[0]["version"];?></span><br/>
                    <strong>大小：</strong><span><?php echo $app_list[0]["size"];?>M</span><br/>
                    <strong>更新时间：</strong><span><?php echo $app_list[0]["update_time"];?></span>
                </div> 
            </div>
            
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
                        <td><?php echo $app["classes"]?></td>
                        <td><?php echo $app["from_plat"]?></td>
                        <td><?php echo $app["download_times"]?></td>
                        </tr>
                    <?php } ?> 
                </tbody>
            </table>
          </div>
        </div>

        <!-- Default panel contents -->
        <div class="panel panel-default">
          <div class="panel-heading">总下载</div>
          <div class="panel-body">
             <div id="container" style="min-width: 310px; height: 400px; 
                 margin: 0 auto"></div>
          </div>
        </div>
        <!-- Default panel contents , end-->

       <!-- Default panel contents -->
        <div class="panel panel-default">
          <div class="panel-heading">每日下载量趋势</div>
          <div class="panel-body">
            <div id="container_down_trend" style="min-width: 310px; 
                height: 400px; margin: 0 auto"></div>
          </div>
        </div>
        <!-- Default panel contents , end-->

        </div>    <!-- div -->
        </div> <!-- row -->
      
    </div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://app.ruyiso.com/resource/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://app.ruyiso.com/resource/js/bootstrap.min.js"></script>
    <script src="http://tongji.ruyiso.com/resource/js/highcharts.js"></script>
    <script type="text/javascript">
    $(function(){
        $('#container').highcharts(<?php echo $download;?>);
        $('#container_down_trend').highcharts(<?php echo $download_trend;?>); 
     });
	  
	  
    </script>
