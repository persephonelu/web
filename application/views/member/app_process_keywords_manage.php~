        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">  


    <h3 class="page-header">app关键词管理</h3>
      <div class="panel panel-default">
        <div class="panel-heading"> 推测的关键词</div>
        <div class="panel-body">
       <form role="form" action="#" method="post">
        <div class="form-group">
        <textarea class="form-control" rows="3" name="d" readonly><?php echo isset($predict_word)?$predict_word:"";?></textarea>
        </div>
      </form>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading"> iTunes 填写的关键词 </div>
        <div class="panel-body">    
        <form role="form" action="<?php echo base_url() . "user_app_process/update_app_itunes_word"?>" method="post">
        <div class="form-group">
            <textarea class="form-control" rows="3" name="itunes_word_list"><?php echo isset($itunes_word)?$itunes_word:"";?></textarea>
        </div>
        <input type="hidden" name="app_id" 
        value="<?php echo isset($app_info)?$app_info["app_id"]:"" ;?>">
        <button type="submit" class="btn btn-primary">保存</button>
      </form>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading"> 后续希望添加的关键词 </div>
        <div class="panel-body">
        <form role="form" action="<?php echo base_url() . "user_app_process/update_app_wish_word"?>" method="post">
        <div class="form-group">
            <textarea class="form-control" rows="3" name="wish_word_list"><?php echo isset($wish_word)?$wish_word:"";?></textarea>
        </div>
        <input type="hidden" name="app_id" 
        value="<?php echo isset($app_info)?$app_info["app_id"]:"" ;?>">
        <button type="submit" class="btn btn-primary">保存</button>
      </form>
        </div>



                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
