<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>">首页</a></li>
        <li><a href="<?php echo base_url();?>app_check">应用检测</a></li>
    </ol> 
  <!-- 提交app基本信息 begin -->
  <div class="row">
    <div class="col-md-8">
    <form role="form" action="<?php echo base_url() . "app_check";?>" method="post">
        <div class="form-group">
          <label for="name">应用名(name)</label>
          <input type="text" class="form-control" name="n" placeholder="" value="<?php echo $app_info["name"] ;?>">
        </div>
        
        <div class="form-group">
          <label for="description">描述(Description)</label>
          <textarea class="form-control" rows="10" name="d" readonly="readonly"><?php echo $app_info["brief"];?></textarea>
        </div>
        
        <div class="form-group">
          <label for="description">类别(Catetory)</label>
          <select class="form-control" name="c" readonly="readonly">
            <option> <?php echo $app_info["ori_classes"] ?> </option>
            <?php foreach ($ori_classes as $item) { ?>
            <option><?php echo $item ?></option>
            <?php } ?>
          </select>
        </div>
       
    <div class="form-group">
          <label for="description">关键词(Keywords)</label>
          <textarea class="form-control" rows="5" name="d"><?php echo $keywords;?></textarea>
      </div>  
        <button type="submit" class="btn btn-primary">一键aso优化</button>
      </form>

    <!-- 具体检查内容 -->
    <hr/>
    <div class="panel panel-default">
        <div class="panel-heading">
            现有关键词检测
        </div>
        <div class="panel-body">
          <table class="table table-hover table-condensed table-striped">
                            <tr>
                                <th width="7%">序号</th>
                                <th width="11%">关键词</th>
                                <th title="搜索指数反映每天搜索
的次数多少" width="11%">搜索指数   <span class="glyphicon glyphicon-question-sign text-info"></span></th>
                                <th width="13%">搜索结果数</th>
                                <th width="13%">推荐度</th>
                                <!-- <th width="15%">优化难度</th> -->
                              </tr>
                              <?php $index=1; foreach ($keyword_judge as $item) {?>
                               <tr>
                               <td><?php echo $index;?></td>
                               <td><?php echo $item["word"];?></td>
                               <td><?php echo $item["rank"];?></td>
                               <td><?php echo $item["num"];?></td>
                               <td><?php echo $item["recommend_level"];?></td>
                              </tr>
                             <?php $index++;} ?>      
        </table> 
       </div>
    </div>

        <hr/>
    <div class="panel panel-default">
        <div class="panel-heading">
            新关键词推荐
        </div>
        <div class="panel-body">
                  <table class="table table-hover table-condensed table-striped">
                            <tr>
                                <th width="7%">序号</th>
                                <th width="11%">关键词</th>
                                <th title="搜索指数反映每天搜索
的次数多少" width="11%">搜索指数   <span class="glyphicon glyphicon-question-sign text-info"></span></th>
                                <th width="13%">搜索结果数</th>
                                <th width="13%">推荐度</th>
                                <!-- <th width="15%">优化难度</th> -->
                              </tr>
                              <?php $index=1; foreach ($recommend as $item) {?>
                               <tr>
                               <td><?php echo $index;?></td>
                               <td><?php echo $item["word"];?></td>
                               <td><?php echo $item["rank"];?></td>
                               <td><?php echo $item["num"];?></td>
                               <td><?php echo $item["recommend_level"];?></td>
                              </tr>
                             <?php $index++;} ?>
        </table>
        </div>
    </div>

    <hr/>
    <div class="panel panel-default">
        <div class="panel-heading">
           相似app
        </div>
        <div class="panel-body">
        <table class="table table-hover table-condensed table-striped">        
                    <tr>
                        <th width="90%">
                           app名
                        </th>
                        <th width="10%">
                            相似度
                        </th>
                    </tr>
                 <?php foreach ($relate_app_list as $app) { ?>
                        <tr>
                        <td><a href="<?php echo base_url().'app_check?n='.$app["name"]; ?>" target="_blank">
                            <?php echo $app["name"] ?>
                        </a></td>
                        <td>
                 <a href="<?php echo base_url().'app_check/get_same_tag?n1='.$app["name"] ."&n2=".$app_info["name"]; ?>" target="_blank">
                        <?php echo $app["match_num"] ?>
                        </a></td>
                        </tr>
                        <?php } ?>
             </table>
        </div>
    </div> 

  </div>
</div>
