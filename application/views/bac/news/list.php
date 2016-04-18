<article class="results clearfix">
    <form id="searchBar" method="POST" action='<?php echo base_url();?>main/search' >
            <input name="q" type="text" placeholder='输入你想找的新闻'/>
			<input type="image" src="<?php echo base_url();?>resource/imgs/search-blue.png">
        </form>
        <h1>搜索“<?php echo $query?>”的结果</h1>
         <section>
        <?php foreach ( $docs as $item ) { ?>
            <div>
            <h2><a href="<?php echo $item['Url']?>" class="title-link" target="_blank">
            <?php echo $item['Title']?></a>
            <span  class="text-info">来源:<?php echo $item['Source']?></span></h2>
            <em>Likes:</em>
            <span class="ratings">|时间:(<?php echo $item['Date']?>)</span>
            <p><?php echo $item['Description']?></p>
           </div>
         <?php } ?>
         </section>
        <?php echo $turn_page?>
      </article>
