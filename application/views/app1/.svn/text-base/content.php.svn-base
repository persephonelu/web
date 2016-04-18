    <div class="container">
    	<div class="searchResultList">
              <table width="100%" border="0">
              <tr>
                <td width="9%" rowspan="2"><img src="<?php echo $app_list[0]["icon"];?>" width="72" height="72" class="sImg" alt="img"></td>
                <td width="77%"><span class="sRTitle"><?php echo $app_list[0]["name"];?></span> <span class="db sRBtn"><?php echo $app_list[0]["classes"];?></span> </td>
                <td width="8%"><a href="javascript:;" class="db sRBtn">关注</a></td>
              </tr>
              <tr>
              </tr>
            </table>
        </div>
    	<div class="searchResultCon">
        	<ul id="tabHd" class="searchTab">
                <li class="on"><a href="javascript:;">详情</a></li>
                <li><a href="javascript:;">总下载</a></li>
                <li><a href="javascript:;">日下载</a></li>
                <li><a href="javascript:;">社区评论</a></li>
            </ul>
            <div id="tabBd" class="searchTabCon">
            	<ul>
                	<li>
                    	<div class="searchDetail">
                        	<div class="sdBor"><h3>简介</h3></div>
                            <div class="sdBon"><?php echo $app_list[0]["brief"];?></div>
                            
                            <div class="sdBor"><h3>当前版本</h3></div>
                            <div class="sdBon">
                             <strong>版本：</strong><span><?php echo $app_list[0]["version"];?></span><br/>
                             <strong>开发者：</strong><span><?php echo $app_list[0]["company"];?></span><br/>
                             <strong>更新时间：</strong><span><?php echo $app_list[0]["update_time"];?></span>
                            </div>
                            
                        </div>
                    </li>
                </ul>
                <ul class="dn">
                	<li>
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
                    </li>
                </ul>
                <ul class="dn">
                	<li>	
                    	<div class="searchDetail">
                               <div id="container_down_trend" style="width:100%;height: 400px; margin: 0 auto"></div>
                        </div>
                    </li>
                </ul>
                <ul class="dn">
                	<li>	
                    	<div class="noSearchResult tc">无内容！</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
