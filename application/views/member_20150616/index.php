        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">

    <h3 class="page-header">我的app列表 <small>(点击app标题，进入详情页)</small> </h3>
      <?php $i=$start; foreach ($app_list as $item) { ?>
       <div class="searchResultList">
        <table width="100%" border="0">
          <tr>
          <td width="6%" rowspan="2" class="sRNum tc fb"><?php echo $i;?></td>
          <td width="9%" rowspan="2"><img src="<?php echo $item["icon"];?>" height="72" class="sImg" alt="img"></td>
          <td width="80%"><span class="sRTitle c2"> 
            <a href="<?php echo base_url()."user_app/app_process_app_info?app_id=".$item["app_id"] . "&nav=app_process_app_info";?>"><?php echo $item["name"];?></a>
            </span>
          <button type="button" class="btn btn-default btn-xs sRBtn"><?php echo $item["ori_classes"];?></button>
    <?php echo $item["download_times"];?>下载</td>
            <td width="5%"><button type="button" class="btn btn-default btn-xs">
            <a href="<?php echo base_url()."user_app/del_app_by_id?app_id=".$item["app_id"];?>">删除</a>
              </button></td>
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
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
