<?php
class DetailController extends BaseController{
	public $_personObj = null;
	public $_pcateObj = null;
	public $_catObj = null;
	public $_productObj = null;
	public $_pimgObj = null;
	public $_nav_typeid = null;
	public $_nav_typename = null;

	public function __construct()
	{
		parent::beforeRun($resource,$action);
		$this->_scriptMap = array('js/jquery.min.js','js/default.js');
		$this->_cssMap = array('css/css.css');
		$this->setLayOut('default');

		Import::loadModel('PersonModel');
		$this->_personObj = new PersonModel();
		Import::loadModel('PersonCategoryModel');
		$this->_pcateObj = new PersonCategoryModel();
		Import::loadModel('CategoryModel');
		$this->_catObj = new CategoryModel();
		Import::loadModel('ProductModel');
		$this->_productObj = new ProductModel();
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
		$this->_data['title'] = '作品';
		if(!isset($_GET['product_id'])){
			echo "param is empty, please go back";
			exit;
		}

		$state = 1;
		$person_list = $this->_personObj->getList($this->_nav_typeid,$state,40); //该大分类下的成员
		$catearray	=  $this->_catObj->getChildNodeById(PRODUCT_ROOT_CATEID); //整个作品的分类
		
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


		if($this->_nav_typename == 'photographers' || $this->_nav_typename == 'retouching'){
			$catearray	=  $this->_catObj->getChildNodeById(PRODUCT_ROOT_CATEID);
			$catearray = GetColumn($catearray, 'id','name');
			$this->_data['catearray'] = $catearray;
		}
		
		$product_id = $_GET['product_id'];
		$product_imgs = $this->_pimgObj->getProductImagesOrder($product_id);
		$this->_data['product_imgs'] = $product_imgs;

		$this->_data['catearray'] = $catearray;
		$this->_data['person_list'] = $person_list;


		$this->rendercAction('product_detail');
	}

	public function bio()
	{
		$this->_data['title'] = 'Bio';
		if(!isset($_GET['person_id'])){
			echo "param is empty, please go back";
			exit;
		}

		$state = 1;
		$person_list = $this->_personObj->getList($this->_nav_typeid,$state,40); //该大分类下的成员
		$catearray	=  $this->_catObj->getChildNodeById(PRODUCT_ROOT_CATEID); //整个作品的分类
		
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


		if($this->_nav_typename == 'photographers' || $this->_nav_typename == 'retouching'){
			$catearray	=  $this->_catObj->getChildNodeById(PRODUCT_ROOT_CATEID);
			$catearray = GetColumn($catearray, 'id','name');
			$this->_data['catearray'] = $catearray;
		}

		$this->_data['catearray'] = $catearray;
		$this->_data['person_list'] = $person_list;


		$this->rendercAction('person_bio');
	}
}

?>