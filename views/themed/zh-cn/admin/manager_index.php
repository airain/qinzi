<html xmlns="http://www.w3.org/1999/xhtml"><head> 
<meta content="text/html; charset=utf-8" http-equiv="Content-Type"> 
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="keywords" content="<?php echo $this->data['keywords'];?>" />
<meta name="description" content="<?php echo $this->data['description'];?>" />
<title><?php echo $this->data['title'];?></title>
<?php echo $this->loadLink();?>	
<?php echo $this->loadScript();?>
</head> 
<body>    
 <table width="100%" cellspacing="0" id="mytable">
   <caption>管理员管理</caption>
</table> 

<table width="100%" cellspacing="0" id="mytable">  
  <tbody>
	  <tr>  
		<th abbr="Dual 2" scope="col">类型</th> 
		<th abbr="Dual 2.5" scope="col">管理员名称</th> 
		<th abbr="Dual 2" scope="col"><div align="center">操作</div></th>
	  </tr> 
	  <?php
		foreach($this->data['managers'] as $key=>$val)
		{
	  ?>
	  <tr>
	   <td>&nbsp;高级管理员</td>
	   <td>&nbsp;<?php echo $val['user_name'];?></td>
	   <td align="center">
	   [<a href="<?php echo SITE_URLI;?>/admin-manager/modify?user_id=<?php echo $val['user_id'];?>">修改</a>]&nbsp;&nbsp;
	   [<a onclick="return confirm('您真的要删除吗？')" href="<?php echo SITE_URLI;?>/admin-manager/delete?user_id=<?php echo $val['user_id'];?>">删除</a>]
	   </td>
	  </tr>
	  <?php
		}
	   ?>

  </tbody>
</table>
 
</body>
</html>