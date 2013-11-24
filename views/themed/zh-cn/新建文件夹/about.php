<!--内容-->
<div class="bd">
	<div class="left_menu">
		<div class="leftmargin"></div>
		<div class="menu">
		 <div class="menu_main">
			<h2><img src="<?php echo PUBLIC_URL;?>images/l_about_r.gif"></h2>
		 </div>
		</div>
	</div>

	<div class="main">
		<h2></h2>
		<div class="about">
			<div class="about_img"><img src='<?php echo PUBLIC_URL.$this->data['article']['image'];?>'></div>
			<div class="about_desc">
				<?php echo $this->data['article']['content'];?>
			</div>				
		</div>
		
		<div class="clear"></div>

		<div class="footer"><img src='<?php echo PUBLIC_URL;?>images/footer.gif'></div>

	</div>
</div>
<!--内容 end-->