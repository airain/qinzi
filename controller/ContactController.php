<?php
class ContactController extends BaseController{
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
		$this->_data['title'] = '联系我们';

		Import::loadModel('ArticleModel');
		$this->_artObj = new ArticleModel();
		$id = 4;
		$article = $this->_artObj->getArticle($id);
		$this->_data['article'] = $article;

		$this->rendercAction('contact');
	}
}

?>