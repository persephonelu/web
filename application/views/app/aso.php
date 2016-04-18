		<div class="index_content clearfix">
            <form action="<?php echo base_url();?>sao/search" method="GET">
				<div>
					<input name="q" type='text' placeholder='请输入要搜索的关键词'/>
					<button name="search" class="glyphicon glyphicon-search"></button>
				</div>
			</form>
			<h2>“<span><?php echo $select_day;?></span>”如下：</h2>
			<table class="table table-hover">
		        <thead>
                  <tr>
                    <th>序号</th>
                    <th>热词</th>
                    <th>权重</th>
		          </tr>
		        </thead>
		        <tbody>
                <?php $i=0;foreach ( $keywords as $item ) { ?>
               <tr>
                <td>
                <?php $i++; echo $start+$i ?>
                </td>
                <td style="word-break:keep-all;"><a href="<?php echo base_url().'content/index/?name='.$item['app_name'];?>" target="blank"><?php echo $item['app_name']?></a></td>
            <td> <?php echo $item['hot_index']?> </td>
        </tr>
         <?php } ?> 

                </tbody>
		      </table>
             <?php echo $turn_page;?>
		</div>
