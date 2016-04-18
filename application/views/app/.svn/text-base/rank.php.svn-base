		<div class="index_content clearfix">
            <form action="<?php echo base_url();?>main/search" method="GET">
				<div>
					<input name="q" type='text' placeholder='请输入要搜索的应用'/>
					<button name="search" class="glyphicon glyphicon-search"></button>
				</div>
			</form>
			<h2>“<span><?php echo $select_day;?></span>”如下：</h2>
			<table class="table table-hover">
		        <thead>
                  <tr>
                    <th>排名</th>
                    <th>名称</th>
                    <th>类型</th>
		            <th>市场</th>
		          </tr>
		        </thead>
		        <tbody>
                <?php $i=0;foreach ( $docs as $item ) { ?>
               <tr>
                <td>
                <?php $i++; echo $start+$i ?>
                </td>
                <td style="word-break:keep-all;"><a href="<?php echo base_url().'content/index/?name='.$item['name'];?>" target="blank"><?php echo $item['name']?></a></td>
            <td> <?php echo $item['classes']?> </td>
            <td> <?php echo $item['from_plat']?> </td>
        </tr>
         <?php } ?> 

                </tbody>
		      </table>
             <?php echo $turn_page;?>
		</div>
