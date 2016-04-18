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
                    <th>app</th>
                    <th>潜力值</th>
                    <th>最大下载量估计</th>
		          </tr>
		        </thead>
		        <tbody>
                <?php $i=0;foreach ( $docs as $item ) { ?>
               <tr>
                <td>
                <?php $i++; echo $start+$i ?>
                </td>
                <td style="word-break:keep-all;"><a href="<?php echo base_url().'content/index/?name='.$item['app_name'];?>" target="blank"><?php echo $item['name']?></a></td>
            <td> <?php echo $item['score']?> </td>
            <td> <?php echo $item['download_times']?> </td>
        </tr>
         <?php } ?> 

                </tbody>
		      </table>
		</div>
