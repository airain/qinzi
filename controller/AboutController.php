<?php
class AboutController extends BaseController{
	public $_artObj = null;
	public function __construct()
	{
		parent::beforeRun($resource,$action);
		$this->_scriptMap = array('js/jquery.min.js','js/default.js');
		$this->_cssMap = array('css/css.css');
		$this->setLayOut('default');
	}

	public function index()
	{
		$this->_data['title'] = '关于我们';

		Import::loadModel('ArticleModel');
		$this->_artObj = new ArticleModel();
		$id = 8;
		$article = $this->_artObj->getArticle($id);
		$this->_data['article'] = $article;

		$this->rendercAction('about');
	}
}

?>