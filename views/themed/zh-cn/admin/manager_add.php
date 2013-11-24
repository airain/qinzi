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
	<form onsubmit="return article.save(this);" method="post" action="add_save" enctype="multipart/form-data" id="myform" name="myform">
	<caption>修改管理员</caption>
	<tbody>
	<tr> 
		<td align="right" class="spec">所属分类: </td>
		<td>
		<select id="typeid" name="typeid">
		   <option>选择所属分类</option>
		  <option value="1">普通管理员</option>
		  <option selected="" value="2">高级管理员</option>
		</select>
		<input type="hidden" value="2" name="id" id="id">
		</td> 
	</tr>
	<tr> 
		<td align="right" class="spec">管理员名称: </td>
		<td><input type="text" maxlength="20" size="20"  id="user_name" name="user_name"></td> 
	</tr>
	<tr> 
		<td align="right" class="spec">管理员密码: </td>
		<td><input type="text" maxlength="20" size="20" value="" id="user_pwd" name="user_pwd"></td> 
	</tr>

	<tr> 
	  <td class="specalt" abbr="G5 Processor" scope="row" colspan="2">
		<div align="center" style="width:450px">
		<input type="submit" value="保存" name="Submit">
		&nbsp;
		<input type="button" onclick="history.go(-1);" value="返回" name="Submit2">	
		</div>
	  </td>
	</tr> 
  
	</tbody>
</table>

</body>
</html>