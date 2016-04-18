    <div class="container">
    	<div class="searchResultCon">
        <p class="sTips">"<?php echo $select_day ?>" 热搜榜</p>
            <ul id="tabHd" class="searchTab">
                <li class="on"><a href="javascript:;">热词词库</a></li>
                <li><a href="javascript:;">app选词优化</a></li>
            </ul>

            <div id="tabBd" class="searchTabCon">
            	<ul>
                    <li>
                        <?php $index=$start+1; foreach ( $keywords  as $item ) { ?>
                      <div class="searchResultList">
                        	<table width="100%" border="0">
                              <tr>
                              <td width="6%" rowspan="2" class="sRNum tc fb"><?php echo $index?></td>
                                <td width="77%"><span class="sRTitle">
                                <a href="#" target="blank">
                                    <?php echo $item['app_name']?></a>
                                </span> 
                            </td>
                              </tr>
                              <tr>
                                <td>热度：<?php echo $item['hot_index']?> </td>
                              </tr>
							</table>
                        </div>
                        
                 <?php $index++;} ?>
                     <?php echo $turn_page;?>
                    </li>
                </ul>
                <ul class="dn">
                	<li>
                        <div class="searchDetail">
                        
                            <div class="sdBor"><h3>app标题和关键词</h3></div>
                            <br/>
                            <strong>标题：</strong><span><br/>
                            <div class="sdBon"><input type="text" name="t"/></div>
                            <strong>关键词列表(逗号分开)：</strong><span><br/>
                            <div class="sdBon"><input type="text" name="k"/></div>
                             
                            <div class="sdBor"><h3>app选词分析</h3></div>
                            <div class="sdBon">
                            <table width="100%" border="0" class="customers">
                             <tr>
                                <th>词</th>
                                <th>热度</th>
                                <th>搜索结果数</th>
                                <th>是否适合</th>
                            </tr>
                            </table>
                            </div>
                            
                            <div class="sdBor"><h3>app选词推荐</h3></div>
                            <div class="sdBon">
                            <table width="100%" border="0" class="customers">
                             <tr>
                                <th>词</th>
                                <th>热度</th>
                                <th>搜索结果数</th>
                                <th>是否适合</th>
                            </tr>
                            </table>
                            </div>

                        </div>
                        
                        
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
