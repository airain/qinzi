<?php $user = $this->data['userinfo']; ?>
<style>
div{ border:1px solid #DDD; padding:5px 10px;}
</style>
<!-- login -->
<a href="/signin/signin_sns/?type=sina">sina</a>

<!--基本资料-->
<div id="baseinfo">
<form action="ajax/mod_user_base" method="post">
	<p>邮箱：<?php echo $user['email'];?></p>
	<p>昵称：<?php echo $user['nick'];?></p>
	<p>性别：
			<fieldset id="genderBox">
					<input type="radio" name="gender" <?php echo $user['gender']=="男"?'checked':'';?> value="男"/> 男
					<input type="radio" name="gender" <?php echo $user['gender']=="女"?'checked':'';?> value="女"/> 女
			</fieldset>
	</p>
	<br/>
	<p>宝宝状态：
			<fieldset id="babyStateBox">
					<input type="radio" name="babyState" <?php echo $user['baby_state']=="1"?'checked':'';?> value="1"/> 已有宝贝
					<input type="radio" name="babyState" <?php echo $user['baby_state']=="2"?'checked':'';?> value="2"/> 已怀孕
					<input type="radio" name="babyState" <?php echo $user['baby_state']=="3"?'checked':'';?> value="3"/> 准备怀孕
			</fieldset>
	</p>
	<p>宝宝姓名：<input type="text" name="babyName" value="<?php echo $user['baby_name'];?>"/></p>
	<p>宝宝生日：<input type="text" name="babyBirth" value="<?php echo $user['baby_birth'];?>"/></p>
	<p>宝宝性别：
		<fieldset id="babyGenderBox">
					<input type="radio" name="babyGender" <?php echo $user['baby_sex']=="男"?'checked':'';?> value="男"/> 男
					<input type="radio" name="babyGender" <?php echo $user['baby_sex']=="女"?'checked':'';?> value="女"/> 女
			</fieldset>
	</p>
	<p><input type="submit" name="submit" value="save"/></p>
</form>
</div>
<!--基本资料 end-->

<!--修改密码-->
<div id="pwdinfo">
<form action="ajax/mod_user_pwd" method="post">
	
	<p>旧密码：<input type="text" name="oldpwd" value=""/></p>
	<p>新密码：<input type="text" name="newpwd" value=""/></p>
	<p>确认密码：<input type="text" name="renewpwd" value=""/></p>
	<p><input type="submit" name="submit" value="save"/></p>
</form>
</div>
<!--修改密码 end-->


<!--修改邮件地址-->
<div id="postinfo">
<form action="ajax/mod_user_post" method="post">
	
	<p>真实姓名：<input type="text" name="realname" value="<?php echo $user['realname'];?>"/></p>
	<p>手机号：<input type="text" name="mobile" value="<?php echo $user['mobile'];?>"/></p>
	<p>邮寄地址：<input type="text" name="address" value="<?php echo $user['address'];?>"/></p>
	<p>邮编：<input type="text" name="postcode" value="<?php echo $user['postcode'];?>"/></p>
	<p><input type="submit" name="submit" value="save"/></p>
</form>
</div>
<!--修改邮件地址 end-->
