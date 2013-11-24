<?php
class CategoryModel extends BaseModel{
	public $dt  = 'category';   //数据表
	public $_db = null;         //数据库存操作对象

	/**
	 * 新增一个节点 
	 *
	 * @param int    $parentid 父亲节点id
	 * @param string $name     新增节点名称
	 *
	 * @access public
	 * @return bool
	 */
	public function save($parentid,$name,$introduce="")
	{
		if(empty($type)) $type = 1;
		$elite = get_int('elite');
		$state = get_int('state');
		$sql = "insert into $this->dt(parentid,name,introduce,type,elite,state) values ('$parentid','$name','$introduce','$type','$elite','$state')";
		if($this->_db->update($sql))
		{
			$row = $this->_db->get_one("select * from $this->dt order by id desc limit 1");
			if($row['parentid'] == 0)
			{
				$path = $row['parentid'];
			}
			else
			{
				$r =  $this->_db->get_one("select * from $this->dt where id='".$row['parentid']."'");
				$path = $r['path'];
			}
			return $this->buildPath($row['id'],$path);
		}
		return false;
	}

	//+---------------------------------------------------------------------------------------------------
	  //更新指定节点的信息
	public function update($initparentid,$parentid,$name,$id)
	{
		if($parentid==$id) return false;
		$elite = get_int('elite');
		$state = get_int('state');
		if($this->_db->update("update $this->dt set parentid='$parentid',name='$name',elite='$elite',state='$state' where id='$id'"))
		{
			$row  = $this->_db->get_one("select path from $this->dt where id='$parentid'");
			$path = isset($row['path']) ? $row['path'] : '0';
			$this->rebuildPath($id,$path);
			return true;
		}
		return false;		
	}

	public function getChildNodeById($id)
	{
		$sql = "select * from $this->dt where parentid=$id";
		$rs = $this->_db->get_all($sql);

		return $rs;
	}

	/*
	 *说明：排序推荐
	 *参数：$id推荐的id是一个数组，key为id, val 为值
	 */
	public function sortCategory($id_array)
	{
		if(empty($id_array)) return false;
		foreach($id_array as $key=>$val)
		{
			$sql = "update  ".$this->dt." set sort_number=".$val."   where id=$key";
			$rs = $this->_db->update($sql);
		}
		return true;
	}

	/*
	 *说明：推荐给用户投稿
	 *参数：$id推荐的id是一个数组，key为id, val 为值
	 */
	public function eliteCategory($id,$op=1)
	{
		if(empty($id))return false;
		if(is_array($id)){
		  $id    = implode(',', $id);
		}
		$sql = "update  ".$this->dt." set elite=".$op."   where id in($id)";
		$rs = $this->_db->update($sql);
		return $rs;
	}

	/*
	 *说明：前台显示
	 *参数：$id推荐的id是一个数组，key为id, val 为值
	 */
	public function setShowCategory($id,$op=1)
	{
		if(empty($id))return false;
		if(is_array($id)){
		  $id    = implode(',', $id);
		}
		$sql = "update  ".$this->dt." set state=".$op."   where id in($id)";
		$rs = $this->_db->update($sql);
		return $rs;
	}


	/**
	 * 删除节点(包括其下所有子节点) 
	 *
	 * @param int $id 节点id
	 *
	 * @access public
	 * @return bool
	 */
	public function remove($id)
	{
		if($id <= 0) return false;
		$row = $this->get($id);
		if(!isset($row['path'])) return false;
		
		$rows = $this->_db->get_all("select id from $this->dt where path like '".$row['path']."%' order by path");
		foreach($rows as $row)
		{
			$nodeId[] = $row['id'];
		}
		$removeId = implode(",",$nodeId);
		
		return $this->_db->update("delete from $this->dt where id in ($removeId)");
	}

	/**
	 * 返回指定ID的节点(不包括子节点)
	 *
	 * @param int $id 节点id
	 *
	 * @access public
	 * @return array
	 */
	public function get($id)
	{
		if($id <=0) return array();

		return $this->_db->get_one("select * from $this->dt where id=$id");
	}

