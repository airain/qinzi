<?php
class AjaxController extends BaseController{
	public $_userServ = null;

	public function __construct()
	{
		parent::beforeRun($resource,$action);

		Import::loadService('UserService');
		$this->_userServ = new UserService();
		$this->userinfo = $this->_userServ->getUserInfo(1);
	}

	public function index()
	{
		echo 'index';
	}
	
	/**
	 * fun: mod_user_base
	 * des: 修改用户信息
	 * 
	 */
	public function mod_user_base(){
		$params = $_POST;		
		$uid = $this->userinfo['uid'];
		$data['gender'] = $params['gender'];
		$data['baby_state'] = $params['babyState'];
		$data['baby_name'] = $params['babyName'];
		$data['baby_birth'] = $params['babyBirth'];
		$data['baby_sex'] = $params['babyGender'];
		
		$rec = $this->_userServ->getUserObj()->modUserInfo($uid, $data);
		echo $rec;
	}//
	
	/**
	 * fun: mod_user_pwd
	 * des: 修改用户密码
	 * 
	 */
	public function mod_user_pwd(){
		$params = $_POST;		
		$uid = $this->userinfo['uid'];
		if($params['newpwd'] != $this->userinfo['pwd']){
			echo 'error';
			exit;
		}
		$data['pwd'] = $params['newpwd'];
		
		$rec = $this->_userServ->getUserObj()->modUserInfo($uid, $data);
		echo $rec;
	}//	
	
	/**
	 * fun: mod_user_post
	 * des: 修改用户邮寄地址
	 * 
	 */
	public function mod_user_post(){
		$params = $_POST;		
		$uid = $this->userinfo['uid'];
		
		$data['realname'] = $params['realname'];
		$data['mobile'] = $params['mobile'];
		$data['address'] = $params['address'];
		$data['postcode'] = $params['postcode'];
		
		$rec = $this->_userServ->getUserObj()->modUserInfo($uid, $data);
		echo $rec;
	}//
}

?>
