<?php
/**
 * classname: UsersModel
 * des: 用户类
 */
class UsersModel extends BaseModel{
	
	public $_table = 'qinzi_users';
	private $_primaryKey = 'uid';

	/**
	 * fun: modBabyInfo
	 * des: 修改宝宝信息
	 * 
	 * @param int $id uid
	 * @param array $info 修改信息[field->value]
	 * @return boolean|int A
	 */
	public function modBabyInfo($uid, array $info = array()){
		if($uid <= 0) return false;
		$where = $this->_primaryKey . '='. $uid;

		return $this->update($info, $where);
	}//modBabyInfo

	/**
	 * fun: modUserInfo
	 * des: 修改用户信息
	 * 
	 * @param int $id uid
	 * @param array $info 修改信息[field->value]
	 * @return boolean|int A
	 */
	public function modUserInfo($uid, array $info = array()){
		if($uid <= 0) return false;
		$where = $this->_primaryKey . '='. $uid;

		return $this->update($info, $where);
	}//modUserInfo

	/**
	 * fun: addUser
	 * des: 添加用户
	 * 
	 * @param array $info 用户信息[field->value]
	 * @return boolean|int A
	 */
	public function addUser(array $info = array()){
		if(empty($info['nick']) || empty($info['pwd'])) return false;
		$info['status'] = 0;
		$info['regtime'] = time();
		return $this->add($info);
	}//addUser

	/**
	 * fun: isUserExist
	 * des: 用户是否存在 
	 *
	 * @param string $nick
	 * @return boolean A
	 */
	public function isUserExist($nick=''){
		if(empty($nick)) false;
		$where = 'nick="'.$sns_uid.'"';

		return $this->getCount($where);
	}//isExist
	
}

/**
 * className: UserSnsModel
 * des: 用户关联类
 */
class UserSnsModel extends BaseModel {
	public $_table = 'qinzi_user_sns';
	private $_primaryKey = 'id';

	/**
	 * fun: modUserSns
	 * des: 更新用户关联信息 
	 *
	 * @param int $sns_uid
	 * @param string $sns_site
	 * @param array $info
	 * @return boolean|int A
	 */
	public function modUserSns($sns_uid, $sns_site, array $info = array()){
		if(empty($sns_uid) || empty($sns_site)) false;
		$where = 'sns_uid="'.$sns_uid.'" AND sns_site="'.$sns_site.'"';

		return $this->update($info, $where);
	}//modUserSns

	/**
	 * fun: isExist
	 * des: 是否存在 
	 *
	 * @param int $sns_uid
	 * @param string $sns_site
	 * @return boolean|array A
	 */
	public function isExist($sns_uid, $sns_site){
		if(empty($sns_uid) || empty($sns_site)) false;
		$where = 'sns_uid="'.$sns_uid.'" AND sns_site="'.$sns_site.'"';

		return $this->getOne($where);
	}//isExist

}
