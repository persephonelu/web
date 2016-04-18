    <div class="footer">
    	<p class="copy_link"><a href="javascript:;" >关于我们</a> <a href="http://weibo.com/AppBKcom" target="_blank">联系我们</a></p>
    	<p>©2014 AppBK 沪ICP备12031794号</p>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo base_url();?>resource/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>resource/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>resource/js/highcharts.js"></script>

<script type="text/javascript">
//浮层
window.onload = function() {var aBtn = document.getElementById("c_btn"),aCon = document.getElementById("c_list");aBtn.onclick = function() {if (aCon.style.display == "block") {aCon.style.display = "none";} else {aCon.style.display = "block";};};}
//tab切换
function tabChange(hd,bd,claName,callBack){var hdLis=document.getElementById(hd).getElementsByTagName("li");var bdUls = document.getElementById(bd).getElementsByTagName("ul");for(var i=0;i<hdLis.length;i++){	hdLis[i].index=i;hdLis[i].onclick=function(){for(var j=0;j<hdLis.length;j++){hdLis[j].className="";bdUls[j].style.display="none";}bdUls[this.index].style.display="block";this.className=claName.replace("{index}",this.index);};}}tabChange('tabHd','tabBd','on');
    $(function(){
        $('#container_down_trend').highcharts(<?php echo $download_trend;?>); 
     });
</script>
</body>
</html>
