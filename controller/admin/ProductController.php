<?php
Import::loadController('admin/AdminController');
class ProductController extends AdminController{
	public $_catObj = null;
	public $_personObj = null;
	public $_nav_typeid = null;
	public $_nav_typename = null;
	public $_productObj = null;
	public $_pcateObj = null;
	public $_pimgObj = null;

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
		Import::loadModel('ProductModel');
		$this->_productObj = new ProductModel();
		Import::loadModel('PersonCategoryModel');
		$this->_pcateObj = new PersonCategoryModel();
		Import::loadModel('ProductImageModel');
		$this->_pimgObj = new ProductImageModel();

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
		$this->_data['title'] = $this->_nav_typename.'__作品__管理';
		$catearray	=  $this->_catObj->getChildNodeById(PRODUCT_ROOT_CATEID);

		$state = isset($_GET['state'])?$_GET['state']:'';
		$items['nav_typeid'] = $this->_nav_typeid;
		if(isset($_GET['cate_id'])) {
			$items['cate_id'] = $_GET['cate_id'];
			$this->_data['cate_id'] = $items['cate_id'];
		}
		if(isset($_GET['person_id'])){
			$items['person_id'] = $_GET['person_id'];
			//根据成员id,列出成员的作品分类,作品分组
			$person_cate = $this->_pcateObj->getCateByPersonId($items['person_id']);
			$person = $this->_personObj->getPerson($items['person_id']);

			$this->_data['person_cate']  = $person_cate;
			$this->_data['person_id'] = $items['person_id'];
			$this->_data['person'] = $person;
		}

		if(isset($_GET['skey_title']) && !empty($_GET['skey_title'])){
			$items['title'] = $_GET['skey_title'];
			$this->_data['skey_title'] = $items['title'];
		}

		if($this->_nav_typename == 'photographers' || $this->_nav_typename == 'retouching'){
			$catearray	=  $this->_catObj->getChildNodeById(PRODUCT_ROOT_CATEID);
			$catearray = GetColumn($catearray, 'id','name');
			$this->_data['catearray'] = $catearray;
		}

		$count = $this->_productObj->getProductByItemsCnt($items);

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

		$list = $this->_productObj->getProductByItems($items,$limit);

		switch ($this->_nav_typeid) {
			case 1:
				$tpl = 'models_product_index';
				break;
			case 2:
				$tpl = 'models_product_index';
				break;
			case 3:
				$tpl = 'person_product_index';
				break;
			case 4:
				$tpl = 'person_product_index';
				break;			
		}

