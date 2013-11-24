<?php

/**
 * 微博 链接
 * @param array $data 传递参数数组
 */
function getWeiboLinks(array $data=array(), $type='') {
    $query_str = empty($data) ? '' : '&' . http_build_query($data, 'myvar_');
    $links['space'] = array('name' => 'space', 'title' => 'QQ', 'alt' => 'QQ', 'link' => '/signin/signin_sns/?type=space' . $query_str);
    $links['sina'] = array('name' => 'sina', 'title' => '新浪微博', 'alt' => '新浪微博', 'link' => '/signin/signin_sns/?type=sina' . $query_str);
    $links['qq'] = array('name' => 'qq', 'title' => '腾讯微博', 'alt' => '腾讯微博', 'link' => '/signin/signin_sns/?type=qq' . $query_str);
    //$links['taobao'] = array('name' => 'taobao', 'title' => '淘宝帐号', 'alt' => '', 'link' => '/signup/signin_sns/?type=taobao' . $query_str);
    //$links['douban'] = array('name'=>'douban','title'=>'豆瓣帐号','link'=>'/signup/signin_sns/?type=douban'.$query_str);
    //$links['renren'] = array('name'=>'renren','title'=>'人人帐号','link'=>'/signup/signin_sns/?type=renren'.$query_str);
    if (empty($type)) {
        unset($links['qq']);
        return $links;
    }
    return $links[$type];
}


//新浪表情切换qq表情
function processEmtSinaToQQ($content) {
    $filename = COMMON_DIR . '/api_file/emotion_sina_qq.php';
    if (file_exists($filename)) {
        $emotion = (array) json_decode(file_get_contents($filename));
        $sina_face = array_keys($emotion);
        $qq_face = array_values($emotion);
        return str_replace($sina_face, $qq_face, $content);
    }
    return $content;
}

//处理表情图片路径 从图片->文字
function processEmotionImageSingle($contents) {
    $emotion_path = PUBLIC_URL . 'images/emotion/';
    if (!isset($GLOBALS['emotion_maps'])) {

        $path = CONFIG_PATH . '/api_file/';
        $ejson_file = $path . 'emotion_file.php';
        $emotion = json_decode(file_get_contents($ejson_file));

        $n_emotion = array();
        foreach ($emotion as $key => $val) {
            $n_emotion = array_merge($n_emotion, (array) $val);
        }
        $GLOBALS['emotion_maps'] = $n_emotion;
    }
    else
        $n_emotion = $GLOBALS['emotion_maps'];

    $rs = preg_match_all("/\[.*\]/U", $contents, $out);
    if ($rs > 0) {
        foreach ($out[0] as $k => &$v) {
            $rv = str_replace('[', '［', $v);
            $rv = str_replace(']', '］', $v);
            $replace2[$k] = $rv;
            if (isset($n_emotion[$v]))
                $replace[$k] = "<img src=$emotion_path" . $n_emotion[$v] . " title='" . $rv . "' alt='" . $rv . "' />";
            else
                $replace[$k] = $rv;
        }
        $contents = str_replace($out[0], $replace, $contents);
        $contents = str_replace($replace2, $out[0], $contents);
    }
    return $contents;
}

//------------------------

function mysql_escape_mimic($inp) { 
    if(is_array($inp)) 
        return array_map(__METHOD__, $inp); 

    if(!empty($inp) && is_string($inp)) { 
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
    } 

    return $inp; 
} 


function OptionArray($a=array(), $c1, $c2) {
	if (empty($a)) return array();
	$s1 = GetColumn($a, $c1);
	$s2 = GetColumn($a, $c2);
	if ( $s1 && $s2 && count($s1)==count($s2) ) {
		return array_combine($s1, $s2);
	}
	return array();
}


function GetColumn($a=array(), $key='id', $value='name')
{
	$ret = array();
	foreach( $a AS $one )
	{   
		$ret[$one[$key]] = $one[$value] ;
	} 
	return $ret;
}
function output_gender($gender)
{
	if($gender ==1)
		return '男';
	elseif($gender == 2)
		return '女';
	else
		return '保密';
}

function out_img_url($src,$nav_typeid)
{
	if(empty($src)) return false;
	switch ($nav_typeid) {
		case 1:
			$path = MODELS_IMG_URL;
			break;
		case 2:
			$path = CELEBRITIES_IMG_URL;
			break;
		case 3:
			$path = PHOTOGRAPHERS_IMG_URL;
			break;
		case 4:
			$path = RETOUCHING_IMG_URL;
			break;			
	}
	$img_url = $path.$src;
	return $img_url;
}

