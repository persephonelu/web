    <div class="container">
     <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>">首页</a></li>
        <li><a href="<?php echo base_url();?>main/rank">统计排行</a></li>
        <li><a href="#">应用详情</a></li>
	</ol>
    	<div class="searchResultList">
	         <table width="100%" border="0">
              <tr>
          <td width="9%" rowspan="2"><img src="<?php echo $app_info["icon"];?>" width="72" height="72" class="sImg" alt="img"></td>
        
        <td width="77%"><span class="sRTitle c3">
        <a href="<?php echo $app_info["download_url"];?>" target="_blank">
        <?php echo $app_info["name"];?></a></span> <span class="db sRBtn"><?php echo $app_info["ori_classes"];?></span> </td>
         <td width="8%"><a href="javascript:;" class="db sRBtn">关注</a></td>
              </tr>
              <tr>
              </tr>
            </table>	

        </div>
           <div class="searchResultCon">
            <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="<?php echo base_url() . "content/id_content?app_id=" 
            . $app_info["app_id"];?>" role="tab" data-toggle="tab">详情</a></li>
              <li><a href="<?php echo base_url() . "content/rank_trend?app_id=" . 
            $app_info["app_id"];?>" role="tab" data-toggle="tab">排名趋势</a></li>
             </ul>
        	<div class="tab-content searchTabCon">
              <div class="tab-pane active" id="one1">
              	<div class="searchDetail">
                           <div class="sdBor"><h3>简介</h3></div>
                            <div class="sdBon"><?php echo $app_info["brief"];?></div>

                            <div class="sdBor"><h3>当前版本</h3></div>
                            <div class="sdBon">
                             <strong>版本：</strong><span><?php echo $app_info["version"];?></span><br/>
                             <strong>开发者：</strong><span><?php echo $app_info["company"];?></span><br/>
                             <strong>更新时间：</strong><span><?php echo $app_info["update_time"];?></span>
                            </div> 
                </div>
              </div>

            </div>
        </div>
    </div>
