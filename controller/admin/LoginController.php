<?php
class LoginController extends BaseController{
	public function __construct()
	{
		parent::beforeRun($resource,$action);
		$this->_scriptMap = array('js/jquery.min.js','js/common.js');
		$this->_cssMap = array('css/admin.css');
		$this->setLayOut('admin');
	}

	public function index()
	{
		$this->_data['title'] = '管理员登录';
		$this->rendercAction('admin/login',false);
	}

	public function login_save()
	{
		$this->_data['title'] = '管理员登录--保存';
		$login_url = SITE_URLI.'/admin-login';

		$user_name = $_POST['user_name'];
		$user_pwd  = $_POST['user_pwd'];
		if(empty($user_name)){
			$this->_message->show("管理员名称不能为空",$login_url);
			exit;
		}
		if(empty($user_pwd)){
			$this->_message->show("管理员密码不能为空",$login_url);
			exit;
		}

		$user_pwd = md5($user_pwd);
		Import::loadModel('ManagerModel');
		$mObj = new ManagerModel();
		$rs = $mObj->login_user($user_name,$user_pwd);
		if(!$rs){
			$this->_message->show("登录失败，请得新登录．",$login_url);
			exit;
		}

		$this->_cookie->updateCookie('manage_uname',$rs['admin_name']);
		$this->_cookie->updateCookie('manage_uid',$rs['admin_id']);
		$this->_cookie->updateCookie('manage_pwd',$rs['pwd']);
		$this->_cookie->updateCookie('lastlogin_time',date('Y-m-d H:i:s',time()));
		$this->_message->show("登录成功．",SITE_URLI."/admin-index");
		exit;
	}

	public function logout()
	{
		$this->_cookie->updateCookie('manage_uname','');
		$this->_cookie->updateCookie('manage_pwd','');
		$this->_cookie->updateCookie('manage_uid','');
		$this->_message->show("注销成功．",SITE_URLI."/admin-login");
		exit;
	}
}

?>
