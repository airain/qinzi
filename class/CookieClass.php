<?php
/*
 *CookieClass.php cookie类
 *skying 2011/4/15
 */

class CookieClass
{

	//cookie 过期时间
	private $expire = 86400;
	
	//cookie 保存路径
	private $path   = '/';
	
	//cookie 所在域名
	private $domain = SITE_DOMAIN;

	//类的构造子
	public function __construct()
	{
		$this->domain = SITE_DOMAIN;

	}

	//类的析构方法(负责资源的清理工作)
	public function __destruct()
	{
		$this->dataInput = null;
	}

	//属性访问器(读)
	public function __get($name)
	{
		if(property_exists($this,$name))
		{
			return $this->$name;
		}
		return null;
	}

	//取cookie
	public function getCookie($key)
	{
		if(isset($_COOKIE[$key]) && $_COOKIE[$key])
		{
			return $_COOKIE[$key];  
		}
		else
		{
			return null;
		}
	}

	//更新或者是增加cookie
	public function updateCookie($key,$value,$expire=86400)
	{
		if(!empty($value)) $value = $value;
		if($expire>0) {
			setcookie($key,$value,time() + $expire,$this->path,$this->domain);
		}
		else {
			setcookie($key,$value,0,$this->path,$this->domain);
		}
	}

	//cookie加密
	public function encryptCookie()
	{

	}

	//cookie解密
	public function decryptCookie()
	{

	}


}
?>
