<?php
class IndexController extends BaseController{
	public function __construct()
	{
		parent::beforeRun($resource,$action);
		$this->_scriptMap = array('js/jquery/jquery.min.js','js/default.js');
		$this->_cssMap = array('css/css.css');
		$this->setLayOut('default_none');
	}

	public function index()
	{
		$this->_data['title'] = '首页';
		Import::loadService("UserService");
		$userServ = new UserService();
		$this->_data['userinfo'] = $userServ->getUserInfo(1);
		$this->rendercAction('user');
	}
}

?>
