<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>">首页</a></li>
        <li><a href="<?php echo base_url();?>">用户登录</a></li>
    </ol>
<div class="row">
<div class="col-md-8">    
      
<form role="form" action="<?php echo base_url();?>user/login_check" method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">E-mail:</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">密码:</label>
    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  <button type="submit" class="btn btn-primary">登录</button>
</form>
    <br/>
     <p><a href="<?php echo base_url()?>/user/weibo_login">新浪微博登陆</a>
     &nbsp;|&nbsp;<a href="<?php echo base_url()?>/user/register">从未注册？注册</a></p>
    <br/>
    <h3 class="text-danger"><?php if (isset($error) && "0"!=$error) {echo $error;}?></h3>
      <br/>
</div>
</div>
</div>
