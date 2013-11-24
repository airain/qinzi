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
$list = $this->data['list'];
$tree = $this->data['tree'];
?>
 <table id="mytable" cellspacing="0" width="100%">
   <caption>文章管理</caption>
  <tr> 
   <td>
      <div align="right">
		<a href="<?=SITE_URLI?>/admin-article/add">新增</a>
	  </div>
   </td>
  </tr> 
 <form action="<?=SITE_URLI?>/admin-article/search" method="get" id="article_form" name="article_form"> 
 <input type="hidden" name="type" value="search">
  <tr>
	<td>搜索：
	标题：<input type="input" name="skey">
	<input type="submit" value="搜索">
	<span style="float:right;margin-right:60px;">
	<select name="scategory_id" id="scategory_id" style="width:100px;" onchange="goUrl(this.options[this.selectedIndex].value,'<?=SITE_URLI?>/admin-article?category_id=');">
	   <option value="0">选择分类</option>
       <?foreach($tree as $node){?>
       <option value="<?=$node['id']?>"><?=$node['name']?></option>
	   <?}?>	  
    </select>
	</span>
	</td>
 </tr>
  </form>
</table> 

<table id="mytable" cellspacing="0" width="100%">
  <form action="<?=SITE_URLI?>/admin-article/delmany" method="POST" id="myform" name="myform" onSubmit="return checkSelectedCheckbox(this,'请选择要操作的数据');"> 
  <tr>  
    <th scope="col" abbr="Dual 1.8" ><input type="checkbox" name="checkbox" value="checkbox"  onClick="selectAll(this,this.form)"/></th> 
    <th scope="col" abbr="Dual 2" >标题</th> 
    <th scope="col" abbr="Dual 2.5">所属分类</th> 
	<th scope="col" abbr="Dual 2">作者</th>
	<th scope="col" abbr="Dual 2" >发布日期</th>
	<th scope="col" abbr="Dual 2">点击</th>
	<th scope="col" abbr="Dual 2">已审核</th>
	<th scope="col" abbr="Dual 2">置顶</th>
	<th scope="col" abbr="Dual 2">推荐</th>
	<th scope="col" abbr="Dual 2"><div align="center">操作</div></th>
  </tr> 
  <?
  if(empty($list))
  {
	echo "<tr><td colspan='10'>暂无任何信息</td></tr>";
  }
  else
  {
	 foreach($list as $data){
  ?>
  <tr>
   <td><input type="checkbox" name="id[]"  value="<?=$data['id']?>" /></td>
   <td title="<?=$data['title']?>">&nbsp;<a href="<?=SITE_URLI?>/news/detail?id=<?=$data['id']?>" target="_blank"><?=output($data['title'],30)?></a></td>
   <td>&nbsp;<a href="<?=SITE_URLI?>/admin-article/index?category_id=<?=$data['category_id']?>"><?=$data['catename']?></a></td>
   <td align="left">&nbsp;<?=$data['author'] ?></td>
   <td>&nbsp;<?=$data['pubtime'] ?></td>
   <td>&nbsp;<?=$data['hit']?></td>
   <td align="center">&nbsp;<?=$data['state'] == 1 ? "<font color='red'>是</font>" : "否" ?></td>
   <td align="center">&nbsp;<?=$data['top'] == 1 ? "<font color='red'>是</font>" : "否" ?></td>
   <td align="center">&nbsp;<?=$data['elite'] == 1 ? "<font color='red'>是</font>" : "否" ?></td>  
   <td align="center">
   [<a href="<?=SITE_URLI?>/admin-article/modify?id=<?=$data['id']?>">修改</a>]
   [<a href="<?=SITE_URLI?>/admin-article/delete?id=<?=$data['id']?>" onclick="return confirm('您真的要删除吗？')">删除</a>]
   </td>
  </tr>
  <?
	   }
  }
   ?>
  
  <tr> 
   <td colspan='11' scope="row" abbr="G5 Processor" class="specalt">
	  <input type="hidden" name="type" id='type' value="">
      <input type="submit" name="button" id="button" value="删 除" onclick="opArticle('delmany')" />
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <div align="center" style="width:600px; height:26px; line-height:26px; margin-top:5px;">
	  <?php echo $this->data['page_str'];?>
	   </div>   </td>
  </tr> 
  </form>
</table> 
<script>
function opArticle(tag)
{
		if('delmany' == $("#type").val())
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