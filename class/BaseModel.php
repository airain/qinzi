<?php
Import::loadClass('db/sql_lite');
class BaseModel {
	public $_db = null;
	private $_showSql = false;
	public function __construct()
	{
		$this->_db = new SqlLite();
	}

	public function showSql(){
		$this->_db->_showSql = true;
	}
	

	public function add(array $info = array()){
		if(empty($info) || !is_array($info)) return false;
		$sql = 'INSERT INTO '.$this->_table;
		$fields = '';
		$values = '';
		foreach($info as $field => $value){

			$fields .= '`'.$field.'`,';
			if(is_numeric($value))
				$values .= '"'.$value.'",';
			else
				$values .= '"'.mysql_escape_mimic($value).'",';
		}
		$sql .= '(' . trim($fields,',') . ') VALUES (' . trim($values,','). ');';
		$this->_db->setQuery($sql);
		try{
			return $this->_db->query();
		}catch(Exception $e){
			return false;
		}
	}//add

	public function update(array $info = array(), $wherestr=''){
		if(empty($info) || !is_array($info)) return false;
		$where = empty($wherestr)?'' : 'AND '.$wherestr;
		$sql = 'UPDATE '.$this->_table.' SET ';
		$updatestr = '';
		foreach($info as $field => $value){
			$updatestr .= '`'.$field.'`="'.mysql_escape_mimic($value).'",';
		}
		$sql .= trim($updatestr,',') . ' WHERE 1 '.$where;
		
		return $this->_db->update($sql);
	}//update

	/**
	* fun: delete
	* des: 删除信息
	* 
	* @param string $wherestr 更新数据
	* @return boolean
	*/
	public function delete($wherestr=''){
		$where .= empty($wherestr)? '' : ' AND '.$wherestr;
		$sql = 'DELETE FROM '.$this->_table.' WHERE 1 '.$where;
		try{
			return $this->_db->query($sql);
		}catch(Exception $e){
			return false;
		}
	}

	/**
	* fun: getCount
	* des: 获取总数
	* 
	* @param string $wherestr 其他查询条件
	* @return array
	*/
	public function getCount($wherestr = '', $value='*'){
		$where = empty($wherestr)? '' : ' AND '.$wherestr;
		$sql = 'SELECT count('.$value.') as cnt FROM '.$this->_table.' WHERE 1 '.$where;
		return $this->_db->get_value($sql);
	}

	/**
	* fun: getAll
	* des: 获取信息
	* 
	* @param string $sids 多个用逗号相隔，为空获取所有全部数据
	* @param string $wherestr 其他查询条件
	* @param int $limit 0
	* @param int $offset 0
	* @return array
	*/
	public function getAll($wherestr = '', $orderby='', $limit=0, $offset = 0){
		$where = empty($wherestr)? '' : ' AND '.$wherestr;
		$where .= empty($orderby)? '' : ' ORDER BY '.$orderby;
		$where .= empty($limit)? '' : ' LIMIT '.$limit;
		$where .= empty($offset)? '' : ' OFFSET '.$offset;
		$sql = 'SELECT * FROM '.$this->_table.' WHERE 1 '.$where;
		return $this->_db->get_all($sql);
	}

	/**
	* fun: getOne
	* des: 根据id获取某个信息
	* 
	* @param int $sid
	* @param string $wherestr
	* @param string $fields
	* @return array
	*/
	public function getOne($wherestr='', $fields='*'){
		$where = '';
		$wherestr && $where .= ' AND '.$wherestr;
		$sql = 'SELECT '.$fields.' FROM '.$this->_table.' WHERE 1 '.$where;
		return $this->_db->get_one($sql);
	}

	public function get_all($sql){
		return $this->_db->get_all($sql);
	}

	public function get_one($sql){
		return $this->_db->get_one($sql);
	}
}

?>
