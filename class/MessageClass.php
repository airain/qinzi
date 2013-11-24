<?php
class MessageClass
{
	public $msgFile   = null;

	public function __construct()
	{
	}

	public function __destruct()
	{
		$this->msgFile = null;
	}

	/**
	 +----------------------------------------------------------
	 * 显示消息提示框(带提示信息+跳转)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string $desc 消息文本
	 +----------------------------------------------------------
	 * @param  string  $url  跳转地址
	 +----------------------------------------------------------
	 */
	public function show($desc,$url,$n=0)
	{
		if($n == 1) $this->msgBox($desc,$url);
		if($n == 2) $this->noMsgBox($url);
		if($n == 3) $this->backMsgBox($desc);
		if(!file_exists($this->msgFile)) 
		{
			print("<br><br>$desc<br><br>");
			print("请稍后,系统正在自动跳转........"); 
			die("<meta http-equiv='Refresh' content='1; url=$url'>");
		}
		$fp = fopen($this->msgFile,'r');
		$fileSize = filesize($this->msgFile);
		$strHtml = fread($fp,$fileSize);
		fclose($fp);
		$strHtml = str_replace("\"","\\\"",$strHtml);
		$gotoUrl  = "<meta http-equiv='Refresh' content='1; url=$url'>";
		$tipInfo  = "$desc <br><br>请稍后,系统正在自动跳转........".$gotoUrl;		
		eval("\$strHtml=\"$strHtml\";");
		die($strHtml);
	}

	/**
	 +----------------------------------------------------------
	 * 显示消息提示框(JS提示)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string $msg 消息文本
	 +----------------------------------------------------------
	 * @param  string  $url 跳转地址
	 +----------------------------------------------------------
	 */
	public function msgBox($msg,$url)
	{
		print "<script language='javascript'>";
		print "alert('$msg');location.href='$url';";
		print "</script>";
		die();
	}

	/**
	 +----------------------------------------------------------
	 * 地址跳转(JS不带提示信息)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string  $url 跳转地址
	 +----------------------------------------------------------
	 */
	public function noMsgBox($url)
	{
		print "<script language='javascript'>";
		print "location.href='$url';";
		print "</script>";
	}

	public function backMsgBox($msg)
	{
		print "<script language='javascript'>";
		print "alert('$msg');history.go(-1);";
		print "</script>";
		die();
	}
}
?>
