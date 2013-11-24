<?php
class SigninController extends BaseController{
	public $_userServ = null;

	public function __construct()
	{
		parent::beforeRun($resource,$action);

		Import::loadService('UserService');
		$this->_userServ = new UserService();
		$this->userinfo = $this->_userServ->getUserInfo(1);
	}

	public function index()
	{
		echo 'index';
	}
	
	public function signin_sns(){
		if(isset($_GET['type'][1])){
			$type = $_GET['type'];
			$invit_code = isset($_GET['key'])?$_GET['key']:0;
			Import::loadClass('sns/SnsGateway');
			$sns = new SnsGateway();
			$sns_type = array("sina","qq");
			if(!in_array($type, $sns_type)){
				$this->redirect('/index');
				exit;
			}
			$forward = isset($_SESSION['forward'])?'http://'.WWW_URL.$_SESSION['forward']:'';
			$res = $sns->probeLogin(array($type),"/signin/callback/",array('key'=>$invit_code,'forward'=>urlencode($forward)));
			if($res){
				$this->redirect($res[$type]);
				exit;
			}
		}
		$this->redirect('/index');
	}

	public function callback(){
		$error = isset($_GET['error'])?$_GET['error']:'';
		
	}	

}

