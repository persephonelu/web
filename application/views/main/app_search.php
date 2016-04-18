<!-- Page Content -->
  <div id="page-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <h3 class="page-header">app搜索结果</h3>
          
          <div class="row" id="form_area">
          
            <div class="col-lg-8">
            <form role="form" method="get" action="<?php echo base_url()?>search" class="form-inline">
                <div class="input-group">
                <input type="text" class="form-control" name="q" placeholder=""  value="<?php echo $query ?>">
                </div>
                <button type="submit" class="btn btn-primary">搜索</button>
              </form>
            </div>
             
          </div>
          </br>
          </br>
          
          <!-- /#from_area -->

                       <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="9%">序号</th>
                                <th>app</th>
                                <th>类别</th>
                                <th>下载量</th>
                              </tr>
                              </thead>
                               
                            <tbody>
                            <?php $index=$start+1; foreach ( $docs as $item ) { ?> 
                              <tr>
                              <th class="table_middle"><?php echo $index?></th>
                                <td class="table_middle"><span class="c2">
                                <img src="<?php echo $item['icon']?>" class="img-rounded mr" alt="">
                                <a href="<?php echo base_url()."content/?app_id=".$item['app_id']?>">
                                    <?php echo $item['name']?></a></span></td>
                                <td class="table_middle"><?php echo $item['ori_classes']?></td>
                                <td class="table_middle"><?php echo $item['download_times']?></td>
                              </tr>
                            <?php $index++; } ?> 
                             </tbody>
                             
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
