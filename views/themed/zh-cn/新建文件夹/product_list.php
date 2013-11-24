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
					if(stristr($url,'bio')) $css = 'sub_menu_current';
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
		<ul class="main_list" id="product_list">
			<?php
			if(isset($this->data['list'])){
				foreach($this->data['list'] as $key=>$val){
					$img_link = SITE_URLI.'/detail?nav_typeid='.$val['nav_typeid'].'&person_id='.$val['person_id'];
					$img_link .= '&cate_id='.$val['cate_id'].'&product_id='.$val['id'];
			?>
			<li class='pic'>
				<div></div>
				<dl style='display:none;'>
					<?php
					if($val['imgs']){
						foreach($val['imgs'] as $k=>$v){
							$dd_css = '';
							if($k==1) $dd_css = 'yes';
							$img_url = out_img_url($v['thumb_image'],$val['nav_typeid']);						
					?>
						<dd data-current="<?php echo $dd_css;?>" data-tip=<?php echo $k;?>>
							<a href='<?php echo $img_link;?>'><img src="<?php echo $img_url;?>"></a>
						</dd>
					<?php
						}
					}
					?>
				</dl>
			</li>
			<?php
				}
			}
			?>
		</ul>
		
		<?php
		if($this->data['pct']>1){
		?>
		<div class="page"> <?php echo $this->data['page_str'];?></div>
		<?php
		}
		?>

		<div class="footer"><img src='<?php echo PUBLIC_URL;?>images/footer.gif'></div>

	</div>
</div>
<!--内容 end-->

<script>
function changeImg(obj)
{
	var that = obj;
	var img_cnt = 0;
	$(that).find('dd').each(function(i){
		img_cnt++;
	})
	var v_obj = $(that).find('dd[data-current="yes"]').attr('data-tip');
	var v_obj_c = $(that).find('dd[data-current="yes"]').html();
	next_obj = parseInt(v_obj)+1;
	if(next_obj==img_cnt){
		next_obj = 0;
	}

	$(obj).find('div').fadeOut(2000,function(){
		$(that).find('div').html(v_obj_c);	
		$(that).find('div').fadeIn(2000);
	});
	
	$(that).find('dd[data-tip='+v_obj+']').attr('data-current','');
	$(that).find('dd[data-tip='+next_obj+']').attr('data-current','yes');
}


$(function(){
	$('.pic').each(function(){
		var that = this;

		var div_c = $(that).find('dd[data-tip=0]').html();
		$(that).find('div').html(div_c);

		var my_interval
		$(this).find('div').one('mouseover',function(){
			my_interval = setInterval(function(){
			changeImg(that);
			},1000);
		});

		$(this).find('div').bind('mouseout',function(){
			clearInterval(my_interval);
			$(that).find('div').one('mouseover',function(){
				my_interval = setInterval(function(){
					changeImg(that);
				},1000);
			});
		});	
	})

})

</script>