<?php
class ManagerModel extends BaseModel{
	public function get_managers()
	{
		$sql = 'select * from manager;';
		$res = $this->_db->get_all($sql);
		return $res;
	}

	public function get_manager($user_id)
	{
		if(empty($user_id) || !is_numeric($user_id)){
			return false;
		}

		$sql = "select * from manager where user_id=".$user_id;
		$res = $this->_db->get_one($sql);
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
		$sql = "insert into manager ($keys) values($vals) ON DUPLICATE KEY update $updatestr";
		
		$rs = $this->_db->update($sql);
		return $rs;
	}   
	
	//修改管理员
	public function edit_manager($item_array,$user_id)
	{
		$i=0;
		$tag = $updatestr = $vals = $keys="";

		if(!is_array($item_array)) return false;
		foreach($item_array as $key=>$val)
		{
			if($i++>0) $tag = ",";
			$updatestr .= $tag.$key."="."'".$val."'";
		}
		$sql = "update  manager set $updatestr where user_id=$user_id";
		$rs = $this->_db->update($sql);
		return $rs;
	}    

	//删除指定ID的管理员
	public function delete_manager($user_id)
	{
		if ($user_id <= 0) return false;

		$sql = "delete from manager where user_id=".$user_id;
		$rs = $this->_db->update($sql);
		
		return $rs;
	}


	//用户登录
	public function login_user($user_name,$user_pwd)
	{
		if(empty($user_name) || empty($user_pwd)) return false;

		$sql = "select * from manager where user_name='".$user_name."'";
		$rs = $this->_db->get_one($sql);
		if(empty($rs))	return false;
		if($user_pwd != $rs['user_pwd'])return false;

		$this->update_logintime($rs['user_id']);
		return $rs;
	}

	//更新用户登录时间
	private function update_logintime($user_id)
	{
		$timestamp = time();
		$sql = "update manager set lastlogin_time=$timestamp where user_id=".$user_id;
		$rs = $this->_db->update($sql);
		return $rs;
	}
}

?>