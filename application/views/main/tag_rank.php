<!-- Page Content -->
  <div id="page-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <h3 class="page-header"><span class="text-primary"><?php echo $category; ?> </span>类别热门用户标签 </h3>
          
          <div class="row" id="form_area">
            <div class="col-lg-4">
             <div class="dropdown">
              <a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-primary" data-target="#"
               href="javascript:;"> 类别选择 <span class="caret"></span> </a>
               
                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                 <li class="dropdown-submenu"> <a tabindex="-1" href="javascript:;">游戏子类</a>
                    <ul class="dropdown-menu">
                       <?php foreach ( $game_category_list as $item ) { ?>
                            <li><a href="<?php echo base_url()."tag?c=" . $item["ori_classes"] ?>&nav=tag" >
                            <?php echo $item["ori_classes"]?>
                            </a></li>
                       <?php } ?>
                      </li>
                    </ul>
                 <li class="divider"></li>
 
                     <?php foreach ( $category_list as $item ) { ?> 
                    <li><a href="<?php echo base_url()."tag?c=" . $item["ori_classes"] ?>&nav=tag" >
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

                        <table class="table table-striped table-bordered">
                            <tr>
                                <th width="9%">序号</th>
                                <th>兴趣标签</th>
                                <th>所有微博用户数估计</th>
                                <th>采样数据中用户数</th>
                              </tr>
                          <?php $index=1; foreach ( $tags  as $item ) { ?>
                            <tr>
                                <th><?php echo $index?></th>
                                <td><?php echo $item['tag']?></td>
                                <td><?php echo $item['weight']?></td>
                                <td><?php echo $item['freq']?></td>
                              </tr>
                            <?php $index++;} ?> 
                        </table> 
                        
            </div>
        <!-- /.col-lg-12 --> 
      </div>
      <!-- /.row --> 
    </div>
    <!-- /.container-fluid --> 
  </div>
  <!-- /#page-wrapper -->  