function getUpImageThumbPath($str)
{
	if(!empty($str))
	{
		$filetype = strrchr($str,'.');
		$filebody = substr($str,0,-strlen($filetype));
		$str = $filebody."_thumb".$filetype;
	}
	return $str;
}

function editor($name='detail',$value='',$width='650',$height=450)
{
	require_once(PUBLIC_PATH."fckeditor/fckeditor.php") ;
	$oFCKeditor = new FCKeditor($name);
	$oFCKeditor->BasePath = PUBLIC_URL.'fckeditor/';
	$oFCKeditor->ToolbarSet = 'Default' ; //Basic Default
	$oFCKeditor->InstanceName = $name;
	$oFCKeditor->Width = $width;
	$oFCKeditor->Height = $height;
	$oFCKeditor->Value = $value;
	return $oFCKeditor->CreateHtml();
}
/**
 +----------------------------------------------------------
 * 字符串切取
 +----------------------------------------------------------
 * @param  string  $str    切取的字符串
 +----------------------------------------------------------
 * @param  string  $strlen 切取的长度
 +----------------------------------------------------------
 * @param  string  $other  是否显示省略标志
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function output($str,$strlen=30,$other=true)
{
	/*
	$j = 0;
	for($i=0;$i<$strlen;$i++) if(ord(substr($str,$i,1))>0xa0) $j++;
	if($j%2!=0) $strlen--;
	$rstr=substr($str,0,$strlen);
	if (strlen($str)>$strlen && $other) $rstr.='...';
	return $rstr;
	*/

	for($i=0;$i<$strlen;$i++){
	   $temp_str=substr($str,0,1);
	   if(ord($temp_str) > 127){
	    $i++;
	    if($i<$strlen){
	     $new_str[]=substr($str,0,3);
	     $str=substr($str,3);
	    }
	   }else{
	    $new_str[]=substr($str,0,1);
	    $str=substr($str,1);
	   }
	}
	$rstr = join($new_str);
	
	if (strlen($rstr)>$strlen && $other) $rstr.='...';
	return $rstr;
}

function get_refer_url()
{
	return $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"] ;//这个可以得到带参数的地址 
}
/* HTTP Request functions */
function get_int($name,$default_value=0)
{
	global $_REQUEST;
	$value = $default_value;
	if(isset($_REQUEST[$name]))
	{
		$value = intval($_REQUEST[$name]);
		if (0 == $value)
			$value = $default_value;
	}
	return $value;
}

/* HTTP Request functions */
function get_str($name,$default_value="")
{
	global $_REQUEST;
	$value = $default_value;
	if(isset($_REQUEST[$name]))
	{
		$value = $_REQUEST[$name];
	}
	return forbid_sql_in($value);
}


function closetags($html){
	//不需要补全的标签
	$arr_single_tags = array('meta','img','br','link','area');
	//匹配开始标签
	preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU',$html,$result);
	$openedtags = $result[1];
	//匹配关闭标签
	preg_match_all('#</([a-z]+)>#iU',$html,$result);
	$closedtags = $result[1];
	//计算关闭开启标签数量，如果相同就返回html数据
	$len_opened = count($openedtags);
	if(count($closedtags) == $len_opened){
		return $html;
	}
	//反排序数组，将最后一个开启的标签放在最前面
	$openedtags = array_reverse($openedtags);
	//遍历开启标签数组
	for($i=0; $i<$len_opened; $i++){
		//如果标签不属于需要补全的标签
		if(!in_array($openedtags[$i],$arr_single_tags)){
			//如果这个标签不在关闭的标签中
			if(!in_array($openedtags[$i],$closedtags)){
				//如果在这个标签之后还有开启的标签
				if(isset($openedtags[$i+1]) && $next_tag = $openedtags[$i+1]){
					//将当前标签放在下一个标签的关闭标签的前面					
					$html = preg_replace('#</'.$next_tag.'#iU', '</'.$openedtags[$i].'></'.$next_tag ,$html);
				}else{
					//直接补全闭合标签
					$html .= '</'.$openedtags[$i].'>';
				}
			}
		}
	}
	return $html;
}

