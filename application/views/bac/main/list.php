<article class="results clearfix">
    <form id="searchBar" method="POST" action='<?php echo base_url();?>main/search' >
            <input name="q" type="text" placeholder='输入你想找的app'/>
			<input type="image" src="<?php echo base_url();?>resource/imgs/search-blue.png">
        </form>
        <h1>搜索“<?php echo $query?>”的结果</h1>
         <section>
        <?php foreach ( $docs as $item ) { ?>
            <div>
            <img data-src="holder.js/140x140" class="img-rounded" alt="140x140" style="width: 100px; height:100px;" src="<?php echo $item['icon']?>">
            <h2><a href="<?php echo $item['from_url']?>" class="title-link" target="_blank">
            <?php echo $item['name']?></a>
            <span  class="text-info">下载量:<?php echo $item['download_times']?></span></h2>
            <em>Likes:<?php echo $item['download_times']?></em>
            <span class="ratings">|评论数:(<?php echo $item['user_comment_num']?>)</span>
            <p><?php echo $item['brief']?></p>
           </div>
         <?php } ?>
         </section>
        <?php echo $turn_page?>
      </article>
