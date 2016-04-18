<!-- Page Content -->
  <div id="page-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <h3 class="page-header"><span class="text-primary"><?php echo $category; ?> </span>类别排行榜 </h3>
          
          <div class="row" id="form_area">
          
            <div class="col-lg-8">
            <form role="form" method="get" action="<?php echo base_url()?>search" class="form-inline">
                <div class="input-group">
                  <input type="text" class="form-control" name="q" placeholder="搜索app..."  value="">
                </div>
                <button type="submit" class="btn btn-primary">搜索</button>
              </form>
            </div>
            
            <div class="col-lg-4">
              <div class="dropdown"> 
              <a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-primary" data-target="#"
               href="javascript:;"> 类别选择 <span class="caret"></span> </a>
               
                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                 <li class="dropdown-submenu"> <a tabindex="-1" href="javascript:;">游戏子类</a>
                    <ul class="dropdown-menu">
                       <?php foreach ( $game_category_list as $item ) { ?>
                            <li><a href="<?php echo base_url()."rank?c=" . $item["ori_classes"] ?>&nav=app" >
                            <?php echo $item["ori_classes"]?>
                            </a></li>
                       <?php } ?>
                      </li>
                    </ul>
                 <li class="divider"></li>
 
                     <?php foreach ( $category_list as $item ) { ?> 
                    <li><a href="<?php echo base_url()."rank?c=" . $item["ori_classes"] ?>&nav=app" >
                       <?php echo $item["ori_classes"]?>
                    </a></li>
                    <?php } ?>
                   </li>
                </ul>
              </div>
            </div>
             
          </div>
          </br>
          </br>
          
          <!-- /#from_area -->

                      <div class="searchDetail" id="table_area">
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
                            <img src="<?php echo $docs["topfreeapplications"][$index]['icon']?>" 
                            class="img-rounded mr" alt="" />
        <a href="<?php echo base_url().'content/index/?app_id='.$docs["topfreeapplications"][$index]['app_id'];?>" 
    target="_blank">
                            <?php echo $docs["topfreeapplications"][$index]['name'];?>
        </a></span><span class="c1"></span></td>
            
                                    <td>
            <span class="c2">
            <img src="<?php echo $docs["toppaidapplications"][$index]['icon']?>" class="img-rounded mr" alt="" />
    <a href="<?php echo base_url().'content/index/?app_id='.$docs["toppaidapplications"][$index]['app_id'];?>"
     target="_blank">
 <?php echo $docs["toppaidapplications"][$index]['name'];?></a></span><span class="c1"></span></td>
    <td>
    <span class="c2">
    <img src="<?php echo $docs["topgrossingapplications"][$index]['icon']?>" class="img-rounded mr" alt="" />
    <a href="<?php echo base_url().'content/index/?app_id='.$docs["topgrossingapplications"][$index]['app_id'];?>" 
    target="_blank">       
    <?php echo $docs["topgrossingapplications"][$index]['name'];?></a></span><span class="c1"></span></td>
                              </tr>
                            <?php } ?>
                         </table>
                     <span  class="loadMore db"><?php echo $turn_page;?></span> 

        </div>
        <!-- /.col-lg-12 --> 
      </div>
      <!-- /.row --> 
    </div>
    <!-- /.container-fluid --> 
  </div>
  <!-- /#page-wrapper -->  
