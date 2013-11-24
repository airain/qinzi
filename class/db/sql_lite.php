<?php

require_once 'databasei.php';
require_once 'sys_configer.php';

class SqlLite extends SysConfiger 
{
	var $_iscache=false;
	var $_ksql ='';
	var $_db  =null;
	var $_sdb  =null;  //从服务器
	var $_mc  =null;
	var $_showSql = false;

	function SqlLite()
	{
		$this->_db = new database($this->_conf_db, $this->_conf_user, $this->_conf_pwd,$this->_conf_dbname);
		$this->_db->setQuery("SET NAMES 'utf8'");
		$this->_db->query();
		
		$this->_sdb = new database($this->_s_conf_db, $this->_s_conf_user, $this->_s_conf_pwd,$this->_s_conf_dbname);
		$this->_sdb->setQuery("SET NAMES 'utf8'");
		$this->_sdb->query();
	}

	
	function setQuery($sql) 
	{
		if($this->_showSql) var_dump($sql);
		$this->_ksql = $sql;
		$this->_db->setQuery($this->_ksql);
	}
	
	function getQuery() 
	{
		return $this->_db->getQuery();
	}
	
	function query() 
	{
		$this->_sdb->setQuery($this->_ksql);
		return $this->_sdb->query();
	}

	function getAffectedRows() 
	{
		return $this->_sdb->getAffectedRows();
	}

	function query_batch( $abort_on_error=true, $p_transaction_safe = false) 
	{
		return $this->_db->query_batch($abort_on_error, $p_transaction_safe);
	}

	function explain() 
	{
		return $this->_db->explain();
	}
	
	function getNumRows($iscache=true,$expire=60) 
	{
		if(($this->_iscache || $iscache) && $this->_mc)
		{
			$key = md5($this->_ksql.'num_rows');
			$res = $this->_mc->get($key);
			
			if($res) return $res;
			
			$res = $this->_db->getNumRows();
			if($this->_mc) $this->_mc->set($key, $res, 0, $expire);
			
			return $res; 
		}
		return $this->_db->getNumRows();
	}

	function loadResult($iscache=true,$expire=60) 
	{
		if(($this->_iscache || $iscache) && $this->_mc)
		{
			$key = md5($this->_ksql);
			$res = $this->_mc->get($key);

			if($res) return $res;

			$res = $this->_db->loadResult();
			if($this->_mc) $this->_mc->set($key, $res, 0, $expire);

			return $res; 
		}

		return $this->_db->loadResult();
	}

	function loadResultArray($iscache=true,$expire=60) 
	{
		if(($this->_iscache || $iscache) && $this->_mc)
		{
			$key = md5($this->_ksql);
			$res = $this->_mc->get($key);

			if($res) return $res;

			$res = $this->_db->loadResultArray();
			if($this->_mc) $this->_mc->set($key, $res, 0, $expire);

			return $res; 
		}
		return $this->_db->loadResultArray();
	}

	function loadAssoc($iscache=true,$expire=60)
	{
		if(($this->_iscache || $iscache) && $this->_mc)
		{
			$key = md5($this->_ksql);
			$res = $this->_mc->get($key);

			if($res) return $res;

			$res = $this->_db->loadAssoc();
			if($this->_mc) $this->_mc->set($key, $res, 0, $expire);

			return $res; 
		}
		return $this->_db->loadAssoc();
	}

	function loadAssocList($iscache=true,$expire=60) 
	{
		if(($this->_iscache || $iscache) && $this->_mc)
		{
			$key = md5($this->_ksql);
			$res = $this->_mc->get($key);

			if($res) return $res;

			$res = $this->_db->loadAssocList();
			if($this->_mc) $this->_mc->set($key, $res, 0, $expire);

			return $res; 
		}
		return $this->_db->loadAssocList();
	}

	/**
	 *新加的返回数组
	 *@param string $sql
	 *@param bool $iscache
	 *@param int $expire
	 *@return array
	 */
	function get_all($sql='',$iscache=true,$expire=60) 
	{
		if(!empty($sql)){
			$this->setQuery($sql);
		}
		$res = $this->_db->loadAssocList();

		if(empty($res)){
			return array();
		}
		return $res; 
	}

