    <div class="container">
    	<ol class="breadcrumb">
                  <li><a href="<?php echo base_url()?>">首页</a></li>
                  <li><a href="<?php echo base_url()?>aso">搜索优化</a></li>
		</ol>
        <div class="searchResultCon">
            <ul class="nav nav-tabs" role="tablist">
            <li>
        <a href="<?php echo base_url()?>aso" role="tab" data-toggle="tab">热词排行</a>
            </li>
            <li>
            <a href="<?php echo base_url()?>aso/suggestion" role="tab" data-toggle="tab">联想词</a>
            </li>
            <li>
            <a href="<?php echo base_url()?>aso/recommend" role="tab" data-toggle="tab">关键词判别</a>
            </li>
            <li class='active'><a href="#one4" role="tab" data-toggle="tab">关键词推荐</a></li>
            <!--
            <li><a href="#one5" role="tab" data-toggle="tab">趋势查询</a></li>
            -->
            </ul>
        	<div class="tab-content searchTabCon">
              <div class="tab-pane active" id="one4">
              	<div class="searchDetail">
                        <p>
                          <strong class="f16">建议：</strong><br />
                          <span class="glyphicon glyphicon-hand-right mr1"></span>输入你的app的关键词列表，为你推荐相关的关键词 (输入关键词之间用逗号分开)；
<br>
                          <span class="glyphicon glyphicon-hand-right mr1"></span>一般而言，"搜索热度"高，"搜索结果数"少的词适合作为app的关键词;；
                        <br/>
                        <span class="glyphicon glyphicon-hand-right mr1"></span>关键词推荐主要根据app之间的相似关系进行推荐，推荐结果按照相关程度排序；
                        </p>

            <form action="<?php echo base_url();?>aso_predict" method="get">            
            <div class="searchBox">
            <div class="input-group">
            <input type="text" name="q" value="<?php if (isset($query)) {echo $query;}?>" class="form-control">
          <span class="input-group-btn">
            <button type="submit" class="btn btn-default">搜索</button>
          </span>
        </div>
        </div>
        </form>
            <br /><br />
                        <table width="100%" border="0" class="customers" id="report">
					          <tr>
                                <th width="7%">排名</th>
                                <th width="11%">关键词</th>
                                <th title="搜索指数反映每天搜索
的次数多少" width="11%">搜索指数   <span class="glyphicon glyphicon-question-sign text-info"></span> </th>
                                <th width="13%">搜索结果数</th>
                                <th width="15%">推荐度</th>
                              </tr>
                              <?php $index=1; foreach ($recommend as $item) {?> 
                               <tr>
                               <td><h3><?php echo $index;?></h3></td>
                               <td><?php echo $item["word"];?></td>
                               <td><?php echo $item["rank"];?></td>
                               <td><?php echo $item["num"];?></td>
                               <td><?php echo $item["recommend_level"];?></td>
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
