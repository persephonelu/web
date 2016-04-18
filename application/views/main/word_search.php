<!-- Page Content -->
  <div id="page-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <h3 class="page-header">关键词搜索 </h3>
          
          <div class="row" id="form_area">
          
            <div class="col-lg-8">
            <form role="form" method="post" action="<?php echo base_url();?>search_word?nav=word" class="form-inline">
                <div class="input-group">
                  <input type="text" class="form-control" name="q" placeholder="搜索关键词..."  
                value="<?php echo $query;?>">
                </div>
                <button type="submit" class="btn btn-primary">搜索</button>
              </form>
            </div>
             
          </div>
          <!-- /#from_area -->
          </br>
          </br>
          

                        <table class="table table-striped table-bordered">
                            <tr>
                                <th width="9%">序号</th>
                                <th>关键词</th>
                                <th title="搜索指数反映每天搜索
的次数多少">搜索指数   <span class="glyphicon glyphicon-question-sign text-info"></span></th>
                              </tr>
                          <?php $index=1; foreach ( $suggestion  as $item ) { ?>
                            <tr>
                                <th><?php echo $index?></th>
                                <td><span class="c2"><?php echo $item['word']?></span></td>
                                <td><?php echo $item['value']?></td>
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