	/**
	 *返回单个值
	 *@param string $sql
	 *@param string $field
	 *@return string
	 */
	function get_value($sql='',$field=0){
		if(!empty($sql)){
			$this->setQuery($sql);
		}	
		$rs = $this->_db->loadAssoc();
		if(empty($rs)){
			return null;
		}
		if($field=='0'){
			$value = reset($rs);
		}
		else{
			$value = isset($rs[$field])? $rs[$field]:null;
		}
	  return $value;
	}

	/**
	 *返回一维数据
	 *@param string $sql
	 *@return array
	 */
	function get_one($sql=''){
		if(!empty($sql)){
			$this->setQuery($sql);
		}	
		$rs = $this->_db->loadAssoc();
	  return $rs;
	}

    /*
     *返回影响的记录条数
     *@param string $sql
     *@return number
     * */
    function update($sql='')
	{
		$affect_num = 0;
		if (empty($sql))
			return 0;
		$this->setQuery($sql);
		if(!$this->query())
		{
			return false;
		}
		$affect_num = $this->getAffectedRows();
		return $affect_num;
	}


	function loadObject(&$object,$iscache=true,$expire=60)
	{
		if(($this->_iscache || $iscache) && $this->_mc)
		{
			$key = md5($this->_ksql);
			$res = $this->_mc->get($key);

			if($res) return $res;

			if($this->_db->loadObject($object))
			{
				if($this->_mc) $this->_mc->set($key, $object, 0, $expire);
				return true;
			}
			return false; 
		}
		return $this->_db->loadObject($object);
	}

	function loadObjectList($iscache=true,$expire=60)
	{
		if(($this->_iscache || $iscache) && $this->_mc)
		{
			$key = md5($this->_ksql);
			$res = $this->_mc->get($key);

			if($res) return $res;

			$res = $this->_db->loadObjectList();
			if($this->_mc) $this->_mc->set($key, $res, 0, $expire);

			return $res; 
		}
		return $this->_db->loadObjectList();
	}

	function loadRow($iscache=true,$expire=60)
	{
		if(($this->_iscache || $iscache) && $this->_mc)
		{
			$key = md5($this->_ksql);
			$res = $this->_mc->get($key);

			if($res) return $res;

			$res = $this->_db->loadRow();
			if($this->_mc) $this->_mc->set($key, $res, 0, $expire);

			return $res; 
		}
		return $this->_db->loadRow();
	}

	function loadRowList($iscache=true,$expire=60)
	{
		if(($this->_iscache || $iscache) && $this->_mc)
		{
			$key = md5($this->_ksql);
			$res = $this->_mc->get($key);

			if($res) return $res;

			$res = $this->_db->loadRowList();
			if($this->_mc) $this->_mc->set($key, $res, 0, $expire);

			return $res; 
		}
		return $this->_db->loadRowList();
	}

	function insertid() 
	{
		return $this->_sdb->insertid();
	}


	/*************************************
	 *功能：新增记录
	 *参数：$res是一个数组,$tbname是表名
	 ***********************************/
	function insertRecord($res,$tbname)
	{
		if(empty($res))
		  return false;
		$i = 0;
		$tag = "";
		$keys = "";
		$vals = "";
		foreach($res as $key=>$val)
		{
			if($i++>0)
				$tag = ",";
			$keys .= $tag.$key;
			$vals .= $tag."'".$val."'";
		}
		$sql = "insert into ".$tbname." (".$keys.") values(".$vals.")";
		$this->setQuery($sql);
		try{
			$rs = $this->query();
		}catch(Exception $e){
			return false;
		}
		return $rs;
	}

	/*************************************
	 *功能：修改记录
	 *参数：$res是一个数组,$tbname是表名,$where修改条件
	 ***********************************/
	function editRecord($res,$tbname,$where)
	{
		if(empty($res))
		  return false;
		$i = 0;
		$tag = "";
		$str = "";
		foreach($res as $key=>$val)
		{
			if($i++>0)
				$tag = ",";
			$str .= $tag.$key."='".$val."'";
		}
		$sql = "update ".$tbname." set ".$str." where 1 ".$where;
		try{
			$rs = $this->update($sql);
		}catch(Exception $e){
			return false;
		}
		return $rs;
	}

	function setCache($iscache=true)
	{
		$this->_iscache=$iscache;
	}


	function getErrorMsg(){
		return $this->_db->getErrorMsg();
	}

}


?>
