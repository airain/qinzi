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
?>
 <table id="mytable" cellspacing="0" width="100%">
   <caption>
	<a href="<?=SITE_URLI?>/admin-product?nav_typeid=<?php echo $this->data['nav_typeid'];?>"><?php echo $this->data['nav_typename'];?>成员管理</a> >> <?php echo $this->data['person']['person_name'];?>作品管理</caption>
  <?php
  if(isset($this->data['person_id']))
  {
  ?>
  <tr> 
   <td>
      <div align="right">
		<a href="<?=SITE_URLI?>/admin-product/add?nav_typeid=<?php echo $this->data['nav_typeid'];?>&person_id=<?php echo $this->data['person_id'];?>">新增作品</a>
	  </div>
   </td>
  </tr> 
  <?php
  }
  ?>

 <form action="<?=SITE_URLI?>/admin-product" method="get" id="product_form" name="product_form"> 
 <input type="hidden" name="nav_typeid" value="<?php echo $this->data['nav_typeid'];?>">
 <input type="hidden" name="person_id" value="<?php echo $this->data['person_id'];?>">
  <tr>
	<td>搜索：
	作品名称：<input type="input" name="skey_title">
	<input type="submit" value="搜索">
	</td>
 </tr>
  </form>
</table> 
<?php
if(isset($this->data['skey_title'])){
?>
<p>搜索关键字为: <span style="color:red"><?php echo $this->data['skey_title'];?></span></p>
<?php
 }
?>
</table> 

<table id="mytable" cellspacing="0" width="100%">
  <form action="<?=SITE_URLI?>/admin-product/delmany" method="POST" id="myform" name="myform" onSubmit="return checkSelectedCheckbox(this,'请选择要操作的数据');"> 
  <tr>  
    <th scope="col" abbr="Dual 1.8" ><input type="checkbox" name="checkbox" value="checkbox"  onClick="selectAll(this,this.form)"/></th> 
    <th scope="col" abbr="Dual 2" >姓名</th> 
    <th scope="col" abbr="Dual 2.5">作品名称</th> 
	<th scope="col" abbr="Dual 2">加入时间</th>
	<th scope="col" abbr="Dual 2">已审核</th>
	<th scope="col" abbr="Dual 2"><div align="center">操作</div></th>
  </tr> 
  <?
  if(empty($list)){
	echo "<tr><td colspan='9'>暂无任何信息</td></tr>";
  }else{
	 foreach($list as $data){
  ?>
  <tr>
   <td><input type="checkbox" name="id[]"  value="<?=$data['id']?>" /></td>
   <td>
	<a href="<?=SITE_URLI?>/admin-product?nav_typeid=<?php echo $this->data['nav_typeid'];?>&person_id=<?=$data['person_id']?>" ><?=output($data['person_name'],30)?></a>
   </td>
   <td><?php echo $data['title'];?></td>
   <td>&nbsp;<?php echo date('Y-m-d',$data['createtime']); ?></td>
   <td align="left">&nbsp;<?=$data['state'] == 1 ? "<font color='red'>是</font>" : "否" ?></td>
   <td align="left">
   [<a href="<?=SITE_URLI?>/admin-product/modify?nav_typeid=<?php echo $this->data['nav_typeid'];?>&id=<?=$data['id']?>">修改</a>]
   [<a href="<?=SITE_URLI?>/admin-product/delete?nav_typeid=<?php echo $this->data['nav_typeid'];?>&id=<?=$data['id']?>" onclick="return confirm('您真的要删除吗？')">删除</a>]
   </td>
  </tr>
  <?
	   }
  }
   ?>
  
  <tr> 
   <td colspan='7' scope="row" abbr="G5 Processor" class="specalt">
	  <input type="hidden" name="type" id='type' value="">
      <input type="submit" name="button" id="button" value="删 除" onclick="opArticle('delmany')" />
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <div align="center" style="width:600px; height:26px; line-height:26px; margin-top:5px;">
	  <?php echo $this->data['page_str'];?>
	   </div>
	</td>
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