	<article class="content">
    <form id="searchBar" method="POST" action='<?php echo base_url();?>main/search'>
    <input name="q" type="text" placeholder='<?php echo $query;?>'/>
    <input type="image" src="<?php echo base_url();?>resource/imgs/search-blue.png">
        </form>
        <hr>
        <h2>搜索"<?php echo $query;?>"的结果</h2>
        <div class="row">
        <div class="col-md-8 col-md-offset-2">
        <table class="table table-striped table-bordered" >
        <thead>
        <tr>
        	<th>图标</th>
            <th>名称</th>
            <th>类型</th>
            <th>生产商</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ( $docs as $item ) { ?>
        <tr>
        	<td>
            <img  class="img-rounded" alt="10x10" style="width: 60px; height:60px;" src="<?php echo $item['icon']?>">
            </td>
            <td style="word-break:keep-all;"><a href="<?php echo base_url().'content/index/?name='.$item['name'].'&type='.$item['type'];?>" target="blank"><?php echo $item['name']?></a></td>
            <td> <?php echo $item['type']?> </td>
            <td> <?php echo $item['platform_list']?> </td>
        </tr>
         <?php } ?>
        </tbody>
        </table>
        <?php echo $turn_page;?>
        </div><!-- div of class col-md-6"-->
        </div>
        
      </article>
