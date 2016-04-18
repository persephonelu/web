        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">      


    <h3 class="page-header">新关键词推荐 <small> (注：如果推荐的词是知名app的名称，请选择其中部分词作为关键词，防止被k)</small></h3>
              

            <table width="100%" border="0" class="customers">
                <tr>
                  <th width="9%">序号</th>
                  <th>搜索词</th>
                  <th title="搜索热度反映每天搜索
的次数多少">搜索热度 <span class="glyphicon glyphicon-question-sign text-info"></span></th>
                  <th>搜索结果数</th>
                  <th>第1名APP</th>
                </tr>
                <?php $i = $start ; foreach ($word_info as $item) { ?>
                <tr>
                  <td><h3><?php echo $i; ?></h3></td>
                  <td><?php echo $item["word"] ?></td>
                  <td><?php echo $item["rank"] ?></td>
                  <td><?php echo $item["num"] ?></td>
                  <td><span class="c2"><?php echo $item["name"] ?></span></td>
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
