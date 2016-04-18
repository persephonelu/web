<!-- 类别排行榜 -->
<div class="container">
  <div class="row">
  
    <div class="col-md-12">
         <ul class="list-inline">
            <li>榜单类型：</li>
            <li><a id="topfreeapplications" href="<?php echo base_url().
                "main/rank?t=topfreeapplications" . "&c=" . $category . "&gc=" . $game_category?>">免费榜</a></li>
            <li><a id="toppaidapplications" href="<?php echo base_url().
                "main/rank?t=toppaidapplications" . "&c=" . $category . "&gc=" . $game_category ?>">付费榜</a></li>
            <li><a id="topgrossingapplications" href="<?php echo base_url().
                "main/rank?t=topgrossingapplications" . "&c=" . $category ."&gc=" . $game_category?>">畅销榜</a></li>
           </ul>
 
         <ul class="list-inline">
            <li>应用类别：</li>
            <?php foreach ( $category_list as $item ) { ?>
                <li><a href="<?php echo base_url()."main/rank?t=" . 
                    $type . "&c=" . $item["ori_classes"] ?>" 
                    id="<?php echo $item["ori_classes"]?>">
                    <?php echo $item["ori_classes"]?></a></li>
            <?php } ?>
           </ul>

        <!-- 游戏子类别 -->
        <ul class="list-inline">
            <li>游戏子类：</li>
            <?php foreach ( $game_category_list as $item ) { ?>
                <li><a href="<?php echo base_url()."main/rank?t=" . 
                    $type . "&c=游戏" . "&gc=" . $item["ori_classes"]
                    ?>" id="<?php echo $item["ori_classes"]?>">
                    <?php echo $item["ori_classes"]?></a></li>
            <?php } ?>
           </ul>         
     </div>
     
     <div class="col-md-12">
       
      <?php $i=$start+1; foreach ($app_list as $item) { ?>
       <div class="searchResultList">
        <table width="100%" border="0">
          <tr>
          <td width="6%" rowspan="2" class="sRNum tc fb"><?php echo $i;?></td>
          <td width="9%" rowspan="2"><img src="<?php echo $item["icon"];?>" height="72" class="sImg" alt="img"></td>
          <td width="80%"><span class="sRTitle c2">
            <a href="<?php echo base_url() . "content/id_content?app_id=". $item["app_id"];?>" target="_blank"><?php echo $item["name"];?></a>
            </span>
          <button type="button" class="btn btn-default btn-xs sRBtn"><?php echo $item["ori_classes"];?></button>
    <?php echo $item["download_times"];?>下载</td>
            <td width="5%"></td>
          </tr>
          <tr>
            <td> 来源: appstore </td>
          </tr>
        </table>
      </div>
      <?php  $i++;} ?>
   
     <!-- 翻页 -->
            <span class="loadMore db">
                <?php echo $turn_page ?>
            </span>
    <!--/ 翻页 -->
 
     </div> 
 
     </div>
</div>

