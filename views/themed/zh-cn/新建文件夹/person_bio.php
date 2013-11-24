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
					$bio_url = SITE_URLI.'/detail/bio?nav_typeid='.$nav_typeid.'&cate_id='.$this->data['cate_id'].'&person_id='.$this->data['person_id'];
						
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

		<?php
		$person = $this->data['person'];
		$img_url = out_img_url($person['headimg'],$this->data['nav_typeid']);	
		?>
		<div class="bio">
			<div class="bio_head"><img src='<?php echo $img_url;?>' style="width:138px;"></div>
			<div class="bio_desc">
			<?php echo $person['bio'];?>
			</div>				
		</div>
		
		<div class="clear"></div>



		<div class="footer"><img src='<?php echo PUBLIC_URL;?>images/footer.gif'></div>

	
	</div>
</div>
<!--内容 end-->


