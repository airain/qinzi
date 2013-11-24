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
   <caption>用户列表</caption>
 <form action="<?=SITE_URLI?>/admin-person/" method="get" id="person_form" name="person_form"> 
  <tr>
	<td>搜索：
	姓名：<input type="input" name="skey_person_name">
	<input type="submit" value="搜索">
	</td>
 </tr>
  </form>
</table> 

<table id="mytable" cellspacing="0" width="100%">
  <form action="<?=SITE_URLI?>/admin-person/delmany" method="POST" id="myform" name="myform" onSubmit="return checkSelectedCheckbox(this,'请选择要操作的数据');"> 
  <tr>  
    <th scope="col" abbr="Dual 1.8" ><input type="checkbox" name="checkbox" value="checkbox"  onClick="selectAll(this,this.form)"/></th> 
    <th scope="col" abbr="Dual 2" >昵称</th> 
    <th scope="col" abbr="Dual 2.5">email</th> 
    <th scope="col" abbr="Dual 2.5">积分</th> 
	<th scope="col" abbr="Dual 2">性别</th>
	<th scope="col" abbr="Dual 2">注册时间</th>
	<th scope="col" abbr="Dual 2"><div align="center">操作</div></th>
  </tr> 
  <?php
  if(empty($list)){
	echo "<tr><td colspan='7'>暂无任何信息</td></tr>";
  }else{
	 foreach($list as $data){
  ?>
  <tr>
   <td><input type="checkbox" name="id[]"  value="<?=$data['id']?>" /></td>
   <td title="<?=$data['nick']?>">&nbsp;<a href="<?=SITE_URLI?>/person?id=<?=$data['id']?>" target="_blank"><?php echo output($data['nick'],30)?></a></td>
   <td class="email">
		<?php echo $data['email'];?>
	</td>
   <td align="left" class="jifen">
		<?php echo $data['jifen'];?>
	</td>
	<td align="left" class="gender">
		<?php echo $data['gender'];?>
	</td>
   <td>&nbsp;<?php echo date('Y-m-d',$data['regtime']); ?></td>
   <td align="left">
   [<a href="<?=SITE_URLI?>/admin-person/modify?nav_typeid=<?php echo $this->data['nav_typeid'];?>&id=<?=$data['id']?>">修改</a>]
   [<a href="<?=SITE_URLI?>/admin-person/delete?nav_typeid=<?php echo $this->data['nav_typeid'];?>&id=<?=$data['id']?>" onclick="return confirm('您真的要删除吗？')">删除</a>]
   </td>
  </tr>
  <?php
	   }
  }
   ?>
  
  <tr> 
   <td colspan='8' scope="row" abbr="G5 Processor" class="specalt">
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


$(function(){
	$('.province').each(function(){
		var pid = $(this).find('a').attr('data_id');
		$(this).find('a').text(getProvinceById(pid));		
	});

	$('.city').each(function(){
		var cid = $(this).find('a').attr('data_id');
		$(this).find('a').text(getCityById(cid));
	});

	$('.district').each(function(){
		var did = $(this).find('a').attr('data_id');
		if(did==''){
			$(this).find('a').text('');
		}else{
			$(this).find('a').text(getAreaById(did));
		}
	})

	

})
</script>
 
</body>
</html>
