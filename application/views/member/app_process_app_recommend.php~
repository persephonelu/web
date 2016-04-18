        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">      


    <h3 class="page-header">竞品app </h3>
              

            <table width="100%" border="0" class="customers">
                <tr>
                  <th width="9%">序号</th>
                  <th>app名</th>
                  <th>相关度</th>
                </tr>
                <?php $i = $start ; foreach ($app_list as $item) { ?>
                <tr>
                  <td><h3><?php echo $i; ?></h3></td>
                  <td>
                  <a href="<?php echo base_url() . "user_app_process/keywords_manage?app_id=" . $item["app_id"]. "&nav=keywords_optimal" ?>" target="_blank">
                    <span class="c2"><?php echo $item["name"] ?></span></td>
                    </a>
                    <td><?php echo $item["score"] ?></td> 
                </tr>
                <?php $i++;} ?>
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
