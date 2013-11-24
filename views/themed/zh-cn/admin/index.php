<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="<?php echo $this->data['keywords'];?>" />
<meta name="description" content="<?php echo $this->data['description'];?>" />
<title><?php echo $this->data['title'];?></title>
<?php echo $this->loadLink();?>	
<?php echo $this->loadScript();?>

<body>
<script>
　　$(document).ready(function(){
		i = 0;
		$(".manage_index_left_nav ul").each(function(){
			if(i++>0)
			{
				$(this).css('display','none');
			}
		});
　　});

function showUl(num)
{	
	i = 1;
	$(".manage_index_left_nav ul").each(function(){
		if(i != num)
		{
			$(this).css('display','none');
		}
		else
		{
			$(this).css('display','block');
		}
		i = i+1;
	});
}

</script>
<?php
$manager = $this->data['manager'];
?>
<table class="manageTable1" width=100% height=100%>
 <tr><td colspan=2>
 <div class="manage_top">
	 <div class="manage_top_left"><?php echo SITE_NAME;?>管理后台</div>
	 <div class="manage_top_right"><a href="<?=SITE_URL?>">网站首页</a></div>
 </div>
 </td></tr>
 <tr>
  <td valign="top">
	<div class="manage_index_left_top">
		<span class="marginL10">管理员：
			<strong>
			<span class="font_arial" style="color:#690"><?php echo $manager['user_name']; ?></span>
			</strong>
			<a href="<?php echo SITE_URLI;?>/admin-login/logout">退出</a>&nbsp;<a href="<?php echo SITE_URLI;?>/admin-index">管理首页</a>
		</span>
	</div>

	<div class="manage_index_left_nav">
		<div class="manage_index_left_nav_title" onclick="showUl(1);"><span class="marginL10">试用管理</span></div>
		<ul id="ui_1">
			<li><a href="<?php echo SITE_URLI;?>/admin-category/add" target="right">类别增加</a></li>
			<li><a href="<?php echo SITE_URLI;?>/admin-category" target="right">类别管理</a></li>
		</ul>
	</div>



	<div class="manage_index_left_nav">
		<div class="manage_index_left_nav_title" onclick="showUl(2);"><span class="marginL10">商家管理</span></div>
		<ul id="ui_2">
			<li><a href="<?php echo SITE_URLI;?>/admin-person/add" target="right">商家增加</a></li>
			<li><a href="<?php echo SITE_URLI;?>/admin-person" target="right">商家列表</a></li>
		</ul>
	</div>

	<div class="manage_index_left_nav">
		<div class="manage_index_left_nav_title" onclick="showUl(3);"><span class="marginL10">活动管理</span></div>
		<ul id="ui_3">
			<li><a href="<?php echo SITE_URLI;?>/admin-person/add" target="right">菜单增加</a></li>
			<li><a href="<?php echo SITE_URLI;?>/admin-person" target="right">菜单列表</a></li>
		</ul>
	</div>

	<div class="manage_index_left_nav">
		<div class="manage_index_left_nav_title" onclick="showUl(4);"><span class="marginL10">用户管理</span></div>
		<ul id="ui_4">
			<li><a href="<?php echo SITE_URLI;?>/admin-person/" target="right">用户列表</a></li>
		</ul>
	</div>

	<div class="manage_index_left_nav">
		<div class="manage_index_left_nav_title" onclick="showUl(5);"><span class="marginL10">网站系统管理</span></div>
		<ul id="ui_7">
			<li><a href="<?php echo SITE_URLI;?>/admin-manager/index" target="right">管理员管理</a></li>
			<li><a href="<?php echo SITE_URLI;?>/admin-manager/add" target="right">管理员增加</a></li>	
			<li><a href="<?php echo SITE_URLI;?>/admin-manager/add" target="right">积分管理</a></li>	
		</ul>	
	</div>
  </td>
  <td width=80%  valign="top">
  <iframe height="720" scrolling="auto" width=100% src="<?php echo SITE_URLI;?>/admin-index/admindefault" border=0 name="right" id="frame_content" scrolling="no"  frameborder="0"></iframe>
  </td>
 </tr>
</table>
<script type="text/javascript">
function reinitIframe(){
var iframe = document.getElementById("frame_content");
try{
var bHeight = iframe.contentWindow.document.body.scrollHeight;
var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
var height = Math.max(bHeight, dHeight);
iframe.height =  height;
}catch (ex){}
}
window.setInterval("reinitIframe()", 200);
</script>
</body>
</html>
