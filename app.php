<?php
class App{
	static public $module;//模块名称		
	static public $action;//操作名称
	static public $config=array();//配置信息

	//执行模块，单一入口控制核心
    public function run()
	{
		$this->_parseUrl();//解析模块和操作	
		if(empty(self::$module)){
			self::$module = 'index';
		}
		if(empty(self::$action)){
			self::$action = 'index';
		}

		$module = self::$module;
		$action = self::$action;
		$controller_file = CONTROLLER_PATH.ucfirst($module).'Controller.php';
		if(stristr($module,'-')){
			$r = explode('-',$module);
			$module = $r[1];
			$controller_file = CONTROLLER_PATH.$r[0].DS.ucfirst($module).'Controller.php';
		}
		
		if(is_file($controller_file) && file_exists($controller_file)){
			require_once($controller_file);
		}else{
			exit("$controller_file is not exists");
		}

		$controller_name = ucfirst($module).'Controller';
		$methodsArray = get_class_methods($controller_name); 
		if(!in_array($action,$methodsArray)){
			exit("action $action is not exists ,in $controller_file");
		}
		$controller = new $controller_name();
		$controller->$action();   
	}


	//网址解析
    private function _parseUrl()
	{
		$script_name=$_SERVER["SCRIPT_NAME"];//获取当前文件的路径
		$url= $_SERVER["REQUEST_URI"];//获取完整的路径，包含"?"之后的字符串
		
		//去除url包含的当前文件的路径信息
		if($url&&@strpos($url,$script_name,0)!==false){
			 $url=substr($url,strlen($script_name));
		}else{
			$script_name=str_replace(basename($_SERVER["SCRIPT_NAME"]),'',$_SERVER["SCRIPT_NAME"]);
			if($url&&@strpos($url,$script_name,0)!==false){
			 $url=substr($url,strlen($script_name));
			}
		}
		
		//第一个字符是'/'，则去掉
		if($url[0]=='/'){
			$url=substr($url, 1);
		}		
		//去除问号后面的查询字符串
		if($url&&false!==($pos=@strrpos($url,'?'))){
			$url=substr($url,0,$pos);
		}
		//去除后缀
		if($url&&($pos=strrpos($url,'.php'))>0){
			$url=substr($url,0,$pos);
		}
		$flag=0;
		//获取模块名称
		if($url&&($pos=@strpos($url,'/',1))>0){
			self::$module=substr($url,0,$pos);//模块
			$url=substr($url,$pos+1);//除去模块名称，剩下的url字符串
			$flag=1;//标志可以正常查找到模块
		}
		else{	//如果找不到模块分隔符，以当前网址为模块名
			self::$module=$url;
		}
		
		$flag2=0;//用来表示是否需要解析参数
		//获取操作方法名称
		if($url&&($pos=@strpos($url,'/',1))>0){
			self::$action=substr($url,0,$pos);//模块
			$url=substr($url,$pos+1);
			$flag2=1;//表示需要解析参数
		}else{
			//只有可以正常查找到模块之后，才能把剩余的当作操作来处理
			//因为不能找不到模块，已经把剩下的网址当作模块处理了
			if($flag){
				self::$action=$url;
			}
		}				
		//解析参数
		if($flag2){
			$param=explode('/',$url);
			$param_count=count($param);
			for($i=0; $i<$param_count;$i=$i+2){			
				$_GET[$i]=$param[$i];
				if(isset($param[$i+1])){
					if(!is_numeric($param[$i])){
						$_GET[$param[$i]]=$param[$i+1];
					}
					$_GET[$i+1]=$param[$i+1];
				}
			}	
		}	
	}
}

?>
