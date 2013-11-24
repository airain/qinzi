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

?>

　<table id="mytable" cellspacing="0" width="100%"> 
　<form name="myform" id="myform" enctype="multipart/form-data" action="add_save" method="post" onsubmit="return article.save(this);">
  <caption><?php echo $this->data['nav_typename'];?>增加</caption>
  <tr> 
    <td class="spec" width="15%" align="right">姓名:</th> 
    <td><input name="person_name" type="text" id="person_name" size="50" maxlength="50" /></td> 
  </tr>
  <tr> 
    <td class="spec" width="15%" align="right">国籍:</th> 
    <td><input name="nationality" type="text" id="nationality" size="50" maxlength="50" /></td> 
  </tr>

  <tr> 
    <td class="spec" width="15%" align="right">性别:</th> 
    <td>
		<input type="radio" name="gender" value="1">男
		<input type="radio" name="gender" value="2" checked>女
	</td> 
  </tr>

  <tr> 
    <td class="spec" width="15%" align="right">生活照片:</th> 
    <td><input type="file" name="headimg"></td> 
  </tr>
  <tr> 
    <td class="spec" width="15%" align="right">个人介绍:</th> 
    <td>
		<div>
			<input type="hidden" id="bio_content" name="bio" value="" style="display:none" />
			<input type="hidden" id="content___Config" value="" style="display:none" />
			<iframe id="content___Frame" src="<?php echo PUBLIC_URL;?>fckeditor/editor/fckeditor.html?InstanceName=bio_content&Toolbar=Basic" width="510" height="200" frameborder="0" scrolling="no"></iframe>
		</div>
	</td> 
  </tr>
   <tr> 
    <td class="spec" width="15%" align="right">备注:</th> 
    <td><textarea name="notes" id="notes" rows="4" cols="60"></textarea></td> 
  </tr>

<?php
if($this->data['nav_typename'] == 'photographers' || $this->data['nav_typename'] == 'retouching')
{
?>
  <tr> 
    <td class="spec" width="15%" align="right">作品分类:</th> 
    <td>
		<?php
		foreach($this->data['catearray'] as $key=>$val){
			echo '<input type="checkbox" name="cate_id[]" value="'.$val['id'].'">'.$val['name'].' ';
		}
		?>
	</td> 
  </tr>
<?php
}
?>


<?php
if($this->data['nav_typename'] == 'models')
{
?>
  <tr> 
    <td class="spec" width="15%" align="right">waist:</th> 
    <td><input name="waist" type="text" id="waist" size="50" maxlength="50" /> CM</td> 
  </tr>
  <tr> 
    <td class="spec" width="15%" align="right">hips:</th> 
    <td><input name="hips" type="text" id="hips" size="50" maxlength="50" /> CM</td> 
  </tr>
  <tr> 
    <td class="spec" width="15%" align="right">shoes:</th> 
    <td><input name="shoes" type="text" id="shoes" size="50" maxlength="50" /></td> 
  </tr>
  <tr> 
    <td class="spec" width="15%" align="right">hair:</th> 
    <td><input name="hair" type="text" id="hair" size="50" maxlength="50" /></td> 
  </tr>
  <tr> 
    <td class="spec" width="15%" align="right">eyes:</th> 
    <td><input name="eyes" type="text" id="eyes" size="50" maxlength="50" /></td> 
  </tr>
<?php
}
?>

  <tr> 
    <td class="spec" align="right">是否审核:</th> 
    <td>
		<input name="state" type="radio" id="state" value="1" checked/>是
		<input name="state" type="radio" id="state" value="2" />否	
	</td> 
  </tr>
  <tr> 
    <td class="spec" width="15%" align="right">发布时间:</th> 
    <td><input name="createtime" type="text" id="createtime" size="50" maxlength="50" value="<?php echo date('Y-m-d H:i:s',time());?>" /></td> 
  </tr>
  
  <tr> 
  <td colspan='2' scope="row" abbr="G5 Processor" class="specalt">
    <div align="center" style="width:450px">
	<input type="hidden" name="nav_typeid" value="<?php echo $this->data['nav_typeid'];?>"> 
    <input type="submit" name="Submit" value="保存" />
    <input type="button" name="Submit2" value="返回"  onclick="history.go(-1);" />	
	</div>
  </td>
  </tr> 
  </form>

</table> 
 <script>
function add_upload_area(){
	upload_num++;
	$('#upload_div_'+upload_num).show();
}

function del_upload_area(that){
	var num = $(that).attr('tip');
	$('#upload_div_'+num).attr('disabled','true');
	$('#upload_div_'+num).hide();
}

 </script>
<script>
var selProvance = document.getElementById('selProvance');
var selCity = document.getElementById('selCity');
var selArea = document.getElementById('selArea');

window.onload = function(){
	loadData(selProvance,selCity,selArea);

}
</script>
</body>
</html>