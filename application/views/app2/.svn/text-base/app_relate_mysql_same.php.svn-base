<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>">首页</a></li>
        <li><a href="<?php echo base_url();?>app_check">应用检测</a></li>
    </ol> 
  <!-- 提交app基本信息 begin -->
  <div class="row">
    <div class="col-md-8">
        <h2 class="bg-primary">app1: <?php echo $app_name1;?></h2>
        keywords: <?php echo $keywords1;?>
        <br/>
        <br/>
        <h2 class="bg-primary">app2: <?php echo $app_name2;?></h2>
        keywords: <?php echo $keywords2;?>

   </div>
    <div class="col-md-8">
          <br/>
          <br/>
          <h3 class="bg-danger">相同的tag</h3>
          <table class="table table-hover table-condensed table-striped">
                <thead>
                    <tr>
                        <th>
                           tag
                        </th>
                        <th>
                           得分
                        </th>
                        <th>
                            来源
                        </th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($same_keywords as $app) { ?>
                        <tr>
                        <td>
                            <?php echo $app["tag"] ?>
                        </td>
                        <td><?php echo $app["score"] ?></td>
                        <td><?php echo $app["source"] ?></td>
                        </tr>
                        <?php } ?>
                </tbody>
            </table> 
    </div>
  </div>
  <!-- 提交app基本信息 end -->
</div>
