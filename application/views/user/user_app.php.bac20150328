        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">

    <h3 class="page-header">我的app管理 <small>(点击app标题，进入详情页)</small> </h3>
                   
                   <!-- app list panel -->        
                   <div class="panel panel-default">
                        <div class="panel-heading">我的应用列表</div>
                        <div>
    
                    <table class="table table-striped">
                            <thead>
                            <tr>
                                <th width="9%">序号</th>
                                <th>app</th>
                                <th>类别</th>
                                <th>下载量</th>
                                <th>删除</th>
                              </tr>
                              </thead> 
                             <tbody>
                            <?php $index=$start; foreach ( $app_list as $item ) { ?>
                              <tr>
                              <th class="table_middle"><?php echo $index?></th>
                                <td class="table_middle"><span class="c2">
                                <img src="<?php echo $item['icon']?>" class="img-rounded mr" alt="">
                                <a href="<?php echo base_url()."user_app/app_process_app_info?app_id=".$item["app_id"] . "&nav=app_process_app_info";?>">
                                    <?php echo $item['name']?></a></span></td>
                                <td class="table_middle"><?php echo $item['ori_classes']?></td>
                                <td class="table_middle"><?php echo $item['download_times']?></td>
                                <td class="table_middle"><a href="<?php echo base_url()."user_app/del_app_by_id?app_id=".$item["app_id"];?>">删除</a></td>  
                            </tr>
                            <?php $index++; } ?>
                             </tbody>

                        </table>
                     <!-- 翻页 -->
                    <span class="loadMore db">
                        <?php echo $turn_page ?>
                    </span>
                   <!--/ 翻页 -->   
                        </div>
                    </div>
                     <!-- / app list panel -->
                    
                   <div class="panel panel-default">
                        <div class="panel-heading">添加应用</div>
                        <div class="panel-body">
                          
                            <div class="row" id="form_area">
                            <div class="col-lg-8">
                <form role="form" method="get" action="<?php echo base_url()?>user_app/index" class="form-inline">
                    <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="搜索app名称或者app id"
                  value="<?php echo isset($query)?$query:""; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">搜索</button>
              </form>
                </div>

                 </div>
         <!-- /#from_area -->
          </br>

                            <!-- 搜索结果 -->
                           <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="9%">序号</th>
                                <th>app</th>
                                <th>类别</th>
                                <th>下载量</th>
                                <th>添加</th>
                              </tr>
                              </thead>
                          
                        <tbody>
                            <?php $index=$start+1; if ( isset($docs) ) { foreach ( $docs as $item ) { ?>
                              <tr>
                              <th class="table_middle"><?php echo $index?></th>
                                <td class="table_middle"><span class="c2">
                                <img src="<?php echo $item['icon']?>" class="img-rounded mr" alt="">
                                <a href="<?php echo base_url()."content/?app_id=".$item['app_id']?>">
                                    <?php echo $item['name']?></a></span></td>
                                <td class="table_middle"><?php echo $item['ori_classes']?></td>
                                <td class="table_middle"><?php echo $item['download_times']?></td>
                                <td class="table_middle">
            <a href="<?php echo base_url() . "user_app/add_app_by_id?app_id=" . $item["app_id"]?>">添加</a></td>  
                            </tr>
                            <?php $index++; } }?>
                             </tbody>

                        </table>
                     <span  class="loadMore db"><?php echo isset($search_turn_page)?$search_turn_page:"";?></span>

                        </div>
                    </div>
                    <!-- /panel -->

                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
