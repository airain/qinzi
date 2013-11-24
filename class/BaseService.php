<?php
/**
 * BaseService
 * base Service provide public method.
 *
 */
class BaseService {
	
	public $_is_mobile = false;
	
	//Model 句柄
	private $modelHandle = array();

	//构造方法
	public function __construct($is_mobile=false) {
		$this->_is_mobile = $is_mobile;
	}

	//析构方法
	public function __destruct() {
	}
	
	protected function pushBeanStalk() {

	}

	protected function pushMemcache() {

	}

	protected function pushHbase() {

	}

}

