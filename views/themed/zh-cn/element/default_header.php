<!--头 -->
<?php
$nav_typeid = $this->data['nav_typeid'];
$nav_typename = $this->data['nav_typename'];
$url = $_SERVER['REQUEST_URI'];
?>
<div class="head">
	<div class="head_logo">
		<a href='<?php echo SITE_URLI;?>' onFocus="this.blur()"><img src='<?php echo PUBLIC_URL;?>images/head_logo.gif'></a>
	</div>
	<div class="head_menu">
		<ul>
			<li class="h_models<?php if($nav_typeid==1) echo "_c";?>"><a href='<?php echo SITE_URLI;?>/list?nav_typeid=1'  onFocus="this.blur()"></a></li>
			<li><img src='<?php echo PUBLIC_URL;?>images/head_tag_line20.gif'></li>
			<li class="h_celebrities<?php if($nav_typeid==2) echo "_c";?>"><a href='<?php echo SITE_URLI;?>/list?nav_typeid=2'  onFocus="this.blur()"></a></li>
			<li><img src='<?php echo PUBLIC_URL;?>images/head_tag_line20.gif'></li>
			<li class="h_photographers<?php if($nav_typeid==3) echo "_c";?>"><a href='<?php echo SITE_URLI;?>/list?nav_typeid=3' onFocus="this.blur()"></a></li>
			<li><img src='<?php echo PUBLIC_URL;?>images/head_tag_line20.gif'></li>
			<li class="h_retouching<?php if($nav_typeid==4) echo "_c";?>"><a href='<?php echo SITE_URLI;?>/list?nav_typeid=4' onFocus="this.blur()"></a></li>

			<li><img src='<?php echo PUBLIC_URL;?>images/head_tag_line20.gif'></li>
			<li class="h_about<?php if(stristr($url,'about')) echo "_c";?>"><a href='<?php echo SITE_URLI;?>/about' onFocus="this.blur()"></a></li>
			<li><img src='<?php echo PUBLIC_URL;?>images/head_tag_line20.gif'></li>
			<li class="h_contact<?php if(stristr($url,'contact')) echo "_c";?>"><a href='<?php echo SITE_URLI;?>/contact' onFocus="this.blur()"></a></li>
		</ul>
	</div>
</div>
<!--头 end-->