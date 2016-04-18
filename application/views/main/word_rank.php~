<!-- Page Content -->
  <div id="page-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <h3 class="page-header"><span class="text-primary"><?php echo $category; ?> </span>类别热门关键词 </h3>
          
          <div class="row" id="form_area">
          
            <div class="col-lg-8">
            <form role="form" method="post" action="<?php echo base_url();?>search_word?nav=word" class="form-inline">
                <div class="input-group">
                  <input type="text" class="form-control" name="q" placeholder="搜索关键词..."  value="">
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
                            <li><a href="<?php echo base_url()."word?c=" . $item["ori_classes"] ?>&nav=word" >
                            <?php echo $item["ori_classes"]?>
                            </a></li>
                       <?php } ?>
                      </li>
                    </ul>
                 <li class="divider"></li>
 
                     <?php foreach ( $category_list as $item ) { ?> 
                    <li><a href="<?php echo base_url()."word?c=" . $item["ori_classes"] ?>&nav=word" >
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
                                <th>搜索词</th>
                                <th title="搜索指数反映每天搜索
的次数多少">搜索指数   <span class="glyphicon glyphicon-question-sign text-info"></span></th>
                                <th>搜索结果数</th>
                                <th>第1名APP</th>
                              </tr>
                          <?php $index=$start+1; foreach ( $keywords  as $item ) { ?>
                            <tr>
                                <th><?php echo $index?></th>
                                <td><span class="c2"><?php echo $item['word']?></span></td>
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
        <!-- /.col-lg-12 --> 
      </div>
      <!-- /.row --> 
    </div>
    <!-- /.container-fluid --> 
  </div>
  <!-- /#page-wrapper -->  
