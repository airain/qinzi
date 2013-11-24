<?php
class AdminController extends BaseController{
	public function beforeRun($resource,$action)
	{
		parent::beforeRun($resource,$action);

		 $m_uname = $this->_cookie->getCookie('manage_uname');
		 $m_pwd   = $this->_cookie->getCookie('manage_pwd');
		 $m_uid   = $this->_cookie->getCookie('manage_uid');

		 $login_url = SITE_URLI."/admin-login";
		 if(empty($m_uname) || empty($m_pwd) || empty($m_uid))
		 {
			$this->_message->show("您还未登录，请先登录．",$login_url);
			exit;
		 }

		Import::loadModel('ManagerModel');
		$mObj = new ManagerModel();
		if(!$mObj->get_manager($m_uid)){
			$this->_message->show("该用户不存在，请返回",$login_url);
			exit;
		}

		$manager = array('user_name'=>$m_uname,'user_pwd'=>$m_pwd,'user_id'=>$m_uid);
		$this->_data['manager'] = $manager;
	}

}

?>
