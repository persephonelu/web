<!-- 类别排行榜 -->
<div class="container">
  <div class="row">
  
    <div class="col-md-12">
         <ul class="list-inline">
            <li>应用类别：</li>
            <?php foreach ( $category_list as $item ) { ?>
                <li><a href="<?php echo base_url()."aso?c=" . $item["ori_classes"] ?>" 
                    id="<?php echo $item["ori_classes"]?>">
                    <?php echo $item["ori_classes"]?></a></li>
            <?php } ?>
           </ul>

        <!-- 游戏子类别 -->
        <ul class="list-inline">
            <li>游戏子类：</li>
            <?php foreach ( $game_category_list as $item ) { ?>
                <li><a href="<?php echo base_url()."aso?c=游戏" . "&gc=" . $item["ori_classes"]
                    ?>" id="<?php echo $item["ori_classes"]?>">
                    <?php echo $item["ori_classes"]?></a></li>
            <?php } ?>
           </ul>         
     </div>
     
     <div class="col-md-12">
                       <table width="100%" border="0" class="customers">
                              <tr>
                                <th width="9%">序号</th>
                                <th>搜索词</th>
                                <th title="搜索指数反映每天搜索
的次数多少">搜索指数   <span class="glyphicon glyphicon-question-sign text-info"></span></th>
                                <th>搜索结果数</th>
                                <th>第1名APP</th>
                              </tr>
                            <?php $index=$start+1; foreach ( $keywords  as $item ) { ?>
                            <tr>
                                <td><h3><?php echo $index?></h3></td>
                                <td><?php echo $item['word']?></td>
                                <td><?php echo $item['rank']?></td>
                                <td><?php echo $item['num']?></td>
                                <td><span class="c2"><?php echo $item['name']?></span></td>
                              </tr>
                            <?php $index++;} ?>
                        </table>    
     
    <!-- 翻页 -->
            <span class="loadMore db">
                <?php echo $turn_page ?>
            </span>
    <!--/ 翻页 -->
 
     </div> 
 
     </div>
</div>

