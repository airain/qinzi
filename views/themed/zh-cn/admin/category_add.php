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
<?php
$node = $this->data['node'];
$tree = $this->data['tree'];
$parentid = $this->data['parentid'];
?>
<table id="mytable" cellspacing="0" width="100%"> 
<caption>新增分类</caption>
 <tbody>
　<form name="myform" id="myform" method="post" action="<?=SITE_URLI?>/admin-category/add_save" >  
  <tr> 
    <td class="spec" width="15%" align="right">分类名称:</th> 
    <td><input name="name" type="text" id="name" size="20" maxlength="20" /></td> 
  </tr>
  <tr> 
    <td class="spec" align="right">所属分类:</th> 
    <td>
	 <select name="parentid" size="20" id="parentid">
		<option value="0" <?=$parentid == 0 ? 'selected' : ''?>>一级分类</option>
		<?foreach($tree as $node){?>         
		<option value="<?=$node['id']?>" <?=$parentid ==$node['id']? 'selected' : ''?>><?=$node['name']?></option> 
        <?}?>
	 </select>
    <a href="index">分类管理</a>	</td> 
  </tr>

  <tr> 
  <td colspan='2' scope="row" abbr="G5 Processor" class="specalt">
    <div align="center" style="width:450px">
    <input type="submit" name="Submit" value="保存" />
    &nbsp;
    <input type="button" name="Submit2" value="返回"  onclick="history.go(-1);" />	
	</div>
  </td>
  </tr> 
  </form>
   </tbody>
</table> 
 
</body>
</html>