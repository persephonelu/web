<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>">首页</a></li>
        <li><a href="<?php echo base_url();?>">用户注册</a></li>
    </ol>
<div class="row">
<div class="col-md-8">    
      
<form role="form" action="<?php echo base_url();?>user/write_user_info" method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">E-mail:</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">密码:</label>
    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">确认密码:</label>
    <input type="password" name="password_check" class="form-control" id="exampleInputPassword1" placeholder="
Password">
  </div>
  <button type="submit" class="btn btn-primary">注册</button> 
  <br/>
  <br/>
  <p><a href="<?php echo base_url()?>/user/login">已经注册过？登陆</a>

  &nbsp;|&nbsp; <a href="<?php echo base_url()?>/user/weibo_login">新浪微博直接登陆</a></p>
</form>
<br/>
<br/>
<h3 class="text-danger"><?php if (isset($error) && "0"!=$error) {echo $error;}?></h3>
</div>
</div>
</div>
