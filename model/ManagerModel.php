<?php
class ManagerModel extends BaseModel{
	public $_table = 'qinzi_manager';
	private $_primaryKey = 'admin_id';

	public function get_managers()
	{
		$sql = 'select * from '.$this->_table;
		$res = $this->_db->get_all($sql);
		return $res;
	}

	public function get_manager($user_id)
	{
		if(empty($user_id) || !is_numeric($user_id)){
			return false;
		}
		$where = $this->_primaryKey . '='. $user_id; 

		$res = $this->getOne($where);
		return $res;
	}

	//增加管理员
	public function save_manager($item_array)
	{
		$i=0;
		$tag = $updatestr = $vals = $keys="";
		if(!is_array($item_array))	return false;
		foreach($item_array as $key=>$val)
		{
			if($i++>0)	$tag = ",";
			$keys .= $tag.$key;
			$vals .= $tag."'".$val."'";
			$updatestr .= $tag.$key."="."'".$val."'";
		}
		$sql = "insert into '.$this->_table.' ($keys) values($vals) ON DUPLICATE KEY update $updatestr";
		
		$rs = $this->_db->update($sql);
		return $rs;
	}   
	
	//修改管理员
	public function edit_manager($item_array,$user_id)
	{
		if(!is_array($item_array)) return false;
		
		$rs = $this->update($user_id, $item_array);
		return $rs;
	}    

	//删除指定ID的管理员
	public function delete_manager($user_id)
	{
		if ($user_id <= 0) return false;
		$where = $this->_primaryKey . '='. $user_id; 
		$rs = $this->delete($where);
		return $rs;
	}


	//用户登录
	public function login_user($user_name,$user_pwd)
	{
		if(empty($user_name) || empty($user_pwd)) return false;

		$where = "admin_name='".$user_name."'";
		$rs = $this->getOne($where);
		if(empty($rs))	return false;
		if($user_pwd != $rs['pwd'])return false;

		$this->update_logintime($rs['admin_id']);
		return $rs;
	}

	//更新用户登录时间
	private function update_logintime($user_id)
	{
		$timestamp = time();
		$sql = "update '.$this->_table.' set lastlogin_time=$timestamp where admin_id=".$user_id;
		$rs = $this->_db->update($sql);
		return $rs;
	}
}

?>
