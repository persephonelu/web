    <div class="container">
    	<ol class="breadcrumb">
                  <li><a href="<?php echo base_url()?>">首页</a></li>
                  <li><a href="<?php echo base_url()?>user_photo">用户画像</a></li>
                  <li><a href="<?php echo base_url() . "user_photo?c=". $ori_classes?>">
                    <?php echo $ori_classes ?>
                  </a></li>
		</ol>
        <div class="searchResultCon">
            <ul class="nav nav-tabs" role="tablist">
            <?php foreach ($category_list as $item) { ?>
            <li <?php if ( $item==$ori_classes) { echo 'class="active"';} ?>>
            <a href="<?php echo base_url()."user_photo?c=". $item ?>" role="tab" data-toggle="tab">
                    <?php echo $item;?>
                </a>
            </li>
            <?php } ?>
            </ul>
        	<div class="tab-content searchTabCon">
              <div class="tab-pane active" id="one4">
              	<div class="searchDetail">
                        <p>
                          <strong class="f16">提示：</strong><br />
                          <span class="glyphicon glyphicon-hand-right mr1"></span>用户标签: 使用该类别app的微博用户"兴趣标签"；
<br>

                        <table width="100%" border="0" class="customers" id="report">
					          <tr>
                                <th width="7%">排名</th>
                                <th width="11%">用户标签</th>
                                <th title="该权重表示使用该标签
用户的多少" width="11%">标签权重   <span class="glyphicon glyphicon-question-sign text-info"></span> </th>
                                <th width="13%">收录数</th>
                              </tr>
                              <?php $index=1; foreach ($tag_list as $item) {?> 
                               <tr>
                               <td><h3><?php echo $index;?></h3></td>
                               <td><?php echo $item["tag"];?></td>
                               <td><?php echo $item["weight"];?></td>
                               <td><?php echo $item["freq"];?></td>
                              </tr>	
                             <?php $index++;} ?>
                        </table>
                        <!--
                        <div class="mt10 fr">
                              <ul class="pagination pagination-sm">
                                <li><a href="#">»</a></li>
                              </ul>
                    </div> -->
                </div>
              </div>
              
    
        <div class="tab-pane" id="one5">
              	<div class="searchDetail">
                              <table width="100%" border="0" class="customers">
                              <tr>
                                <th width="7%">序号</th>
                                <th width="11%">关键词</th>
                                <th width="6%">排名</th>
                                <th width="11%">搜索指数</th>
                                <th width="13%">搜索结果数</th>
                                <th width="15%">优化难度</th>
                                <th width="12%">最近30天趋势</th>
                                <th width="11%">搜索研究</th>
                                <th width="14%">操作</th>
                              </tr>
                              <tr>
                              	<td><h3>1</h3></td>
                                <td>QQ</td>
                                <td>1</td>
                                <td>23</td>
                                <td>456546</td>
                                <td>8</td>
                                <td><img src="http://placehold.it/120x25" alt="" /></td>
                                <td><button class="btn btn-default btn-xs sRBtn" type="button">查看</button></td>
                                <td><button class="btn btn-primary btn-xs" type="button">收藏</button></td>
                              </tr>
                              <tr>
                              	<td><h3>2</h3></td>
                                <td>QQ HD</td>
                                <td>3</td>
                                <td>24</td>
                                <td>45645</td>
                                <td>6</td>
                                <td><img src="http://placehold.it/120x25" alt="" /></td>
                                <td><button class="btn btn-default btn-xs sRBtn" type="button">查看</button></td>
                                <td><button class="btn btn-default btn-xs sRBtn" type="button">取消收藏</button></td>
                              </tr>
                              <tr>
                              	<td><h3>3</h3></td>
                                <td>QQ</td>
                                <td>1</td>
                                <td>23</td>
                                <td>456546</td>
                                <td>8</td>
                                <td><img src="http://placehold.it/120x25" alt="" /></td>
                                <td><button class="btn btn-default btn-xs sRBtn" type="button">查看</button></td>
                                <td><button class="btn btn-primary btn-xs" type="button">收藏</button></td>
                              </tr>
                              <tr>
                              	<td><h3>4</h3></td>
                                <td>QQ HD</td>
                                <td>3</td>
                                <td>24</td>
                                <td>45645</td>
                                <td>6</td>
                                <td><img src="http://placehold.it/120x25" alt="" /></td>
                                <td><button class="btn btn-default btn-xs sRBtn" type="button">查看</button></td>
                                <td><button class="btn btn-default btn-xs sRBtn" type="button">取消收藏</button></td>
                              </tr>
                              <tr>
                              	<td><h3>5</h3></td>
                                <td>天天连萌</td>
                                <td>1</td>
                                <td>23</td>
                                <td>456546</td>
                                <td>8</td>
                                <td><img src="http://placehold.it/120x25" alt="" /></td>
                                <td><button class="btn btn-default btn-xs sRBtn" type="button">查看</button></td>
                                <td><button class="btn btn-primary btn-xs" type="button">收藏</button></td>
                              </tr>
                              <tr>
                              	<td><h3>6</h3></td>
                                <td>QQ HD</td>
                                <td>3</td>
                                <td>24</td>
                                <td>45645</td>
                                <td>6</td>
                                <td><img src="http://placehold.it/120x25" alt="" /></td>
                                <td><button class="btn btn-default btn-xs sRBtn" type="button">查看</button></td>
                                <td><button class="btn btn-default btn-xs sRBtn" type="button">取消收藏</button></td>
                              </tr>
                              <tr>
                              	<td><h3>7</h3></td>
                                <td>天天飞车</td>
                                <td>1</td>
                                <td>23</td>
                                <td>456546</td>
                                <td>8</td>
                                <td><img src="http://placehold.it/120x25" alt="" /></td>
                                <td><button class="btn btn-default btn-xs sRBtn" type="button">查看</button></td>
                                <td><button class="btn btn-primary btn-xs" type="button">收藏</button></td>
                              </tr>
                        </table>
                              <a class="loadMore db" href="javascript:;">加载更多..</a>		
                          </div>
              </div>
			</div>
        </div>
    </div>
<script type="text/javascript">
$('.dropdown-toggle').dropdown()
//tab切换
$(function () {
    $('#myTab a').tab('show')
  })
$(document).ready(function(){
		$("#report tr:odd").addClass("odd");
		$("#report tr:not(.odd)").hide();
		$("#report tr.on").show();
		$("#report tr.odd").click(function(){
			$(this).next("tr").toggle();
			$(this).find(".glyphicon-chevron-down").toggleClass("glyphicon-chevron-up");
		});
	});
</script>
