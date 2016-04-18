    <div class="container">
     <ol class="breadcrumb">
                  <li><a href="<?php echo base_url();?>">首页</a></li>
                  <li><a href="<?php echo base_url();?>main/search">app搜索</a></li>
                  <li class="active"><?php echo $query;?></li>
        </ol>
        <p class="searchResultTips">"<?php echo $query;?>"的搜索结果</p>
        <div class="searchResultCon">
            <ul class="nav nav-tabs" role="tablist">
              <li class="active"><a href="#one1" role="tab" data-toggle="tab">应用</a></li>
              <!--
              <li><a href="#one2" role="tab" data-toggle="tab">词库</a></li>
              <li><a href="#one3" role="tab" data-toggle="tab">舆情</a></li>
              -->
            </ul>
        	<div class="tab-content searchTabCon">
              <div class="tab-pane active" id="one1">
                        <?php $index=$start+1; foreach ( $docs as $item ) { ?>
                      <div class="searchResultList">
                        	<table width="100%" border="0">
                              <tr>
                              <td width="6%" rowspan="2" class="sRNum tc fb"><?php echo $index?></td>
                              <td width="9%" rowspan="2"><img src="<?php echo $item['icon']?>" width="72" height="72" class="sImg" alt="img"></td>
                              <td width="80%"><span class="sRTitle c2">
                              <a href="<?php echo base_url()."content/id_content?app_id=". $item["app_id"];?>" target="_blank">
                             <?php echo $item['name']?> 
                            </a></span> <button type="button" class="btn btn-default btn-xs sRBtn"><?php echo $item['ori_classes']?></button> 
                                <?php echo $item['download_times']?>下载</td>
                                <!--
                                <td width="5%"><button type="button" class="btn btn-primary btn-xs">关注</button></td>
                                --> 
                             </tr>
                              <tr>
                                <!--
                                <td><span class="db sRIcon"><i class="sRImg db"><img src="images/icon1.png" alt="img"></i>12</span><span class="db sRIcon"><i class="sRImg db"><img src="images/icon2.png" alt="img"></i>8</span><span class="db sRIcon"><i class="sRImg db"><img src="images/icon3.png" alt="img"></i>9</span><span class="stars star5"></span><span class="grade fb">3.7分</span>(4555评论)</td>
                                <td><button type="button" class="btn btn-primary btn-xs">对比</button></td>
                                -->  
                                <td> 来源: <?php echo $item['from_plat']?> </td>
                            </tr>
							</table>
                        </div>
                        <?php $index++;} ?>
                     <span  class="loadMore db"><?php echo $turn_page;?></span>
              </div>


              <div class="tab-pane" id="one2">
              		<div class="searchDetail">
                   		  <div class="sdBor">
                          <h3 class="fl db">词库数量<span class="num">680</span></h3>
                          <select name="" class="fr select db">
                    		  <option value="1">Appstore</option>
                    		  <option value="2">应用宝</option>
                    		  <option>91手机助手</option>
                    		  <option>360手机助手</option>
                    		  <option>豌豆荚</option>
                   		  </select>
                          </div>
                          <div class="sdBon">
                          	<div class="searchDetailList">
                          	<table width="100%" border="0" class="customers">
                              <tr>
                                <th>序号</th>
                                <th>关键词</th>
                                <th>指数 ↓</th>
                                <th>排名</th>
                                <th>搜索结果数</th>
                                <th>趋势</th>
                              </tr>
                              <tr>
                                <td>1</td>
                                <td>QQ</td>
                                <td>200000</td>
                                <td>2</td>
                                <td>24113</td>
                                <td class="c1">↑</td>
                              </tr>
                              <tr>
                                <td>2</td>
                                <td>QQ</td>
                                <td>200000</td>
                                <td>2</td>
                                <td>24713</td>
                                <td class="c1">↑</td>
                              </tr>
                              <tr>
                                <td>3</td>
                                <td>应用宝</td>
                                <td>200000</td>
                                <td>2</td>
                                <td>213</td>
                                <td class="c1">↑</td>
                              </tr>
                              <tr>
                                <td>4</td>
                                <td>豌豆荚</td>
                                <td>200000</td>
                                <td>2</td>
                                <td>413</td>
                                <td class="c1">↑</td>
                              </tr>
                              <tr>
                                <td>5</td>
                                <td>QQ</td>
                                <td>200000</td>
                                <td>2</td>
                                <td>213</td>
                                <td class="c1">↑</td>
                              </tr>
						</table>
                        <div class="mt10 fr">
                              <ul class="pagination pagination-sm">
                                <li><a href="#">«</a></li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#">»</a></li>
                              </ul>
    				</div></div>
                         </div>
                        </div>
              </div>
              <div class="tab-pane" id="one3">
              		<div class="searchDetail">
                    		<div class="sdBor"><h3>舆情</h3></div>
                            <div class="sdBon">
                            	<p>预估搜索曝光量：20万</p>
                            </div>
                            <div class="sdBor"><h3>媒体指数</h3></div>
                            <div class="sdBon">
                            	<p>微信公众号20万</p>
                            </div>
                            <div class="sdBor"><h3>社区指数</h3></div>
                            <div class="sdBon">
                            	<p>搜索结果出最多7条；</p>
                            </div>
						</div>
              </div>
            </div>
        </div>
    </div>
