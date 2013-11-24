<html xmlns="http://www.w3.org/1999/xhtml"><head> 
<meta content="text/html; charset=utf-8" http-equiv="Content-Type"> 
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="keywords" content="<?php echo $this->data['keywords'];?>" />
<meta name="description" content="<?php echo $this->data['description'];?>" />
<title><?php echo $this->data['title'];?></title>
<?php echo $this->loadLink();?>	
<?php echo $this->loadScript();?>
</head> 
<style>
.upload_img p{width:100%;margin: 0;}
.upload_img p span{width:120px; display:inline-block; text-align:right; height:20px; }
</style>
<script>
var upload_num = 1;
</script>
<body>    
　<table id="mytable" cellspacing="0" width="100%"> 
　<form name="myform" id="myform" enctype="multipart/form-data" action="add_save" method="post">
  <caption>
  <a href="<?=SITE_URLI?>/admin-product?nav_typeid=<?php echo $this->data['nav_typeid'];?>"><?php echo $this->data['nav_typename'];?>成员管理</a> >>
  <a href="<?=SITE_URLI?>/admin-product?nav_typeid=<?php echo $this->data['nav_typeid'];?>&person_id=<?php echo $this->data['person_id'];?>"><?php echo $this->data['person']['person_name'];?>作品管理</a> >>
 作品增加</caption>
  <tr> 
    <td class="spec" width="15%" align="right">作品名称:</th> 
    <td><input name="title" type="text" id="title" size="50" maxlength="50" /></td> 
  </tr>
  <input type="hidden" name="person_id" value="<?php echo $this->data['person_id'];?>">
  <input type="hidden" name="nav_typeid" value="<?php echo $this->data['nav_typeid'];?>">
  <?php
	if($this->data['nav_typename'] == 'photographers' || $this->data['nav_typename'] == 'retouching')
	{
  ?>
   <tr> 
    <td class="spec" width="15%" align="right">作品分类:</th> 
    <td>
		<select name="cate_id">
			<?php
			foreach($this->data['person_catearray'] as $key=>$val){
				echo "<option value='".$val['cate_id']."' selected>".$val['name']."</option>";
			}

			?>
		</select>
	</td> 
  </tr>
  <?php
	}
  ?>

  <tr> 
    <td colspan='2'>
		<div class="upload_img" id="upload_area">
		<p style="padding-left:60px;"><a href="javascript:;" onclick="addUploadFileItem();">+增加上传项</a></p>
		<p><span>作品图片:</span> <input type="file" name="upload_img[]" rel='1'><span>作品图片缩略图:</span> <input type="file" name="thumb_img[]" rel="1"></p>
		<?php
		for($i=2;$i<30;$i++)
		{
		?>
			<p id="uparea_<?php echo $i;?>" style="display:none;">
				<span>作品图片:</span> <input type="file" name="upload_img[]" rel='<?php echo $i;?>'>
				<span>作品图片缩略图:</span> <input type="file" name="thumb_img[]" rel="<?php echo $i;?>">
				<a href='javascript:void(0);' rel='<?php echo $i;?>' onclick='delUploadFileItem(this);'>取消上传项</a>
			</p>
		<?php
		}
		?>
		</div>
	</td> 
  </tr>

  <tr> 
    <td class="spec" width="15%" align="right">作品介绍:</th> 
    <td>
		<div>
			<input type="hidden" id="bio_content" name="brief" value="" style="display:none" />
			<input type="hidden" id="content___Config" value="" style="display:none" />
			<iframe id="content___Frame" src="<?php echo PUBLIC_URL;?>fckeditor/editor/fckeditor.html?InstanceName=bio_content&Toolbar=Basic" width="510" height="120" frameborder="0" scrolling="no"></iframe>
		</div>
	</td> 
  </tr>


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
    <input type="submit" name="Submit" value="保存" />
    &nbsp;
    <input type="button" name="Submit2" value="返回"  onclick="history.go(-1);" />	
	</div>
  </td>
  </tr> 
  </form>

</table> 

<script>
function addUploadFileItem()
{
	upload_num++;
	$('#uparea_'+upload_num).css('display','');
}

function delUploadFileItem(obj)
{
	var reln = $(obj).attr('rel');
	$("#uparea_"+reln).remove();

}
</script>

<div style="margin-top:140px;height:20px;"></div>
</body>
</html>