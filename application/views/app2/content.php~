    <div class="container">
     <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>">首页</a></li>
        <li><a href="<?php echo base_url();?>main/rank">统计排行</a></li>
        <li><a href="#">应用详情</a></li>
	</ol>
    	<div class="searchResultList">
	         <table width="100%" border="0">
              <tr>
          <td width="9%" rowspan="2"><img src="<?php echo $app_list["icon"];?>" width="72" height="72" class="sImg" alt="img"></td>
        
        <td width="77%"><span class="sRTitle c3">
        <a href="<?php echo $app_list["download_url"];?>" target="_blank">
        <?php echo $app_list["name"];?></a></span> <span class="db sRBtn"><?php echo $app_list["ori_classes"];?></span> </td>
         <td width="8%"><a href="javascript:;" class="db sRBtn">关注</a></td>
              </tr>
              <tr>
              </tr>
            </table>	

        </div>
           <div class="searchResultCon">
            <ul class="nav nav-tabs" role="tablist">
              <li class="active"><a href="#one1" role="tab" data-toggle="tab">详情</a></li>
              <li><a href="#one2" role="tab" data-toggle="tab">下载趋势</a></li>
              <!--
              <li><a href="#one3" role="tab" data-toggle="tab">日下载</a></li>
              <li><a href="#one4" role="tab" data-toggle="tab">社区评论</a></li>
             -->
             </ul>
        	<div class="tab-content searchTabCon">
              <div class="tab-pane active" id="one1">
              	<div class="searchDetail">
                           <div class="sdBor"><h3>简介</h3></div>
                            <div class="sdBon"><?php echo $app_list["brief"];?></div>

                            <div class="sdBor"><h3>当前版本</h3></div>
                            <div class="sdBon">
                             <strong>版本：</strong><span><?php echo $app_list["version"];?></span><br/>
                             <strong>开发者：</strong><span><?php echo $app_list["company"];?></span><br/>
                             <strong>更新时间：</strong><span><?php echo $app_list["update_time"];?></span>
                            </div> 
                </div>
              </div>
               <div class="tab-pane" id="one2">
                <div class="searchDetail">
                <div id="container_down_trend" style="width:100%;height: 400px; margin: 0 auto"></div>   
                </div>
              </div>             

            <div class="tab-pane" id="one3">
              	<div class="searchDetail">
                        <table width="100%" border="0" class="customers">
                             <tr>
                                <th>名称</th>
                                <th>类别</th>
                                <th>市场</th>
                                <th>下载量</th>
                            </tr>
                        <?php foreach ($app_list as $app) {?>
                            <tr>
                            <td><?php echo $app["name"]?></td>
                            <td><?php echo $app["classes"]?></td>
                            <td><?php echo $app["from_plat"]?></td>
                            <td><?php echo $app["download_times"]?></td>
                            </tr>
                        <?php } ?>
                       </table> 
                </div>
              </div>
            <div class="tab-pane" id="one4">无内容</div>
            </div>
        </div>
    </div>
