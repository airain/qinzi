<!--内容-->
<?php 
$nav_typeid = $this->data['nav_typeid'];
$nav_typename = $this->data['nav_typename'];
$url = $_SERVER['REQUEST_URI'];
if(stristr($url,'about'))
	$lmenu_c = 'about';
elseif(stristr($url,'contact'))
	$lmenu_c = 'contact';
else 
	$lmenu_c = $nav_typename;
?>
<div class="bd">
	<div class="left_menu">
		<div class="leftmargin"></div>
		<div class="menu">
		 <div class="menu_main">
			<h2><img src="<?php echo PUBLIC_URL;?>images/l_<?php echo $lmenu_c;?>_r.gif"></h2>
			<div class="narrow_down"><img src='<?php echo PUBLIC_URL;?>images/l_menu_narrow_down.gif'></div>
			<ul>
				<?php
				if(isset($this->data['person_list'])){
					foreach($this->data['person_list'] as $key=>$val){
						$css = '';
						if($val['id'] == $this->data['person_id']) $css = 'lmenuc';
				?>
				<li>
					<a href='<?php echo SITE_URLI;?>/list?nav_typeid=<?php echo $nav_typeid;?>&person_id=<?php echo $val['id'];?>' class='<?php echo $css;?>'>
						<?php echo ucfirst($val['person_name']);?>
					</a>
				</li>
				<?php
					}
				}
				?>
			</ul>
		 </div>
		</div>
	</div>

	<div class="main">
		<h2>
			<?php
				if(isset($this->data['person_id']) &&( $nav_typename == 'photographers' || $nav_typename == 'retouching')){
					$css = '';
					if(empty($this->data['cate_id'])) $css = 'sub_menu_current';
					$bio_url = SITE_URLI.'/detail/bio?nav_typeid='.$nav_typeid.'&person_id='.$this->data['person_id'];
						
			?>
				<a href='<?php echo $bio_url;?>' class="<?php echo $css;?>">Bio</a><img src='<?php echo PUBLIC_URL;?>images/sub_menu_tag.gif'>
			<?php
				if(isset($this->data['person_cate'])){
					$str = '';
					foreach($this->data['person_cate'] as $key=>$val){
						$current_css = $tag = '';
						if($key>0) $tag = ' <img src=\''.PUBLIC_URL.'images/sub_menu_tag.gif\'> ';
						if($this->data['cate_id'] == $val['cate_id']) $current_css = " sub_menu_current ";
						$urli = SITE_URLI.'/list?nav_typeid='.$nav_typeid.'&person_id='.$this->data['person_id'];
						$str .= $tag."<a href='".$urli."&cate_id=".$val['cate_id']."' class='".$current_css."'>".ucfirst($val['name'])."</a>";
					}
					echo $str;
				}
			}
			?>
		</h2>
		<div class="show_img">
			<div class="img_list">
				<ul style="left:0px;position:absolute;" class="img_list_inner">
					<?php
					if(isset($this->data['product_imgs'])){
						foreach($this->data['product_imgs'] as $key=>$val){
							$img_url = out_img_url($val['thumb_image'],$this->data['nav_typeid']);		
					?>
						<li><img src="<?php echo $img_url;?>"></li>
					<?php
						}
					}
					?>
				</ul>
			</div>
			<div class="drag_slider" id="container">
				<div class="drag_point" style="left:0px; position:absolute; cursor:pointer;" id="d1"> </div>
			</div>
		</div>

		<div class='models_info'>
		<?php
		if($nav_typename=='models'){
			$p = $this->data['person'];
		?>
		
			<span>国籍：<?php echo $p['nationality'];?></span>
			<span>WAIST：<?php echo $p['waist'];?>CM</span>
			<span>HIPS：<?php echo $p['hips'];?>CM</span>
			<span>SHOES：<?php echo $p['shoes'];?></span>
			<span>HAIR：<?php echo $p['hair'];?></span>
			<span>EYES：<?php echo $p['eyes'];?></span>
		<?php
		}
		?>
		</div>

		<div class="footer"><img src='<?php echo PUBLIC_URL;?>images/footer.gif'></div>

	
	</div>
</div>
<!--内容 end-->


<script type="text/javascript">
window.onload = function(){
	var container = document.getElementById('container');
	var ele = document.getElementById('d1');
	var bodyWidth = container.offsetWidth,
	bodyHeight = container.offsetHeight;
	var maxX = bodyWidth - ele.offsetWidth;
	var maxY = bodyHeight - ele.offsetHeight ;

	var img_list_inner_width = 0;
	$('.img_list_inner img').each(function(){
		img_list_inner_width = img_list_inner_width + $(this).width();
	})
	$('.img_list_inner').css('width',img_list_inner_width);

	var out_img_width = $('.img_list_inner').width();
		//精华所在,out_img_width是所有图片的宽度，但是最终的图片显示宽度应该减去外容器的宽度。
		out_img_width = out_img_width-($('.img_list').width())
	var line_width = $('#container').width();
	var drag_rate = out_img_width/maxX; 

	var dd = new Dragdrop({
		target : ele,
		area : [0,maxX,0,maxY],
		callback : function(obj){
			var drag_length = obj.moveX * drag_rate;
			$('.img_list_inner').css('left','-'+drag_length+'px');
		}
	});
}
</script>