/* HTTP Request functions */
function get_req_int($name,$default_value=0)
{
	global $_REQUEST;
	$value = $default_value;
	if(isset($_REQUEST[$name]))
	{
		$value = intval($_REQUEST[$name]);
		if (0 == $value)
			$value = $default_value;
	}
	return $value;
}

/* HTTP Request functions */
function get_req_str($name,$default_value="")
{
	global $_REQUEST;
	$value = $default_value;
	if(isset($_REQUEST[$name]))
	{
		$value = $_REQUEST[$name];
	}
	return forbid_sql_in($value);
}

/* Get micro time in second */
function get_micro_time()
{
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

/*
extract Chinese words from a long string for GB2312
*/
function csub_str($str,$start,$len) 
{ 
	if($str=="") return "";
	$strlen=strlen($str); 
	$clen=0; 
	$tmpstr = "";

	for($i=0;$i<$strlen;$i++,$clen++)
	{
		if ($clen>=$start+$len)
			break;

		if(ord(substr($str,$i,1))>0xa0)
		{
			if ($clen>=$start)
			$tmpstr.=substr($str,$i,2);
			$i++;
		}
		else
		{ 
			if ($clen>=$start)
				$tmpstr.=substr($str,$i,1);
		}
	}
	if ($clen < $strlen)
		$tmpstr .= "...";

	return $tmpstr;
}

function get_ip() 
{ 
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		$onlineip = getenv("HTTP_CLIENT_IP");
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		$onlineip = getenv("HTTP_X_FORWARDED_FOR");
	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		$onlineip = getenv("REMOTE_ADDR");
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		$onlineip = $_SERVER['REMOTE_ADDR'];
	else
		$onlineip = "unknown";
	return $onlineip;
} 


/**
 * 得到浏览器地址中的地址
 */
function getLocationUrl() {
	$serverName = $_SERVER['SERVER_NAME']; //  www.my.com
	$serverPort = $_SERVER['SERVER_PORT']; //  80
	$serverUri  = $_SERVER['REQUEST_URI']; //  /myphp/test.php?a=1
	if (strlen($serverName) && strlen($serverUri)) {
		$fullUrl .= "http://" . $serverName;
		if ($serverPort != 80) $fullUrl .= ":$serverPort";
		$fullUrl .= $serverUri;
	}
	return $fullUrl;
}

/**
 * 判断是否是来自于本站的提交
 *
 * @param bool $bAllow true or false
 * @return bool true or false
 */
function submitCheck($bAllow = false) {
	if ($bAllow || 
		(preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", 
		$_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))) {
		return true;
	} 
	return false;
}

/**
 * 生成随机数
 * 
 * @param int $length  生成的随机数的长度
 * @param int $numeric 是否生成数字随机数的标志
 */
function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	} else {
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}

/**
 * set the page charset
 * 
 * @param string $sContentType 内容类型，如text/html,text/xml等
 * @param string $sCharset 字符类型，如utf-8,gb2312等
 */
function setPageCharset($sContentType = "text/xml", $sCharset = 'utf-8') {
	$header = sprintf("content-type:%s; charset=%s", $sContentType, $sCharset);
	@header($header);
}



/**
 * 计算当前日期几天以前或几天以后日期的函数,正数表示后几天,负数表示前几天
 * 返回的日期格式为yyyy-mm-dd hh:ii:ss
 *
 * @param int $day
 * @param string $date(YYYY-mm-dd HH:ii:ss或时间)
 * @param bool $timestampFlag 是返回时间戳还是返回日期时间字符串的标志
 * @param string $format 返回的日期时间的格式
 *
 * @return string 日期时间字符串 
 */
function subDate($day, $date = "", $timestampFlag = true, $format = "Y-m-d H:i:s") { 
	$now = time();
	if (!empty($date)){
		/* 不是时间截格式，是YYYY-mm-dd HH:ii:ss格式，将它们转换成时间戳格式 */
		if (!is_numeric($date)) {
			$datetime_array = explode(" ", $date);
			$date_array = explode("-", $datetime_array[0]);
			$time_array = explode(":", $datetime_array[1]);
			$now = mktime($time_array[0], $time_array[1], $time_array[2], $date_array[1], $date_array[2], $date_array[0]);
		} else {
			$now = $date;
		}
	}
	$m = $day * 24 * 60 * 60;
	$diff = $now + $m; 
	if ($timestampFlag) {
		return $diff;
	} else {
		if (empty($format)) $format = "Y-m-d H:i:s";
		$oldday = date($format, $diff);
		return $oldday;
	}
}


