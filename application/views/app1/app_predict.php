    <div class="container">
    	<div class="searchResultCon">
        <p class="sTips">"<?php echo $select_day;?>" 热门预测</p>
            <ul id="tabHd" class="searchTab">
                <li class="on"><a href="javascript:;">应用</a></li>
                <!--
                <li><a href="javascript:;">词库</a></li>
                <li><a href="javascript:;">舆情</a></li>
                -->
            </ul>

            <div id="tabBd" class="searchTabCon">
            	<ul>
                    <li>
                        <?php $index=$start+1; foreach ( $docs as $item ) { ?>
                      <div class="searchResultList">
                        	<table width="100%" border="0">
                              <tr>
                              <td width="6%" rowspan="2" class="sRNum tc fb"><?php echo $index?></td>
                                <td width="9%" rowspan="2"><img src="<?php echo $item['icon']?>" width="72" height="72" class="sImg" alt="img"></td>
                                <td width="77%"><span class="sRTitle">
                                <a href="<?php echo base_url().'predict/content/?name='.$item['filter_name'].'&type='.$item['type'];?>" target="blank">
                                    <?php echo $item['name']?> </a>
                                </span> 
                            <span class="db sRBtn"><?php echo $item['ori_classes']?></span> </td>
                              </tr>
                              <tr>
                              <td>潜力值：<?php echo $item['score']?>, 
                                最大下载量估计：<?php echo $item['download_times']?> </td>
                              </tr>
							</table>
                        </div>
                        
                 <?php $index++;} ?>
                    </li>
                </ul>
                <ul class="dn">
                	<li>
                    	<div class="noSearchResult tc">没有搜索结果！</div>
                    </li>
                </ul>
                <ul class="dn">
                	<li>	
                    	<div class="noSearchResult tc">没有搜索结果！</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
