<?php
Import::loadController('admin/AdminController');
class IndexController extends AdminController{
	public function __construct()
	{
		parent::beforeRun($resource,$action);
		$this->_scriptMap = array('js/jquery.min.js','js/common.js');
		$this->_cssMap = array('css/admin.css');
		$this->setLayOut('admin');
	}

	public function index()
	{
		$this->_data['title'] = '管理后台';

		$this->rendercAction('admin/index',false);
	}

	public function admindefault()
	{
		$this->_data['title'] = '管理后台--缺省页';

		$this->rendercAction('admin/admin_default',false);
	}
}

?>
