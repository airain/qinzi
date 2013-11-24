<!doctype html public "-//w3c//dtd xhtml 1.0 transitional//en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="keywords" content="<?php echo $this->data['keywords'];?>" />
<meta name="description" content="<?php echo $this->data['description'];?>" />
<title><?php echo $this->data['title'];?></title>
<?php echo $this->loadLink();?>	
<?php echo $this->loadScript();?>

<style type="text/css">
<!--
*{margin:0; padding:0;}
body {font-family: Arial, Helvetica, sans-serif,"宋体"; font-size: 12px;line-height: 210%;font-weight: normal;color: #333333;text-decoration: none;background: #0cf url(<?php echo PUBLIC_URL; ?>images/admin/03.jpg) repeat-x 0 0 ;}
li{ list-style:none;}
input {	font-family:"宋体";	font-size:12px;	border:1px solid #dcdcdc;height:18px;line-height:18px; padding-left:2px;}
#main{ background:url(<?php echo PUBLIC_URL; ?>images/admin/01.jpg) no-repeat 300px 0; width:930px; min-height:600px; height:600px; overflow:hidden; margin:0 auto; position:relative;}
#login_box{	width:278px; height:138px; background:url(<?php echo PUBLIC_URL; ?>images/admin/02.jpg) no-repeat 0 0;	position:absolute; top:228px; left:380px; padding-left:50px; padding-top:50px;line-height:138px;}
#login_box ul li{ line-height:32px; height:32px;}
.btn{ background:url(<?php echo PUBLIC_URL; ?>images/admin/05.gif) no-repeat 0 0; height:20px; width:58px; border:0; cursor:pointer; color:#fff; line-height:20px;}
-->
</style>
</head>
<body onload="javascript:document.myform.uname.focus();">
<div id="main">
  <div id="login_box">
    <ul>
    <form method=post action="<?php echo SITE_URLI;?>/admin-login/login_save" name="myform">
      <li>用户名：<input type="text" name="user_name" class="input"></li>
	  <li>密　码：<input type="password" name="user_pwd" class="input"></li>
      <li style="padding-left: 48px;">
	    <input name="dosubmit" value=" 登录 " class="btn" type="submit"> 
	    <input name="reset" value=" 清除 " class="btn" type="reset">
      </li>
    </form>
    </ul>
  </div>
</div>
</body>
</html>