	/**
	 * 返回一个节点的子节点的节点列表
	 *
	 * @param int $id 节点id
	 *
	 * @access public
	 * @return array
	 */
	public function getChildNodes($id)
	{
		if($id <=0) return array();

		$row   = $this->get($id);
		$nodes = $this->_db->get_all("select * from $this->dt where id<>'$id' and path like '".$row['path']."%' order by path");	
		foreach($nodes as $key=>$row)
		{
			$depth = count(explode(',',$row['path']));
			$nodes[$key]['depth'] = $depth;
		}
		return $nodes;
	}

	/**
	 * 得到一个节点的父类节点
	 *
	 * @param int $id 节点id
	 *
	 * @access public
	 * @return array
	 */
	public function getParentNode($id)
	{
		if($id <=0) return array();

		$node     = $this->get($id);
		$parentid = $node['parentid'];

		return $this->_db->get_one("select * from $this->dt where id='$parentid'");
	}

	/**
	 * 得到一个节点的所有兄弟节点
	 *
	 * @param int $id 节点id
	 *
	 * @access public
	 * @return array
	 */
	public function getSiblingNodes($id)
	{
		$node     = $this->get($id);
		$parentid = $node['parentid'];
		return $this->_db->get_all("select * from $this->dt where parentid='$parentid'");
	}

	//+---------------------------------------------------------------------------------------------------
	  //Desc:假如某个节点拥有任何子节点，则返回true。否则返回false。
	public function hasChildNodes()
	{
	}

	/**
	 * 得到所有根节点
	 *
	 * @access public
	 * @return array
	 */
	public function getRootNodes($elite=0,$ctype=0,$state=0)
	{
		$sql = "select * from $this->dt where parentid=0";
		if(!empty($elite))
			$sql .= " and elite=1 ";
		if(!empty($state))
			$sql .= " and state=1 ";
		if(!empty($ctype))
			$sql .= " and type=".$ctype;
		$sql .= " order by sort_number asc ";
		return $this->_db->get_all($sql);
	}

	//+---------------------------------------------------------------------------------------------------
	  //Desc:检查指定节点下是否存在子节点
	public function checkNode($initparentid,$parentid,$path)
	{
		$row = $this->_db->get_one("select path from $this->dt where parentid='$parentid' limit 1");
		return preg_match("/$path/",$row['path']) ? true : false;
	}

	/**
	 * 得到指定节点的深度
	 *
	 * @param int $id 节点id
	 *
	 * @access public
	 * @return int
	 */
	public function getDepth($id)
	{
		$row = $this->_db->get_one("select path from $this->dt where id='$id'");
		
		return count(explode(',',$row['path'])) - 1;
	}

	/**
	 * 得到N颗完整树
	 *
	 * @access public
	 * @return array
	 */
	public function getTree()
	{
		$tree = array();
		$rows = $this->getRootNodes();
		foreach($rows as $row)
		{
			$tree[] = $this->getChildNodes($row['id']);
		}
		return $tree;
	}

	/**
	 * 格式化树
	 *
	 * @access public
	 * @return array
	 */
	public function getOptionTree($elite=0,$ctype=0)
	{
		$tree = array();
		$rows = $this->getRootNodes($elite,$ctype);
		foreach($rows as $row)
		{
			$rrows = $this->_db->get_all("select * from $this->dt where path like '".$row['path']."%' order by path asc,id asc");
			foreach($rrows as $rrow)
			{
				$depth  = count(explode(',',$rrow['path'])); //计算节点的深度
				$sign   = ($rrow['parentid'] == 0) ? str_repeat('&nbsp;',1) : str_repeat('&nbsp;',3);
				$tree[] = array(
					'id'   => $rrow['id'],
					'name' => str_repeat($sign,$depth).'◆-'.$rrow['name'],
					'path'=>$rrow['path']
					);
			}
		}
		return $tree;
	}

