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
　<form name="myform" id="myform" enctype="multipart/form-data" action="add_save" method="post" onsubmit="return article.save(this);">
  <caption>新增文章</caption>
   
  <tr> 
    <td class="spec" width="15%" align="right">标题:</th> 
    <td><input name="title" type="text" id="title" size="50" maxlength="50" /></td> 
  </tr>
  <tr> 
    <td class="spec" width="15%" align="right">关键字:</th> 
    <td><input name="keyword" type="text" id="keyword" size="50" />注:逗号分隔</td> 
  </tr>

  <tr> 
    <td class="spec" align="right">所属分类:</th> 
    <td>
	<select name="category_id" id="category_id">
	   <option value="0">所有分类</option>
       <?foreach($tree as $node){?>
       <option value="<?=$node['id']?>"><?=$node['name']?></option>
	   <?}?>	  
    </select>
    
	</td> 
  </tr>

  <tr> 
    <td class="spec" align="right">作者:</th> 
    <td><input name="author" type="text" id="author" value="匿名" size="20" maxlength="20" /></td> 
  </tr>

  <tr> 
    <td class="spec" align="right">来源:</th> 
    <td><input name="copyfrom" type="text" id="copyfrom" value="不详" size="20" maxlength="20" /></td> 
  </tr>
  <tr> 
    <td class="spec" align="right">权限:</th> 
    <td>
	<label> 
    <input name="state" type="checkbox" id="state" value="1" />已审核
    <input name="top" type="checkbox" id="top"  value="1" />置顶                     
    <input name="hot" type="checkbox" id="hot" value="1" />热门
    <input name="elite" type="checkbox" id="elite" value="1" />推荐
    </label>

	</td> 
  </tr>
  <tr> 
    <td class="spec" align="right">发布日期:</th> 
    <td>
	<label>
    <input name="pubtime" type="text" id="pubtime" onfocus="new WdatePicker(this,'%Y-%M-%D',true)" value="<?php echo date('Y-m-d H:i:s',time());?>" size="20"  />
    </label>
	</td> 
  </tr>

  <tr> 
    <td class="spec" align="right">点击次数:</th> 
    <td><input name="hit" type="text" id="hit" value="0" size="20" maxlength="10" /></td> 
  </tr>
  <tr> 
    <td class="spec" align="right">推荐到首页图片:</th> 
    <td><input name="image" type="file" id="image" size="50" /></td> 
  </tr>

  <tr> 
    <td class="spec" align="right">详细内容:</th> 
    <td>
		<div>
			<input type="hidden" id="content" name="content" value="" style="display:none" />
			<input type="hidden" id="content___Config" value="" style="display:none" />
			<iframe id="content___Frame" src="<?php echo PUBLIC_URL;?>fckeditor/editor/fckeditor.html?InstanceName=content&amp;Toolbar=Default" width="650" height="450" frameborder="0" scrolling="no"></iframe>
		</div>
	</td> 
  </tr>

  <tr> 
    <td class="spec" align="right">是否评论:</th> 
    <td><input name="iscomment" type="checkbox" id="iscomment" value="1" /></td> 
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
 
</body>
</html>