		$this->_data['list'] = $list;
		$this->_data['catearray'] = $catearray;
		$this->rendercAction('admin/'.$tpl,false);
	}


	public function add()
	{
		$this->_data['title'] = $this->_nav_typename.'--作品管理--增加';
		$this->_data['person_id'] = isset($_GET['person_id'])?$_GET['person_id']:'';
		$person = $this->_personObj->getPerson($this->_data['person_id']);
		$this->_data['person'] = $person;

		$person_catearray = $this->_pcateObj->getCateByPersonId($this->_data['person_id']);
		$this->_data['person_catearray'] = $person_catearray;

		$tpl = 'person_product_add';
		$this->rendercAction("admin/$tpl",false);
	}

	public function add_save()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--增加--保存';
		
		$title		 = $_POST['title'];
		$nav_typeid  = $_POST['nav_typeid'];
		$person_id	 = $_POST['person_id'];
		$brief	     = addslashes($_POST['brief']);
		$state		 = $_POST['state'];
		$createtime	 = strtotime($_POST['createtime']);

		$additems = array(
			'title' => $title,
			'nav_typeid'  => $this->_nav_typeid,
			'person_id' => $person_id,
			'brief'       => $brief,
			'state'       => $state,
			'createtime'  => $createtime,
			);
		if(isset($_POST['cate_id'])){
			$additems['cate_id'] = $_POST['cate_id'];
		}

		$ufile = $_FILES['upload_img'];
		$umorefile = array();
		foreach($ufile['name'] as $k=>$v)
		{
			if(empty($ufile['name'][$k])) continue;
			$image_file = array('name'=>$ufile['name'][$k],'type'=>$ufile['type'][$k],'tmp_name'=>$ufile['tmp_name'][$k]);
			$img_name = $this->uploadImage($image_file);
			$umorefile[$k] = $img_name;				
		}


		$thumbfile = $_FILES['thumb_img'];
		$tmorefile = array();
		foreach($thumbfile['name'] as $k=>$v)
		{
			if(empty($thumbfile['name'][$k])) continue;
			$image_file = array('name'=>$thumbfile['name'][$k],'type'=>$thumbfile['type'][$k],'tmp_name'=>$thumbfile['tmp_name'][$k]);
			$img_name = $this->uploadImage($image_file);
			$tmorefile[$k] = $img_name;				
		}

		$c_url = SITE_URLI.'/admin-product/add';
		if($product_id = $this->_productObj->saveProduct($additems)){
			$this->_pimgObj->insertMoreImage($product_id,$umorefile,$tmorefile);
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
		$c_url = $_SERVER['HTTP_REFERER'];

		$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0 ;
		if ($this->_productObj->removeProduct($id)){
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
	}
	public function delmany()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--删除';
		$c_url = $_SERVER['HTTP_REFERER'];

		$id = $_POST['id'];
		$nav_typeid = $_POST['nav_typeid'];
		foreach($id as $key=>$val){
			$this->_productObj->removeProduct($val);
			$this->_pimgObj->delUploadImageByPid($nav_typeid,$val);
		}
		$this->_message->show("操作成功", $c_url);
	}

	public function delete_upload_img()
	{
		$id = $_GET['id'];
		$nav_typeid = $_GET['nav_typeid'];


		if(empty($id)){
			$result = array('result'=>0,'msg'=>'删除图片失败');
		}else{
			$rs = $this->_pimgObj->delUploadImage($nav_typeid,$id);
			$result = array('result'=>1,'msg'=>'删除图片成功');
		}
		echo json_encode($result);
	}

	public function modify()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--修改';
		
		$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0 ;
		$product = $this->_productObj->getProduct($id);
		$this->_data['product'] = $product;
		$this->_data['person_id'] = $product['person_id'];

		$person = $this->_personObj->getPerson($this->_data['person_id']);
		$this->_data['person'] = $person;

		$person_catearray = $this->_pcateObj->getCateByPersonId($this->_data['person_id']);
		$this->_data['person_catearray'] = $person_catearray;

		$upload_imgs = $this->_pimgObj->getProductImages($id);
		$this->_data['upload_imgs'] = $upload_imgs;

		$tpl = 'person_product_modify';
		$this->rendercAction("admin/$tpl",false);
	}

	public function modify_save()
	{
		$this->_data['title'] = $this->_nav_typename.'管理--修改--保存';

		$id			 = $_POST['id'];
		$title		 = $_POST['title'];
		$nav_typeid  = $_POST['nav_typeid'];
		$person_id	 = $_POST['person_id'];
		$brief	     = addslashes($_POST['brief']);
		$state		 = $_POST['state'];
		$createtime	 = strtotime($_POST['createtime']);
		
		$img_order_array = array();
		for($i=1;$i<50;$i++){
			$img_order_array[] = $_POST['imgorder_'.$i];
		}
		$this->_pimgObj->updateImgOrder($id,$img_order_array);
		

		$additems = array(
			'title' => $title,
			'nav_typeid'  => $this->_nav_typeid,
			'person_id' => $person_id,
			'brief'       => $brief,
			'state'       => $state,
			'createtime'  => $createtime,
		);
		$additems['cate_id'] = 0;
		if(isset($_POST['cate_id'])){
			$additems['cate_id'] = $_POST['cate_id'];
		}

		$ufile = $_FILES['upload_img'];
		$umorefile = array();
		foreach($ufile['name'] as $k=>$v)
		{
			if(empty($ufile['name'][$k])) continue;
			$image_file = array('name'=>$ufile['name'][$k],'type'=>$ufile['type'][$k],'tmp_name'=>$ufile['tmp_name'][$k]);
			$img_name = $this->uploadImage($image_file);
			$umorefile[$k] = $img_name;				
		}


		$thumbfile = $_FILES['thumb_img'];
		$tmorefile = array();
		foreach($thumbfile['name'] as $k=>$v)
		{
			if(empty($thumbfile['name'][$k])) continue;
			$image_file = array('name'=>$thumbfile['name'][$k],'type'=>$thumbfile['type'][$k],'tmp_name'=>$thumbfile['tmp_name'][$k]);
			$img_name = $this->uploadImage($image_file);
			$tmorefile[$k] = $img_name;				
		}


		$c_url = SITE_URLI.'/admin-product/modify?nav_typeid='.$this->_nav_typeid.'&id='.$id;
		if($person_id = $this->_productObj->editProduct($additems,$id)){
			$this->_pimgObj->insertMoreImage($id,$umorefile,$tmorefile);
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
		exit;
	}



}

?>