<?php
Import::loadController('admin/AdminController');
class ArticleController extends AdminController{
	public $_catObj = null;
	public $_artObj = null;

	public function __construct()
	{
		parent::beforeRun($resource,$action);
		$this->_scriptMap = array('js/jquery.min.js','js/common.js','js/calendar/WdatePicker.js');
		$this->_cssMap = array('css/admin.css');
		$this->setLayOut('admin');

		Import::loadModel('CategoryModel');
		$this->_catObj = new CategoryModel();
		Import::loadModel('ArticleModel');
		$this->_artObj = new ArticleModel();
	}

	public function index()
	{
		$this->_data['title'] = '文章管理';
		$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
		$tree	=  $this->_catObj->getOptionTree();
		$count = $this->_artObj->getListCount(0,$category_id);

		/*page*/
		Import::loadClass('BasePager');		
		foreach($_GET as $k=>$v){
			if($v !=='p' && empty($v)) unset($_GET[$k]);
		}
		$per_page = 10;	
		$maxlength = 10;
		$pager = new BasePager(SITE_URLI.'/admin-article', $count, $per_page, $maxlength);
		$current_page = $pager->getPageNumber($_GET);		
		$pager->makeUrl($_GET);
		$pager->paginate($current_page);
		$this->_data['page_str'] =  $pager->output;
		$limit = $pager->getPageLimit($count,$current_page,$per_page);
		/*page*/

		$list = $this->_artObj->getList(0,$category_id,$limit);

		$this->_data['list'] = $list;
		$this->_data['tree'] = $tree;
		$this->rendercAction('admin/article_index',false);
	}

	public function search()
	{
		$this->_data['title'] = '文章管理--搜索';
		$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
		$tree	=  $this->_catObj->getOptionTree();

		$skey = $_GET['skey'];
		$count = $this->_artObj->getSearchListCount($skey,$category_id);

		/*page*/
		Import::loadClass('BasePager');		
		foreach($_GET as $k=>$v){
			if($v !=='p' && empty($v)) unset($_GET[$k]);
		}
		$per_page = 10;	
		$maxlength = 10;
		$pager = new BasePager(SITE_URLI.'/admin-article', $count, $per_page, $maxlength);
		$current_page = $pager->getPageNumber($_GET);		
		$pager->makeUrl($_GET);
		$pager->paginate($current_page);
		$this->_data['page_str'] =  $pager->output;
		$limit = $pager->getPageLimit($count,$current_page,$per_page);
		/*page*/

		$list = $this->_artObj->getSearchList($skey,$category_id,$limit);

		$this->_data['list'] = $list;
		$this->_data['tree'] = $tree;
		$this->rendercAction('admin/article_index',false);
	}

	public function add()
	{
		$this->_data['title'] = '文章管理--增加';
		$tree = $this->_catObj->getOptionTree(0,1);
		$this->_data['tree'] = $tree;	
		$this->rendercAction('admin/article_add',false);
	}

	public function add_save()
	{
		$this->_data['title'] = '文章管理--增加--保存';
		
		Import::loadClass('UploadFile');
		$up = new UploadFile();

		//图片上传
		$up = new UploadFile();
		$up->path = PUBLIC_PATH."upload/article_images/";
		$up->maxSize = 10240000;
		$up->upType = "jpg,gif,rar,bmp";
		$imageName = '';
		$image = $_FILES['image'];
		if ($image['name'] && $up->upload($image,true)){
			$imageName = $up->msg == null ?  'upload/article_images/'.$up->upFile : '';
		}

		$title = $_POST['title'];
		$keyword = $_POST['keyword'];
		$category_id = $_POST['category_id'];
		$copyfrom = $_POST['copyfrom'];
		$author = $_POST['author'];
		$state = $_POST['state'];
		$top = $_POST['top'];
		$hot = $_POST['hot'];
		$elite = $_POST['elite'];
		$iscomment = $_POST['iscomment'];
		$content = addslashes($_POST['content']);
		$pubtime = $_POST['pubtime'];

		//构造写入数据库的键值对数据(键代表数据库字段,值代表写入对应数据字段的值)
		$article = array(
			'title'       => $title,
			'keyword'       => $keyword,
			'category_id'      => $category_id,
			'copyfrom'      => $copyfrom,
			'author'		=>$author,
			'content'      => $content,
			'state'     => isset($state) ? 1 : 0,
			'top'       => isset($top) ? 1 : 0,
			'hot'       => isset($hot) ? 1 : 0,
			'elite' => isset($elite) ? 1 : 0,
			'hit'         => intval($hit),
			'iscomment'       => isset($iscomment) ? 1 : 0,
			'pubtime'     => strtotime($pubtime),
			);
		if(!empty($imageName)) 
			$article = array_merge($article,array('image'=>$imageName));

		$c_url = SITE_URLI.'/admin-article';
		if($this->_artObj->saveArticle($article)){
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
		exit;
	}

	public function delete()
	{
		$this->_data['title'] = '文章管理--删除';
		$c_url = SITE_URLI.'/admin-article';

		$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0 ;
		if ($this->_artObj->removeArticle($id)){
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
	}
	public function delmany()
	{
		$this->_data['title'] = '文章管理--删除';
		$c_url = SITE_URLI.'/admin-article';

		$id = $_POST['id'];
		if ($this->_artObj->removeMany($id)){
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
	}

	public function modify()
	{
		$this->_data['title'] = '文章管理--修改';
		
		$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0 ;
		$article = $this->_artObj->getArticle($id);
		$tree	=  $this->_catObj->getOptionTree();
		$this->_data['tree'] = $tree;
		$this->_data['article'] = $article;

		$this->rendercAction('admin/article_modify',false);
	}

	public function modify_save()
	{
		$this->_data['title'] = '文章管理--修改--保存';
		
		Import::loadClass('UploadFile');
		$up = new UploadFile();

		//图片上传
		$up = new UploadFile();
		$up->path = PUBLIC_PATH."upload/article_images/";
		$up->maxSize = 10240000;
		$up->upType = "jpg,gif,rar,bmp";
		$imageName = '';
		$image = $_FILES['image'];
		if ($image['name'] && $up->upload($image,true)){
			$imageName = $up->msg == null ?  'upload/article_images/'.$up->upFile : '';
		}

		$id = $_POST['id'];
		$title = $_POST['title'];
		$keyword = $_POST['keyword'];
		$category_id = $_POST['category_id'];
		$copyfrom = $_POST['copyfrom'];
		$author = $_POST['author'];
		$state = $_POST['state'];
		$top = $_POST['top'];
		$hot = $_POST['hot'];
		$elite = $_POST['elite'];
		$iscomment = $_POST['iscomment'];
		$content = addslashes($_POST['content']);
		$pubtime = $_POST['pubtime'];

		//构造写入数据库的键值对数据(键代表数据库字段,值代表写入对应数据字段的值)
		$article = array(
			'title'       => $title,
			'keyword'       => $keyword,
			'category_id'      => $category_id,
			'copyfrom'      => $copyfrom,
			'author'		=>$author,
			'content'      => $content,
			'state'     => isset($state) ? 1 : 0,
			'pubtime'     => strtotime($pubtime),
			);
		if(!empty($imageName)) 
			$article = array_merge($article,array('image'=>$imageName));

		$c_url = SITE_URLI.'/admin-article/modify?id='.$id;
		if($this->_artObj->editArticle($article,$id)){
			$this->_message->show("操作成功", $c_url);
		}else{
			$this->_message->show("操作失败,请重试", $c_url); 
		}
		exit;
	}

}

?>