<?php
Import::loadController('admin/AdminController');
class PersonController extends AdminController{
	public $_personObj = null;

	public function __construct()
	{
		parent::beforeRun($resource,$action);
		$this->_scriptMap = array('js/jquery.min.js','js/common.js','js/calendar/WdatePicker.js');
		$this->_cssMap = array('css/admin.css');
		$this->setLayOut('admin');

		Import::loadModel('UsersModel');
		$this->_personObj = new UsersModel();
	}

	public function index()
	{
		$this->_data['title'] = '用户管理';

		$where = isset($_GET['skey_person_name'])?'nick like "%'.$_GET['skey_person_name'].'%"':'';
		$count = $this->_personObj->getCount($where);
		/*page*/
		Import::loadClass('BasePager');
		$per_page = 10;	
		$maxlength = 10;
		$pager = new BasePager(SITE_URLI.'/admin-person', $count, $per_page, $maxlength);
		$current_page = $pager->getPageNumber($_GET);		
		$pager->makeUrl($_GET);
		$pager->paginate($current_page);
		$this->_data['page_str'] =  $pager->output;
		/*page*/

		$list = $this->_personObj->getAll($where,'',$per_page, ($current_page-1) * $per_page);
		$this->_data['list'] = $list;
		$this->rendercAction('admin/person_index',false);
	}

	public function add()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--增加';
		//$catearray	=  $this->_catObj->getChildNodeById(PRODUCT_ROOT_CATEID);
		$this->_data['catearray'] = $catearray;

		$tpl = "person_add";
		$this->rendercAction("admin/$tpl",false);
	}

	public function add_save()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--增加--保存';
		
		$headimg = '';
		if(isset($_FILES['headimg'])){
			$headimg = $_FILES['headimg'];
			if(!empty($headimg['name'])){
				$headimg = $this->uploadImage($headimg);
			}
		}

		$person_name = $_POST['person_name'];
		$nav_typeid  = $_POST['nav_typeid'];
		$nationality = $_POST['nationality'];
		$gender      = $_POST['gender'];
		$headimg     = $headimg;
		$bio	     = addslashes($_POST['bio']);
		$notes		 = $_POST['notes'];
		$state		 = $_POST['state'];
		$createtime	 = strtotime($_POST['createtime']);

		$additems = array(
			'person_name' => $person_name,
			'nav_typeid'  => $this->_nav_typeid,
			'nationality' => $nationality,
			'gender'      => $gender,
			'headimg'	  => $headimg,
			'bio'         => $bio,
			'notes'       => $notes,
			'state'       => $state,
			'createtime'  => $createtime,
			);

		if($this->_nav_typename == 'models'){
			$additems['waist']		= $_POST['waist'];
			$additems['hips']		= $_POST['hips'];
			$additems['shoes']		= $_POST['shoes'];
			$additems['hair']		= $_POST['hair'];
			$additems['eyes']		= $_POST['eyes'];
		}

		$c_url = SITE_URLI.'/admin-person/add';
		if($person_id = $this->_personObj->savePerson($additems)){
			if(isset($_POST['cate_id'])){
				foreach($_POST['cate_id'] as $key=>$val){
					$this->_pcateObj->saveCategory(array('person_id'=>$person_id,'cate_id'=>$val));
				}
			}
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
		exit;
	}


    private function uploadImage($image_array){
    	Import::loadClass('BaseGdImage');
		switch ($this->_nav_typeid) {
			case 1:
				$path = MODELS_IMG_PATH;
				break;
			case 2:
				$path = CELEBRITIES_IMG_PATH;
				break;
			case 3:
				$path = PHOTOGRAPHERS_IMG_PATH;
				break;
			case 4:
				$path = RETOUCHING_IMG_PATH;
				break;			
		}
  		$gd = new BaseGdImage($path,$path);
  		if($img_name = $gd->uploadImages($image_array)){
  			return $img_name;
  		}
		return false;
    }


	public function delete()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--删除';
		$c_url = SITE_URLI.'/admin-person';

		$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0 ;
		if ($this->_personObj->removePerson($id)){
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
	}
	public function delmany()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--删除';
		$c_url = SITE_URLI.'/admin-person';

		$id = $_POST['id'];
		if ($this->_personObj->removeMany($id)){
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
	}

	public function modify()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--修改';
		
		$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0 ;
		$person = $this->_personObj->getPerson($id);
		$this->_data['person'] = $person;

		$catearray	=  $this->_catObj->getChildNodeById(PRODUCT_ROOT_CATEID);
		$this->_data['catearray'] = $catearray;
		$pcatearray = $this->_pcateObj->getCateByPersonId($id);
		$this->_data['pcatearray'] = $pcatearray;

		$this->rendercAction('admin/person_modify',false);
	}

	public function modify_save()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--修改--保存';
		
		$id			 = $_POST['id'];
		$person_name = $_POST['person_name'];
		$nav_typeid  = $_POST['nav_typeid'];
		$nationality = $_POST['nationality'];
		$gender      = $_POST['gender'];
		$bio	     = addslashes($_POST['bio']);
		$notes		 = $_POST['notes'];
		$state		 = $_POST['state'];
		$createtime	 = strtotime($_POST['createtime']);

		$additems = array(
			'person_name' => $person_name,
			'nav_typeid'  => $this->_nav_typeid,
			'nationality' => $nationality,
			'gender'      => $gender,
			'bio'         => $bio,
			'notes'       => $notes,
			'state'       => $state,
			'createtime'  => $createtime,
			);

		if($this->_nav_typename == 'models'){
			$additems['waist']		= $_POST['waist'];
			$additems['hips']		= $_POST['hips'];
			$additems['shoes']		= $_POST['shoes'];
			$additems['hair']		= $_POST['hair'];
			$additems['eyes']		= $_POST['eyes'];
		}

		if(isset($_FILES['headimg'])){
			$headimg = $_FILES['headimg'];
			if(!empty($headimg['name'])){
				$headimg = $this->uploadImage($headimg);
				$additems['headimg'] = $headimg;
			}
		}

		$c_url = SITE_URLI.'/admin-person/modify?nav_typeid='.$this->_nav_typeid.'&id='.$id;
		if($this->_personObj->editPerson($additems,$id)){

			if(isset($_POST['cate_id'])){
				$this->_pcateObj->removeCategoryByPersonId($id);
				foreach($_POST['cate_id'] as $key=>$val){
					$this->_pcateObj->saveCategory(array('person_id'=>$id,'cate_id'=>$val));
				}
			}

			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
		exit;
	}

}

?>
