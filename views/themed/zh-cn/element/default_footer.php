<!--底 -->
<div class="ft">
	<div class="ft_nav">
    	<ul>
        	<li><a href="<?php echo SITE_URLI;?>/news/detail?id=7">网站地图</a></li>
        	<li>|</li>
        	<li><a href="<?php echo SITE_URLI;?>/news">最新动态</a></li>
        	<li>|</li>
        	<li><a href="<?php echo SITE_URLI;?>/shop">门店展示</a></li>
        	<li>|</li>
        	<li><a href="<?php echo SITE_URLI;?>/member">会员专区</a></li>
        	<li>|</li>
        	<li><a href="<?php echo SITE_URLI;?>/job">工作机会</a></li>
        	<li>|</li>
        	<li><a href="<?php echo SITE_URLI;?>/news/detail?id=4">联系我们</a></li>
        </ul>
    </div>
    <div class="ft_login">
    	<div>
			<form onsubmit="return checkInput()" method="post" target="_blank" action="https://exmail.qq.com/cgi-bin/login" name="form1">
			<input type="hidden" value="false" name="firstlogin">
			<input type="hidden" value="dm_loginpage" name="errtemplate">
			<input type="hidden" value="other" name="aliastype">
			<input type="hidden" value="bizmail" name="dmtype">
			<input type="hidden" value="" name="p">
        	<b><a href="#">登录邮箱</a></b>
            <span>帐号：</span>
			<input type="text" value="" class="ft_login_txt" name="uin"><span>@idragonstar.com&nbsp;&nbsp;</span>
			<input type="hidden" value="idragonstar.com" name="domain">
			<span>密码：</span>
			<input type="password" value="" class="ft_login_txt" name="pwd">

			<input type="image" onclick="this.form.submit();" value="登录" style="width:52px;height:22px;" name="" src="<?php echo PUBLIC_URL;?>images/a_name_btn.gif">

            <a href="https://exmail.qq.com/cgi-bin/readtemplate?check=false&amp;t=bizmail_orz" target="_blank">忘记密码</a>
			</form>
        </div>
    </div>
    <div class="ft_copy" style="text-align:center;">
    	Copyright © 2011 Dragonstar Inc. 北京英龙华辰科技有限公司 版权所有  京ICP备050123号
		<br>
		网站技术支持:<a href="http://www.apexcn.net/" target="_blank">创联天下</a>
    </div>
</div>
<!--底end -->