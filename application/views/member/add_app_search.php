        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">      

    <h3 class="page-header">添加app</h3>

	  <p>通过appid添加:</p>
      <div class="searchBox">
        <form action="<?php echo base_url()?>user_app/add_app_by_id" method="GET">
          <div class="input-group">
            <input type="text"  name="app_id" class="form-control" placeholder="填写itunes app id">
            <span class="input-group-btn">
            <button type="submit" class="btn btn-default">添加</button>
            </span> 
          </div>
        </form>
      </div>
 
 	<br/>
    <br/>
 	<p>或者通过搜索添加:</p>
      <div class="searchBox">
        <form action="<?php echo base_url() . "user_app/add_app_search"?>" method="GET">
          <div class="input-group">
            <input type="text"  placeholder="输入app的名称" name="q" class="form-control" 
            value="<?php echo isset($query)?$query:"" ;?>">
            <span class="input-group-btn">
            <button type="submit" class="btn btn-default">搜索</button>
            </span> 
          </div>
        </form>
      </div>
      
      <br/>
      <br/>
        
      
    <?php $i=1; foreach ($docs as $item) { ?>
       <div class="searchResultList">
        <table width="100%" border="0">
          <tr>
          <td width="6%" rowspan="2" class="sRNum tc fb"><?php echo $i;?></td>
      <td width="9%" rowspan="2"><img src="<?php echo $item["icon"];?>" height="72" class="sImg" alt="img"></td>
          <td width="80%"><span class="sRTitle c2"> 
          <a href="<?php echo $item["download_url"];?>" target="_blank">
            <?php echo $item["name"];?></a>
            </span>
          <button type="button" class="btn btn-default btn-xs sRBtn"><?php echo $item["ori_classes"];?></button>
            <?php echo $item["download_times"];?>下载</td>
            <td width="5%"><button type="button" class="btn btn-default btn-xs">
            <a href="<?php echo base_url()."user_app/add_app_by_id?app_id=".$item["app_id"];?>">添加</a>
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
                                   