/**
变量批量过滤
*/
function stripslashes_array($array) {
	return is_array($array) ? array_map('stripslashes_array', $array) : stripslashes($array);
}

/**
取文件扩展名
*/
function fileext($filename) {
	return trim(substr(strrchr($filename, '.'), 1, 10));
}

//验证是否为指定长度的字母/数字组合 
function isletter($num1,$num2,$str) 
{ 
    return (preg_match("/^[a-zA-Z0-9]{".$num1.",".$num2."}$/",$str))?true:false; 
} 

//验证是否为指定长度数字 
function isnumeral($num1,$num2,$str) 
{ 
    return (preg_match("/^[0-9]{".$num1.",".$num2."}$/i",$str))?true:false; 
}  
//验证是否为指定长度汉字 
function isfont($num1,$num2,$str) 
{ 
    return (preg_match("/^([\x81-\xfe][\x40-\xfe]){".$num1.",".$num2."}$/",$str))?true:false; 
} 
//验证身份证号码 
function isstatus($str) 
{ 
    return (preg_match('/(^([\d]{15}|[\d]{18}|[\d]{17}x)$)/',$str))?true:false; 
} 

 //验证邮件地址 
function isemail($str){ 
    return (preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/',$str))?true:false; 
} 
 //验证电话号码 
function isphone($str) 
{ 
    return (preg_match("/^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/",$str))?true:false; 
} 
 //验证邮编 
function iszipcode($str) 
{ 
  return (preg_match("/^[1-9]\d{5}$/",$str))?true:false; 
} 
 //验证url地址 
function isurl($str) 
{ 
  return (preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/",$str))?true:false; 
}  



//---------------
//获得当前的页面文件的url
//----------------
function GetCurUrl()
{
	if(!empty($_SERVER["REQUEST_URI"]))
	{
		$nowurl = $_SERVER["REQUEST_URI"];
		$nowurls = explode("?",$nowurl);
		$nowurl = $nowurls[0];
	}
	else
	{
		$nowurl = $_SERVER["PHP_SELF"];
	}
	return $nowurl;
}


/*
2005-01-01 
=> 05-01-01 or 01-01
$length [8 or 5] 
*/
function get_short_date($str,$right_length=5)
{
	if(strlen($str)>=10)
	{
		$str = substr($str,0,10);
		return substr($str,10-$right_length);
	}
	else 
	{
		return $str;
	}
}

/* 
String conversion (gb2312 => utf8)
*/
function gb2312_to_utf8($str)
{
	return @iconv("GB2312", "UTF-8", $str);
}
function gbk_to_utf8($str)
{
	return iconv("GBK", "UTF-8", $str);
}
/* 
String conversion (utf8 => gb2312)
*/
function utf8_to_gb2312($str)
{
	return iconv("UTF-8", "GB2312", $str);
}
function utf8_to_gbk($str)
{
	return iconv("UTF-8", "GBK", $str);
}
/* XML String Encode*/
function encode_xml_string($str)
{
	$invalid_chars = array('&'=>'&amp;','<'=>'&lt;','>'=>'&gt;','\''=>'&apos;','\"'=>'&quot;');
	foreach($invalid_chars as $key=>$val)
	{
		$str = str_replace($key,$val,$str);
	}
	return $str;
}
/* XML String Decode */
function decode_xml_string($str)
{
	$invalid_chars = array('&'=>'&amp;','<'=>'&lt;','>'=>'&gt;','\''=>'&apos;','\"'=>'&quot;');
	foreach($invalid_chars as $key=>$val)
	{
		$str = str_replace($val,$key,$str);
	}
	return $str;
}

function to_xml($str)
{
	return encode_xml_string($str);
}

function utf8_strlen($string)
{
  return strlen(utf8_decode($string));
}
 
function utf8_substr($str,$from,$len)
{
	// 添加尾部...
	$suffix = ($len - $from < utf8_strlen($str)) ? "..." : "";
	$str = preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s','$1',$str);
	return $str .= $suffix;
}

function extract_filename($full_path)
{
	$sp = '/';
	if(strstr($full_path,"\\")!=null)
		$sp = '\\';
	else if(strstr($full_path,"/")!=null)
		$sp = '/';
	else 
		return $full_path;
	$filename = substr(strrchr($full_path, $sp), 1 );
	return $filename;
}

function extract_filepath($full_path)
{
	$sp = '/';
	if(strstr($full_path,"\\")!=null)
		$sp = '\\';
	else if(strstr($full_path,"/")!=null)
		$sp = '/';
	else 
		return $full_path;
	$filename = substr($full_path,0,strrpos($full_path, $sp)+1);
	return $filename;
}

function extract_fileprotocol($full_path)
{
	$filename = "";
	if(strlen($full_path)>5)
		$filename = substr($full_path,0,strpos($full_path, "://"));
	return $filename;
}

function get_friend_number($number)
{
	if($number==0)
		return 0;
	if($number<1 && $number>-1)
		return sprintf("%.2f",$number);
	else if(is_float($number))
		return number_format($number,1,'.',',');
	else
		return number_format(intval($number));	

}

function get_percent($float_number,$total_number)
{
	return (0 != $total_number) 
		? sprintf("%.2f%%", ($float_number / $total_number) * 100)
		: "0%";
}

function dbfilter($str)
{
	return mysql_real_escape_string($str);
}


function get_field_url($url,$max=40)
{
	$url = preg_replace("/http[s]?:\/\//","",$url);
	$value_show = (strlen($url)>$max)? ("..".substr($url,(0-$max))):$url;
	return $value_show;
}


/* user it to get html */
function get_curl_html($url, $heaer = FALSE)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, $heaer);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
	if (!strstr($url,"google"))
	{
		curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);      
	}
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$html = curl_exec($ch);
	curl_close($ch);

	return $html;
}