	public function getOptionTreeExt($state=0,$ctype=0)
	{
		$tree = array();
		$rows = $this->getRootNodes(0,$ctype,$state);
		foreach($rows as $row)
		{
			$rrows  = $this->_db->get_all("select * from $this->dt where path like '".$row['path']."%' order by sort_number");
			foreach($rrows as $rrow)
			{
				$depth  = count(explode(',',$rrow['path'])); //计算节点的深度
				$sign   = ($rrow['parentid'] == 0) ? str_repeat('&nbsp;',1) : str_repeat('&nbsp;',4);
				$node[] = array(
					'id'   => $rrow['id'],
					//'name' => str_repeat($sign,$depth).'◆-'.$rrow['name'],
					'name' => $rrow['name'],
					'path' => $rrow['path'],
					'sort_number'=>$rrow['sort_number'],
					'elite'=>$rrow['elite'],
					'state'=>$rrow['state']
					);
			}
			$tree[] = array(
				'id'    => $row['id'],
				'name'  => $row['name'],
				'child' => $node,
				'sort_number'=>$row['sort_number'],
				'elite'=>$row['elite'],
				'state'=>$row['state']
				);
			unset($node);
		}
		return $tree;
	}


	/**
	 * 格式化树
	 *
	 * @access public
	 * @return array
	 */
	public function getTableTree($ctype=0)
	{
		$tree = array();
		$rows = $this->getRootNodes(0,$ctype);
		foreach($rows as $row)
		{
			$rrows  = $this->_db->get_all("select * from $this->dt where path like '".$row['path']."%' order by path");
			foreach($rrows as $rrow)
			{
				$depth  = count(explode(',',$rrow['path'])); //计算节点的深度
				$sign   = ($rrow['parentid'] == 0) ? str_repeat('&nbsp;',1) : str_repeat('&nbsp;',4);
				$node[] = array(
					'id'   => $rrow['id'],
					'name' => str_repeat($sign,$depth).'◆-'.$rrow['name'],
					'path' => $rrow['path'],
					'sort_number'=>$rrow['sort_number'],
					'elite'=>$rrow['elite'],
					'state'=>$rrow['state']

					);
			}
			$tree[] = array(
				'id'    => $row['id'],
				'name'  => $row['name'],
				'child' => $node,
				'sort_number'=>$row['sort_number'],
				'elite'=>$row['elite'],
				'state'=>$row['state']
				);
			unset($node);
		}
		return $tree;
	}

	/**
	 * 构造当前节点的路径(使用前需要先获得父节点的路径) 
	 *
	 * @param int    $id         节点id
	 * @param string $parentPath 父节点路径
	 *
	 * @access private
	 * @return bool
	 */
	private function buildPath($id,$parentPath = null)
	{
		$path = ($parentPath == null) ? $id : $parentPath.','.$id;
		$sql  = "update $this->dt set path='$path' where id ='$id'";
		
		return $this->_db->update($sql);  
	}

	/**
	 * 重新构造当前节点的路径(使用前需要先获得父节点的路径) 
	 *
	 * @param int    $id         节点id
	 * @param string $parentPath 父节点路径
	 *
	 * @access private
	 * @return void
	 */
	private function rebuildPath($id, $parentPath='')
	{
		$path = ($parentPath <>'') ? $parentPath.','.$id : $id;
		$this->_db->update("update $this->dt set path='$path' where id ='$id'");     
		$rows = $this->_db->get_all("select * from $this->dt where parentid='$id'");
		foreach($rows as $row)
		{
			$this->rebuildPath($row['id'],$path);
		}
	}

	//根据类别的路径，取得相应的信息
	public function getPathInfo($path)
	{
		if(empty($path)) return false;
		$pathinfo = "";
		$path = explode(',',$path);
		foreach($path as $key=>$val)
		{
			$rs = self::get($val);
			$pathinfo[$key]=$rs;
		}
		return $pathinfo;
	}

	public function getPathInfoExt($path,$type=1)
	{
		$pathinfo = self::getPathInfo($path);
		if(empty($pathinfo)) return false;
		$res = "";
		$url = SITE_PATH.'cms/list.php?cid=';
		if($type == 2)
		{
			$url = SITE_PATH.'provider/list.php?cid=';
		}
		foreach($pathinfo as $key=>$val)
		{
			$tag = "";
			if($key>0) $tag = "--&gt;";
			$res .= $tag."<a href='".$url.$val['id']."' >".$val['name']."</a>";
		}
		return $res;
	}

}

?>