		<div class="index_content clearfix">
            <form action="<?php echo base_url();?>main/search" method="GET">
				<div>
					<input name="q" type='text' placeholder='请输入要搜索的应用'/>
					<button name="search" class="glyphicon glyphicon-search"></button>
				</div>
			</form>
			<h2>搜索“<span><?php echo $query;?></span>”的结果如下：</h2>
			<table class="table table-hover">
		        <thead>
                  <tr>
                    <th>图标</th>
                    <th>名称</th>
                    <th>类型</th>
		            <th>市场</th>
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
		</div>