// isbr = 0 返回包括换行的html, once 匹配次数(preg_match_all, preg_match)
function get_data($pattern, $url, $once = 0, $br = 0)
{
	$outer = array(0);
	if ($html = get_curl_html($url))
	{
		if (!$br)
			$html = str_replace(chr(10), '', $html);
		if (!$once)
			preg_match_all($pattern, $html, $outer, PREG_PATTERN_ORDER);
		else
			preg_match($pattern, $html, $outer);
	}
	return $outer;
}


function md_encoding($content)
{
	if (mb_strtolower(mb_detect_encoding($content, "UTF-8, gb2312, gbk")) != "utf-8")
		$content = gb2312_to_utf8($content);
	return $content;
}

// 去除http和尾部/
function url_format($url)
{
	return preg_replace("/^(?:http:\/\/)?(.*?)\/?$/", "\$1", strtolower(trim($url)));
}

// 由key得到值
function get_cookie($key, $cookies)
{
	$result = "";

	if (preg_match("/".$key.":([^,]*)/", $cookies, $result))
		return trim($result[1]);

	return 0;
}

// 更新cookie
function update_cookies($key, $value, $cookies)
{
	//  已存在于cookies中的，更新
	if (preg_match("/".$key.":[^,]*/", $cookies))
		$cookies = preg_replace("/(".$key."):[^,]*/", "\$1:".$value, $cookies);
	else
	{
		// 新cookie，加入
		if ("" != $cookies)
			$cookies .= sprintf(",%s:%s", $key, $value);
		else
			$cookies = sprintf("%s:%s", $key, $value);
	}
	return $cookies;
}

// 取得文件的修改时间，防止文件缓存的太多
function get_mod_time($filename)
{
	if (file_exists($filename))
		return filemtime($filename);
	else
		return intval(time()/60);
}


function formatProvince($province_array,$ori_province_array)
{
	$str = '';
	$tag = '';
	foreach($province_array as $key=>$val){
		if($key>0) $tag = ',';
		$str .= $tag.$val['province_id']."|".$ori_province_array[$val['province_id']];
	}
	$str = " var provincesData = \"$str\"; ";
	return $str;
}
function formatCity($city_array,$ori_city_array)
{
	$str = '';
	$tag = '';
	foreach($city_array as $key=>$val){
		if($key>0) $tag = ',';
		$str .= $tag.$val['city_id']."|".$ori_city_array[$val['city_id']];
	}
	$str = " var citysData = \"$str\"; ";
	return $str;
}
function formatDistrict($district_array,$ori_district_array)
{
	$str = '';
	$tag = '';
	foreach($district_array as $key=>$val){
		if($key>0) $tag = ',';
		$str .= $tag.$val['district_id']."|".$ori_district_array[$val['district_id']];
	}
	$str = " var areasData = \"$str\"; ";
	return $str;
}
?>
