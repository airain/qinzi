<?php
Import::loadController('admin/AdminController');
class ManagerController extends AdminController{
	public function __construct()
	{
		parent::beforeRun($resource,$action);
		$this->_scriptMap = array('js/jquery.min.js','js/common.js');
		$this->_cssMap = array('css/admin.css');
		$this->setLayOut('admin');
	}

	public function index()
	{
		$this->_data['title'] = '管理员管理';

		Import::loadModel('ManagerModel');
		$mObj = new ManagerModel();
		$managers = $mObj->get_managers();
		$this->_data['managers'] = $managers;

		$this->rendercAction('admin/manager_index',false);
	}

	public function add()
	{
		$this->_data['title'] = '管理员管理--增加';

		$this->rendercAction('admin/manager_add',false);
	}

	public function add_save()
	{
		$this->_data['title'] = '管理员管理--增加--保存';
		if(empty($_POST['user_pwd']) || empty($_POST['user_name'])){
			exit('param is empty, please go back');
		}
		$user_name = $_POST['user_name'];
		$user_pwd = $_POST['user_pwd'];
		$user_pwd = md5($user_pwd);
		$manager_array = array(
			'user_name'       => $user_name,
			'user_pwd'		=>$user_pwd,
			'lastlogin_time' =>time()
			);

		Import::loadModel('ManagerModel');
		$mObj = new ManagerModel();
		$rs = $mObj->save_manager($manager_array);
		
		$c_url = SITE_URLI.'/admin-manager';
		if($rs)
			$this->_message->show("操作成功",$c_url);
		else
			$this->_message->show("操作失败",$c_url.'add');
		exit;
	}

	public function modify()
	{
		$this->_data['title'] = '管理员管理--修改';
		if(!isset($_GET['user_id'])){
			exit('user_id is empty, please go back');
		}
		$user_id = intval($_GET['user_id']);

		Import::loadModel('ManagerModel');
		$mObj = new ManagerModel();
		$manager = $mObj->get_manager($user_id);
		$this->_data['manager'] = $manager;

		$this->rendercAction('admin/manager_modify',false);
	}

	public function modify_save()
	{
		$this->_data['title'] = '管理员管理--修改--保存';
		if(empty($_POST['user_id']) || empty($_POST['user_pwd']) || empty($_POST['user_name'])){
			exit('param is empty, please go back');
		}
		$user_id = intval($_POST['user_id']);
		$user_name = $_POST['user_name'];
		$user_pwd = $_POST['user_pwd'];
		$user_pwd = md5($user_pwd);
		$manager_array = array(
			'user_name'       => $user_name,
			'user_pwd'		=>$user_pwd,
			'lastlogin_time' =>time()
			);

		Import::loadModel('ManagerModel');
		$mObj = new ManagerModel();
		$rs = $mObj->edit_manager($manager_array,$user_id);
		
		$c_url = SITE_URLI.'/admin-manager';
		if($rs)
			$this->_message->show("操作成功",$c_url);
		else
			$this->_message->show("操作失败",$c_url.'/modify?user_id='.$user_id);
		exit;
	}


	public function delete()
	{
		$this->_data['title'] = '管理员管理--删除';
		if(empty($_GET['user_id'])){
			exit('param is empty, please go back');
		}
		$user_id = intval($_GET['user_id']);

		Import::loadModel('ManagerModel');
		$mObj = new ManagerModel();
		$rs = $mObj->delete_manager($user_id);
		
		$c_url = SITE_URLI.'/admin-manager';
		if($rs)
			$this->_message->show("操作成功",$c_url);
		else
			$this->_message->show("操作失败",$c_url);
		exit;	
	}
}

?>