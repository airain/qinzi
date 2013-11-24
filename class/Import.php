<?php
class Import{
	static $_instance = null;

    public static function instance(){
        if(self::$_instance===NULL){
            self::$_instance = new Import;
        }
        return self::$_instance;
    }

	private static function _load($file)
	{
		if(is_file($file) && file_exists($file)){
			require_once($file);
		}else{
			echo $file.' is not exists';
		}
	}

	public static function loadClass($class_name)
	{
		$class_file = ROOT_PATH.DS.'class'.DS.$class_name.'.php';
		self::_load($class_file);
	}

	public static function loadService($class_name)
	{
		$class_file = ROOT_PATH.DS.'service'.DS.$class_name.'.php';
		self::_load($class_file);
	}

	public static function loadModel($class_name)
	{
		$class_file = ROOT_PATH.DS.'model'.DS.$class_name.'.php';
		self::_load($class_file);
	}

	public static function loadViews($class_name)
	{
		$class_file = ROOT_PATH.DS.'views'.DS.$class_name.'.php';
		self::_load($class_file);
	}

	public static function loadController($class_name)
	{
		$class_file = ROOT_PATH.DS.'controller'.DS.$class_name.'.php';		
		self::_load($class_file);
	}
}
?>
