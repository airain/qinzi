<?php
Import::loadController('admin/AdminController');
class PgroupController extends AdminController{
	public $_catObj = null;
	public $_personObj = null;
	public $_nav_typeid = null;
	public $_nav_typename = null;
	public $_groupObj = null;
	public $_productObj = null;
	public $_pcateObj = null;

	public function __construct()
	{
		parent::beforeRun($resource,$action);
		$this->_scriptMap = array('js/jquery.min.js','js/common.js','js/calendar/WdatePicker.js');
		$this->_cssMap = array('css/admin.css');
		$this->setLayOut('admin');

		Import::loadModel('CategoryModel');
		$this->_catObj = new CategoryModel();
		Import::loadModel('PersonModel');
		$this->_personObj = new PersonModel();
		Import::loadModel('ProductGroupModel');
		$this->_groupObj = new ProductGroupModel();
		Import::loadModel('ProductModel');
		$this->_productObj = new ProductModel();
		Import::loadModel('PersonCategoryModel');
		$this->_pcateObj = new PersonCategoryModel();

		$this->_nav_typeid = isset($_GET['nav_typeid'])?$_GET['nav_typeid']:'';
		if(empty($this->_nav_typeid) && isset($_POST['nav_typeid'])){
			$this->_nav_typeid = $_POST['nav_typeid'];
		}
		$this->_nav_typeid = !empty($this->_nav_typeid)?$this->_nav_typeid:1;
		$this->_data['nav_typeid'] = $this->_nav_typeid;
		$this->_nav_typename =  $this->_nav_type[$this->_nav_typeid];
		$this->_data['nav_typename'] = $this->_nav_typename;
	}

	public function index()
	{
		$this->_data['title'] = $this->_nav_typename.'__作品__分组__管理';

		$person_id = $_GET['person_id'];
		$count = $this->_groupObj->getListCount($person_id);

		/*page*/
		Import::loadClass('BasePager');		
		foreach($_GET as $k=>$v){
			if($v !=='p' && empty($v)) unset($_GET[$k]);
		}
		$per_page = 10;	
		$maxlength = 10;
		$pager = new BasePager(SITE_URLI.'/admin-product', $count, $per_page, $maxlength);
		$current_page = $pager->getPageNumber($_GET);		
		$pager->makeUrl($_GET);
		$pager->paginate($current_page);
		$this->_data['page_str'] =  $pager->output;
		$limit = $pager->getPageLimit($count,$current_page,$per_page);
		/*page*/

		$list = $this->_groupObj->getList($person_id,$limit);

		$tpl = 'product_group_index';

		$this->_data['list'] = $list;
		$this->rendercAction('admin/'.$tpl,false);
	}


	public function add()
	{
		$this->_data['title'] = $this->_nav_typename.'--作品分组--增加';
		$this->_data['person_id'] = isset($_GET['person_id'])?$_GET['person_id']:'';

		$tpl = 'product_group_add';
		$this->rendercAction("admin/$tpl",false);
	}

	public function add_save()
	{
		$this->_data['title'] = $this->_nav_typename.'--作品分组管理--增加--保存';
		
		$group_name	 = $_POST['group_name'];
		$person_id	 = $_POST['person_id'];

		$additems = array(
			'group_name' => $group_name,
			'person_id' => $person_id,
			);

		$c_url = SITE_URLI.'/admin-pgroup/add';
		if($person_id = $this->_groupObj->saveGroup($additems)){
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
		exit;
	}


	public function delete()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--删除';
		$c_url = SITE_URLI.'/admin-product';

		$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0 ;
		if ($this->_groupObj->removeGroup($id)){
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
	}
	public function delmany()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--删除';
		$c_url = SITE_URLI.'/admin-pgroup';

		$id = $_POST['id'];
		if ($this->_groupObj->removeMany($id)){
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
	}

	public function modify()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--修改';
		
		$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0 ;
		$group = $this->_groupObj->getGroup($id);
		$this->_data['group'] = $group;
		$this->_data['person_id'] = $group['person_id'];

		$tpl = 'product_group_modify';
		$this->rendercAction("admin/$tpl",false);
	}

	public function modify_save()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--修改--保存';

		$id			 = $_POST['id'];
		$group_name		 = $_POST['group_name'];

		$additems = array(
			'group_name' => $group_name,
			);

		$c_url = SITE_URLI.'/admin-pgroup/modify?nav_typeid='.$this->_nav_typeid.'&id='.$id;
		if($person_id = $this->_groupObj->editGroup($additems,$id)){
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
		exit;
	}

}

?>