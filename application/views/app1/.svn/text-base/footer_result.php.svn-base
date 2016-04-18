<script type="text/javascript">
//浮层
window.onload = function() {var aBtn = document.getElementById("c_btn"),aCon = document.getElementById("c_list");aBtn.onclick = function() {if (aCon.style.display == "block") {aCon.style.display = "none";} else {aCon.style.display = "block";};};}
//tab切换
function tabChange(hd,bd,claName,callBack){var hdLis=document.getElementById(hd).getElementsByTagName("li");var bdUls = document.getElementById(bd).getElementsByTagName("ul");for(var i=0;i<hdLis.length;i++){ hdLis[i].index=i;hdLis[i].onclick=function(){for(var j=0;j<hdLis.length;j++){hdLis[j].className="";bdUls[j].style.display="none";}bdUls[this.index].style.display="block";this.className=claName.replace("{index}",this.index);};}}tabChange('tabHd','tabBd','on');
</script>

