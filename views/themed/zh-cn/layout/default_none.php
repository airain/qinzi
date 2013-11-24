<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="<?php echo $this->data['keywords'];?>" />
	<meta name="description" content="<?php echo $this->data['description'];?>" />
	<title><?php echo $this->data['title'];?></title>
	<?php echo $this->loadLink();?>	
	<?php echo $this->loadScript();?>

</head>

<body>


<?php 
	include $this->data['layout'];
?>

</body>
</html>
