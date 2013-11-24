<?php
/**
 * className: UserService
 * des: 
 */

Import::loadClass('BaseService');

class UserService extends BaseService{
	private $_userObj = null;
	private static $_USERINFO = 'qinzi_login_userinfo';
	public function __construct(){
		Import::loadModel('UsersModel');
		$this->_userObj = new UsersModel();
	}

	public function getUserObj(){
		return $this->_userObj;
	}

	/**
	 * fun: createUser
	 * des: 创建用户
	 * 
	 * @param array $info [nick,pwd,email,snsInfo], snsInfo[...]
	 * @return array A
	 */
	public function createUser(array $info=array()){
		$rec = array('resutl'=>1, 'data'=> null, 'message'=> null);
		if(empty($info['nick']) || empty($info['pwd']) || empty($info['email'])) {
			$rec['message'] = 'nick、passwrod or email can\'t be empty';
			return $rec;
		}
		if(!isemail($info['email'])) {
			$rec['message'] = "E-mail verification is not correct";
			return $rec;
		}
		if($this->_userObj->isUserExist($info['nick'])){
			$rec['message'] = 'nick is already exists.';
			return $rec;
		}
		$snsinfo = null;
		$userSnsObj = new UserSnsModel();
		if(isset($info['snsInfo'])) {
			$snsinfo = $info['snsInfo'];
			$userSns = $userSnsObj->isExist($sns_uid, $sns_site);
			if($userSns){
				$rec['message'] = "sns is registed";
				return $rec;
			}
		}
		$data['nick'] = $info['nick'];
		$data['pwd'] = md5($info['pwd']);
		$data['email'] = $info['email'];
		
		$uid = $this->_userObj->addUser($data);
		if($uid && $snsinfo){
			$data1['uid'] = $uid;
			$data1['sns_site'] = $info['snsInfo']['sns_site'];
			$data1['sns_uid'] = $info['snsInfo']['sns_uid'];
			$data1['sns_name'] = $info['snsInfo']['sns_name'];
			$data1['sns_token'] = $info['snsInfo']['sns_token'];
			$data1['sns_secret'] = $info['snsInfo']['sns_secret'];
			$data1['sns_expires'] = $info['snsInfo']['sns_expires'];
			$data1['uptime'] = time();
			$usns_id = $userSnsObj->add($data1);
			if(!$usns_id){
				$this->_userObj->delete('uid='.$uid);
				$rec['message'] = 'save snsinfo failed';
				return $rec;
			}
		}
		$user = $this->getUserInfo($uid);
		$this->setUserInfo($user);
		$rec['result'] = 0;
		$rec['data'] = $uid;
		return $rec;
	}//createUser

	/**
	 * fun: login
	 * des: 登录
	 *
	 * @param array $info [nick,pwd,snsInfo], snsInfo[...]
	 * @return array A
	 */
	public function login(array $info=array()){
		$rec = array('resutl'=>1, 'data'=> null, 'message'=> null);
		if(empty($info['nick']) && empty($info['pwd']) || empty($info['snsInfo'])) {
			$rec['message'] = 'nick or passwrod can\'t be empty';
			return $rec;
		}
		if(isset($info['snsInfo']) && count($info['snsInfo']) > 0){//sns登录
			$sns_uid = $info['snsInfo']['sns_id'];
			$sns_site = $info['snsInfo']['sns_site'];
			$userSnsObj = new UserSnsModel();
			$userSns = $userSnsObj->isExist($sns_uid, $sns_site);
			if(!$userSns){
				$rec['message'] = 'not registe';
				$rec['result'] = 100;
				return $rec;
			}
			$userSnsObj->modUserSns($sns_uid, $sns_site, $info['snsInfo']);
			$where = 'uid='.$userSns['uid'];
		}else{//正常登录
			$nick = $info['nick'];
			$pwd = md5($info['pwd']);
			$where = 'nick="'.$nick.'" AND pwd="'.$pwd.'"';
		}
		$user = $this->_userObj->getOne($where);
		if(!$user){
			$rec['message'] = 'user or password not match';
			return $rec;
		}
		$this->setUserInfo($user);

		$rec['result'] = 0;
		$rec['data'] = $user;
		return $rec;
	}//login

	//checkedLogin
	public function isLogin(){
		if(!isset( $_SESSION[self::_USERINFO])) return false;
		return $this->getLoginUserInfo();
	}

	public function setUserInfo($user, $value=''){
		if(is_array($user)){
			$_SESSION[self::_USERINFO] = $user;
		}elseif(is_string($user)){
			$_SESSION[self::_USERINFO][$user] = $value;
		}
	}

	public function getUserInfo($uid){
		return $this->_userObj->getOne('uid='.$uid);
	}

	public function getLoginUserInfo(){
		if(!isset( $_SESSION[self::_USERINFO])) return array();
		return $_SESSION[self::_USERINFO];
	}
}

