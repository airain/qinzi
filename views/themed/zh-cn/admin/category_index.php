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
$tree = $this->data['tree'];
?>
<table id="mytable" cellspacing="0" width="100%">
<caption>分类管理</caption>
  <tbody>
  <tr> 
   <td colspan="5">
      <div align="right"><a href="<?php echo SITE_URLI; ?>/admin-category/add">新增</a></span></div>   </td>
  </tr> 
    <form action="<?php echo SITE_URLI; ?>/admin-category/add" method="POST" id="myform" name="myform" > 
  <tr>  
    <th scope="col" abbr="Dual 2" >序号<input type="checkbox" name="checkbox" value="checkbox"  onClick="selectAll(this,this.form)"/></th> 
    <th scope="col" abbr="Dual 2" >分类名称</th> 
	<th scope="col" abbr="Dual 2"><div align="center">操作</div></th>
  </tr> 
<?$i=0;foreach($tree as $node){?>  
  <?foreach($node['child'] as $child){?>
  <tr onMouseOut=this.style.color="blue" onMouseOver=this.style.color="red">
    <td>&nbsp;<input type="checkbox" name="id[]"  value="<?=$child['id']?>" /> <input name="sort_number[<?=$child['id']?>]" value="<?=$child['sort_number']?>" type="text" size="4"></td>
    <td>&nbsp;<?=$child['name']?></td>
    <td align="center">
		<a href="<?=SITE_URLI?>/admin-category/add?cateid=<?=$child['id']?>">[新增]</a>
		<a href="<?=SITE_URLI?>/admin-category/delete?cateid=<?=$child['id']?>" onClick="return confirm('你真的要删除吗?');">[删除]</a>&nbsp;&nbsp;
		<a href="<?=SITE_URLI?>/admin-category/modify?cateid=<?=$child['id']?>">[修改]</a>
	</td>

  </tr>
   <?}?>
<?}?>   
  <tr onMouseOut=this.style.color="blue" onMouseOver=this.style.color="red">
    <td colspan="3">
	  <input type="hidden" name="type" id='type' value="">
      <input type="submit" name="button" id="button" value="排序" onclick="opArticle('sort')" />
	</td>
  </tr>
  </form>
  </tbody>
</table>
<script>
function opArticle(tag)
{
	if('del' == $("#type").val())
	{
		if(confirm('您真的要删除吗？'))
			$("#type").val(tag);
	}
	else
	{
		$("#type").val(tag);
	}

}
</script>
 
</body>
</html>