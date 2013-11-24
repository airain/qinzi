<?php
header("Content-type: text/html; charset=UTF-8"); 
define('DEBUG',1);
define('DS','/');
define('ROOT_PATH', str_replace("\\", '/', substr(dirname(__FILE__), 0, -7)));
define('VIEWS_PATH',ROOT_PATH.DS.'views'.DS);
define('MODEL_PATH',ROOT_PATH.DS.'model'.DS);
define('CONTROLLER_PATH',ROOT_PATH.DS.'controller'.DS);
define('PUBLIC_PATH',ROOT_PATH.DS.'public'.DS);
define('CONFIG_PATH',ROOT_PATH.DS.'config'.DS);
define('LOGS_PATH',ROOT_PATH.DS.'logs'.DS);

define('MODELS_IMG_PATH',PUBLIC_PATH.'upload'.DS.'models'.DS);
define('CELEBRITIES_IMG_PATH',PUBLIC_PATH.'upload'.DS.'celebrities'.DS);
define('PHOTOGRAPHERS_IMG_PATH',PUBLIC_PATH.'upload'.DS.'photographers'.DS);
define('RETOUCHING_IMG_PATH',PUBLIC_PATH.'upload'.DS.'retouching'.DS);

if ( function_exists('date_default_timezone_set') )
	date_default_timezone_set('PRC');//设置系统时间为北京区域时间，解决时间相关8小时


if(DEBUG)
 error_reporting(E_ALL ^ E_NOTICE);//除了notice提示，其他类型的错误都报告
else
 error_reporting(0);//把错误报告，全部屏蔽

require(ROOT_PATH.DS.'class'.DS.'Import.php');
require_once("site.config.php");       //加载配置文件
require_once("common.function.php");   //常用函数
Import::loadClass('BaseController');
Import::loadClass('BaseModel');
Import::loadClass('BaseView');
Import::loadClass('MessageClass');
require_once(ROOT_PATH.DS."class/db/sql_lite.php"); //加载数据库操作类(其中包括数据库最底层操作类,还有配置文件)
?>
