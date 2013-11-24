<?php
Import::loadController('admin/AdminController');
class CategoryController extends AdminController{
	public $_catObj = null;
	public function __construct()
	{
		parent::beforeRun($resource,$action);
		$this->_scriptMap = array('js/jquery.min.js','js/common.js');
		$this->_cssMap = array('css/admin.css');
		$this->setLayOut('admin');

		Import::loadModel('CategoryModel');
		$this->_catObj = new CategoryModel();
	}

	public function index()
	{
		$this->_data['title'] = '分类管理';

		$tree = $this->_catObj->getTableTree();
		$this->_data['tree'] = $tree;

		$this->rendercAction('admin/category_index',false);
	}

	public function add()
	{
		$this->_data['title'] = '分类管理--增加';
		
		$cateid = isset($_GET['cateid']) ? (int)$_GET['cateid'] : 0;
		$node   =  $this->_catObj->get($cateid);
		$tree	=  $this->_catObj->getOptionTree();
		$this->_data['tree'] = $tree;
		$this->_data['node'] = $node;
		$this->_data['parentid'] = isset($node['id'])?$node['id']:0;
		$this->rendercAction('admin/category_add',false);
	}

	public function add_save()
	{
		$this->_data['title'] = '分类管理--增加--保存';
		
		$name = $_POST['name'];
		if(empty($name)){
		  $this->_message->show("分类名称不能为空",$c_url);
		  exit;
		}

		$parentid = $_POST['parentid'];
		$c_url = SITE_URLI.'/admin-category/add?cateid='.$parentid;

		if($this->_catObj->save($parentid,$name,'分类介绍'))
			$this->_message->show("操作成功",$c_url);
		else
			$this->_message->show("操作成功",$c_url);
		exit;
	}

	public function delete()
	{
		$this->_data['title'] = '分类管理--删除';
		$c_url = SITE_URLI.'/admin-category';

		$cateid = isset($_GET['cateid']) ? (int)$_GET['cateid'] : 0 ;
		if ($this->_catObj->remove($cateid)){
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
	}

	public function modify()
	{
		$this->_data['title'] = '分类管理--修改';
		
		$cateid = isset($_GET['cateid']) ? (int)$_GET['cateid'] : 0;
		$data   =  $this->_catObj->get($cateid);
		$tree	=  $this->_catObj->getOptionTree();
		$this->_data['tree'] = $tree;
		$this->_data['data'] = $data;

		$this->rendercAction('admin/category_modify',false);
	}

	public function modify_save()
	{
		$this->_data['title'] = '分类管理--修改--保存';
		$cateid = $_POST['cateid'];
		$initparentid = $_POST['initparentid'];
		$parentid = $_POST['parentid'];
		$name = $_POST['name'];
		$c_url = SITE_URLI.'/admin-category/modify?cateid='.$cateid;

		if($this->_catObj->update($initparentid,$parentid,$name,$cateid))
			$this->_message->show("操作成功",$c_url);
		else
			$this->_message->show("操作成功",$c_url);
		exit;
	}

}

?>