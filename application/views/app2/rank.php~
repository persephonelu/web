    <div class="container">
    	<ol class="breadcrumb">
            <li><a href="<?php echo base_url();?>">首页</a></li>
            <li><a href="<?php echo base_url();?>main/rank">排行榜</a></li>
        </ol>
        <div class="searchResultCon">
            <ul class="nav nav-tabs" role="tablist">
            <li <?php if ($tab==1) {echo "class='active'";};?>>
            <a href="<?php echo base_url();?>main/rank"  role="tab" data-toggle="tab">总排行榜</a></li>
            <li <?php if ($tab==2) {echo "class='active'";};?>>
            <a href="<?php echo base_url();?>main/rank_new" role="tab" data-toggle="tab">新应用榜</a></li>
            <!--  
                <li><a href="#one3" role="tab" data-toggle="tab">新游戏榜</a></li>
              <li><a href="#one4" role="tab" data-toggle="tab">下载榜</a></li>
            -->
            </ul>
        	<div class="tab-content searchTabCon">
            <div class="tab-pane <?php if ($tab==1) {echo "active";};?>" id="one1">
              	<div class="searchDetail">
                    	<table width="100%" border="0" class="customers">
                              <tr>
                                <th>&nbsp;</th>
                                <th >免费排行</th>
                                <th>付费排行</th>
                                <th>畅销排行</th>
                              </tr>
                              <?php
                                if ($result_num>0) {$max_num = 10;} else {$max_num = 0;}
                                for($index=0;$index<$max_num;$index++ ) { ?>
                              <tr>
                              <td><h3><?php echo $start+$index+1;?></h3></td>
                              <td>
                             <span class="c2">
                            <img src="<?php echo $docs["topfreeapplications"][$index]['icon']?>" class="img-rounded mr" alt="" />
                          <a href="<?php echo base_url().'content/index/?name='.$docs["topfreeapplications"][$index]['name'];?>" target="_blank">
                            <?php echo $docs["topfreeapplications"][$index]['name'];?>
        </a></span><span class="c1"></span></td>
                              
        <td>
            <span class="c2">
            <img src="<?php echo $docs["toppaidapplications"][$index]['icon']?>" class="img-rounded mr" alt="" />
    <a href="<?php echo base_url().'content/index/?name='.$docs["toppaidapplications"][$index]['name'];?>" target="_blank">
 <?php echo $docs["toppaidapplications"][$index]['name'];?></a></span><span class="c1"></span></td>
    <td>
    <span class="c2">    
    <img src="<?php echo $docs["topgrossingapplications"][$index]['icon']?>" class="img-rounded mr" alt="" />
    <a href="<?php echo base_url().'content/index/?name='.$docs["topgrossingapplications"][$index]['name'];?>" target="_blank">       <?php echo $docs["topgrossingapplications"][$index]['name'];?></a></span><span class="c1"></span></td>
                              </tr>
                            <?php } ?>
                        </table>
                        <!--
                        <a class="loadMore db" href="javascript:;">加载更多..</a>		
                        -->
                        <span  class="loadMore db"><?php echo $turn_page;?></span>
					</div>
              </div>
              <div class="tab-pane <?php if ($tab==2) {echo "active";};?>" id="one2">
                       <div class="searchDetail">
                        <table width="100%" border="0" class="customers">
                              <tr>
                                <th>&nbsp;</th>
                                <th>新应用总榜</th>
                                <th>免费新应用榜</th>
                                <th>付费新应用榜</th>
                              </tr>
                            <?php
                                if ($result_num_new>0) {$max_num = 10;} else {$max_num = 0;}
                                for($index=0;$index<$max_num;$index++ ) { ?> 
                            <tr>
                            <td><h3><?php echo $start+$index+1;?></h3></td>

    <td>
        <span class="c2">
        <img src="<?php echo $docs["newapplications"][$index]['icon']?>" class="img-rounded mr" alt="" />
 <a href="<?php echo base_url().'content/index/?name='.$docs["newapplications"][$index]['name'];?>" target="_blank"> 
<?php echo $docs["newapplications"][$index]['name'];?>
</a></span><span class="c1"></span></td>
                             
 <td><span class="c2"> <img src="<?php echo $docs["newfreeapplications"][$index]['icon']?>" class="img-rounded mr" alt="" />
 <a href="<?php echo base_url().'content/index/?name='.$docs["newfreeapplications"][$index]['name'];?>" target="_blank"> 
<?php echo $docs["newfreeapplications"][$index]['name'];?></a></span><span class="c1"></span></td>

    <td><span class="c2"><img src="<?php echo $docs["newpaidapplications"][$index]['icon']?>" class="img-rounded mr" alt="" />
 <a href="<?php echo base_url().'content/index/?name='.$docs["newpaidapplications"][$index]['name'];?>" target="_blank"> 
<?php echo $docs["newpaidapplications"][$index]['name'];?></a></span><span class="c1"></span></td>
                              
                            </tr>
                            <?php } ?>
                        </table>
                        <!--
                        <a class="loadMore db" href="javascript:;">加载更多..</a>
                        -->
                        <span  class="loadMore db"><?php echo $turn_page;?></span>
                    </div>
                        
            </div>
              <div class="tab-pane" id="one3">
              	<div class="searchDetail">
                      <a class="loadMore db" href="javascript:;">加载更多..</a>		
                 </div>
              </div>
              <div class="tab-pane" id="one4">无内容</div>
            </div>
        </div>
    </div>
