<?php
/**
 * Sns Gateway module
 *
 * @author hesen2006cn@126.com
 * create_time : 2011-06-23
 *
 * qq api http://open.t.qq.com/resource.php?i=1,1
 * akey = 7537814fd3c844888c8025ff97bd5522
 * skey = 8ff09530387c2bf6713a531e4725bd78
 * 
 */

require_once('qq_weibo2.0.class.php');

class SnsQQTenAdapter{
	// sina key
	private $_key = 'qq';
 	//app key
 	private $_akey = '801104893';
 	//app secret
 	private $_skey = '7daa516ca0314cf3eedbe0ef2120868e';
 	
	private $_org_weibo_id = 'EAFF1B2FB37F5813106C8E4D7E49A456';
 	
 	private $_auth = null;
 	
 	private $_client = null;
 	
 	private $_error_code = null;
 	
 	private $_error_message = null;
 	
	private $_kv_user = null;
		
	private $_kv_line = null;

	private $_kv_msg = null;

	private $_log_file = null;

	private  $_debug = false;

 	public function __construct($akey=null,$skey=null,$debug=false){
		if($akey)
 			$this->_akey = $akey;
		if($skey)
 			$this->_skey = $skey;
		$this->_debug = $debug;
 		if(!isset($_SESSION)) session_start();
 		$this->initConvertList(); 		
 	}

	
	//set log file 
	public function setLogFile($log_file = ''){
		$this->_log_file = $log_file;
	}
	
	private function initServer(){
		if($this->_auth == null){
			$this->_auth = new TencentAuth($this->_akey,$this->_skey,null,null,null,$this->_debug);
			$this->_auth->setLogFile($this->_log_file);
		}
	}
 	
 	// probe login to website
 	public function probeLogin($callback_url){
 		if(empty($callback_url)) return null;
 		
		$this->initServer();

		$url = $this->_auth->getAuthorizeURL('http://'.$_SERVER['HTTP_HOST'].$callback_url);

		return $url;
 	}
 	// login call back
 	public function probeCallBack(){
 		$this->initServer();
		try{
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = preg_replace('@&code=.*@','', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			$token = $this->_auth->getAccessToken('code',$keys) ;
		}catch(Exception $e){
			return false;
		}
		if(empty($token)) return false;
		$_SESSION[$this->_key.'last_key']['oauth_openid'] = $_REQUEST['openid'];
		$_SESSION[$this->_key.'last_key']['oauth_token'] = $token['access_token'];
		$_SESSION[$this->_key.'last_key']['oauth_token_secret'] = isset($token['refresh_token'])?$token['refresh_token']:'';
		$_SESSION[$this->_key.'last_key']['oauth_expires'] = intval($token['expires_in']);
		$_SESSION[$this->_key.'last_key']['oauth_startime'] = time();
		return true;
 	}

	public function refreshToken(){
		$this->initServer();
		try{
			$keys['refresh_token'] = $_SESSION[$this->_key.'last_key']['oauth_token_secret'];
			$token = $this->_auth->getAccessToken('token',$keys) ;
		}catch(Exception $e){
			return false;
		}
		if(empty($token)) return false;
		$_SESSION[$this->_key.'last_key']['oauth_openid'] = $token['openid'];
		$_SESSION[$this->_key.'last_key']['oauth_token'] = $token['access_token'];
		$_SESSION[$this->_key.'last_key']['oauth_token_secret'] = isset($token['refresh_token'])?$token['refresh_token']:'';
		$_SESSION[$this->_key.'last_key']['oauth_expires'] = intval($token['expires_in']);
		$_SESSION[$this->_key.'last_key']['oauth_startime'] = time();
		$res = $_SESSION[$this->_key.'last_key'];
		$res['nickname'] = $token['nick'];
		$res['uid'] = $token['openid'];
		return $res;
	}

	
	public function getOrgId(){
		return $this->_org_weibo_id;
	}
	
	public function isLogin(){
 		if(isset($_SESSION[$this->_key.'last_key'])) 
			return true;
		return false;
	}

 	// get my information
 	public function getMyInfor(){
 		$this->initClient();

		$res = $this->_client->getUserInfo(null);
		
		if(!$this->handleError($res)) return false;
			
		return $this->parseUserPairs($res);
 	}
 	
 	//get user attentions
 	public function getAttentionsByUser($uid_name,$start=0,$limit=20){
 		$this->initClient();
 		
		$param = array('n'=>$uid_name, 'start'=>$start, 'num'=>$limit, 'type'=>1);
 		$res = $this->_client->getfans($param);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseUserPairs($res);
 	}
 	
 	//get user fancy
 	public function getFanciesByUser($uid_name,$start=0,$limit=20){
 		$this->initClient();
 		
 		$param = array('n'=>$uid_name, 'start'=>$start, 'num'=>$limit, 'type'=>0);
 		$res = $this->_client->getfans($start,$limit,$uid_name);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseUserPairs($res);
 	}
 	//get user information
 	public function getUserInfor($uid_name){
 		$this->initClient();
 		
 		$p = array('n'=>$uid_name);
 		$res = $this->_client->getUserInfo($p);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseUserPairs($res);
 	}
 	// add attention
 	public function addAttention($uid_name, $fopenids=false){
 		$this->initClient();
		if($fopenids){
			$p['n'] = '';
			$p['ids'] = $uid_name;
		}else{
			$p['n'] = $uid_name;
			$p['ids'] = '';
		}
 		$p['type'] = 1;
 		$res = $this->_client->setMyidol($p);
 		
 		if(!$this->handleError($res)) return false;
			
 		return true;
 	}
 	//cancel attention
 	public function cancelAttention($uid_name){
 		$this->initClient();
 		
 		$p = array('n'=>$uid_name,'type'=>0);
 		$res = $this->_client->setMyidol($p);
 		
 		if(!$this->handleError($res)) return false;
			
 		return true;
 	}
 	// is attention to user
 	public function isAttention($uid_name){
 		$this->initClient();
 		$p = array('n'=>$uid_name,'type'=>1);
 		$res = $this->_client->checkFriend($p);
 		
		if(!$this->handleError($res)) return false;
			
 		return true;
 	}
	
 	// get my favorites
 	public function getMyFavorites($page=0){
 		$this->initClient();
 		
		$p = array('n'=>$page,'f'=>0,'t'=>0,'lid'=>0,'type'=>1);
 		$res = $this->_client->getFav($p);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseUserPairs($res);
 	}
 	// delete mention by sid
 	public function deleteMentionBySid($sid){
 		$this->initClient();
 		
 		$res = $this->_client->delOne(array('id'=>$sid));
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
 	// issue mention
 	public function issueMention($content, $ip='', $j='0', $w='0', $f=0){
 		$this->initClient();
 		
 		$res = $this->_client->postOne(array('c'=>$content, 'ip'=>$ip, 'j'=>$j, 'w'=>$w, 'f'=>$f, 'type'=>1));
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
 	
 	//issue have image mention
 	public function issueImageMention($content, $img_url, $ip='', $j='0', $w='0', $f=0){
 		$this->initClient();
 		
 		$res = $this->_client->postOne(array('c'=>$content, 'p'=>$img_url, 'ip'=>$ip, 'j'=>$j, 'w'=>$w, 'f'=>$f, 'type'=>1));
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
 	// forward mention
 	public function forwardMention($sid, $content='', $ip='', $j='0', $w='0'){
 		$this->initClient();
 		
 		$res = $this->_client->postOne(array('c'=>$content, 'r'=>$sid, 'ip'=>$ip, 'j'=>$j, 'w'=>$w, 'type'=>2));
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
 	// issue comment
 	public function issueComment($sid,$content,$ip=null){
 		$this->initClient();
 		
 		$res = $this->_client->postOne(array('c'=>$content, 'r'=>$sid, 'ip'=>$ip, 'j'=>0, 'w'=>0, 'type'=>4));
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
 	// replay comment
 	public function replyComment($sid,$content,$ip=null){
 		$this->initClient();
 		
 		$res = $this->_client->postOne(array('c'=>$content, 'r'=>$sid, 'ip'=>$ip, 'j'=>0, 'w'=>0, 'type'=>3));
 		
 		if(!$this->handleError($res)) return false;
 		
 		return true;
 	}
 	// add faorite mention
 	public function addFavorite($sid){
 		$this->initClient();
 		
 		$res = $this->_client->postFavMsg(array('id'=>$sid,'type'=>1));
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}

	//send private message 
	public function sendPrivateMsg($info, $ip=null){
		$this->initClient();
 		$data = array('ip'=>$ip, 'j'=>0, 'w'=>0);
		$data['pic'] = isset($info['pic'])? '@'.$info['pic'] : '';
		$data['f'] = empty($info['pic']) ? 1 : 2;
		$data['c'] = $info['content'];
		$data['n'] = isset($info['name'])?$info['name']:'';
		$data['fid'] = isset($info['fid'])?$info['fid']:'';
 		$res = $this->_client->postOneMail($data);
 		
 		if(!$this->handleError($res)) return false;

 		return true;
	}
 	
 	public function getErrorMessage(){
 		return $this->_error_message;	
 	}
 	
 	public function getErrorCode(){
 		return $this->_error_code;	
 	}
 	
	public function getAdapterKey(){
		return $this->_key;
	}

 	private function handleError($src){
 		
 		if ($src === false || $src === null) return false;
		
		if (isset($src['ret']) && isset($src['msg'])){
			if($src['ret']==0) return true;
			$this->_error_code=$src['errcode'];
			$this->_error_message=$src['msg'];
			return false;
		}
		return true;
 	}
 	
 	private function initClient(){
 		if(!$this->_client){
 			$this->_client = new TencentClient($this->_akey,$this->_skey,
 				$_SESSION[$this->_key.'last_key']['oauth_token'],
 				$_SESSION[$this->_key.'last_key']['oauth_token_secret'],
				$_SESSION[$this->_key.'last_key']['oauth_openid'],
				$this->_debug);
			$this->_client->setLogFile($this->_log_file);
 		}
 	}
 	
 	private function initConvertList(){
 		
		if(!$this->_kv_user){
			$this->_kv_user = array('uid'=>'name','nickname'=>'nick','url'=>'head',
	 			'user_name'=>'name','location'=>'location','avatar_url'=>'head',
				'gender'=>'sex','att_count'=>'idolnum','fan_count'=>'fansnum',
				'mms_count'=>'tweetnum',//weibo count
				'email'=>'email',
				//-------------- shou cang
				'content'=>'text','fid'=>'id','favnum'=>'favnum'
				);
		}

 	}

	private function objectToArray($res){
		if(empty($res)) return $res;
		$rec = array();
		foreach($res as $key => $val){
			if(is_object($val) || is_array($val))
				$rec[$key] = $this->objectToArray($val);
			else
				$rec[$key] = $val;
		}
		return $rec;
	}

	private function parseUserPairs($res){
		$rec = array();
		if(empty($res)) return $rec;
		//$res = json_decode($res);
 		if(!is_object($res) && !is_array($res)) return $res;
		$isTwoDimension = true;
		is_object($res) && $res = $this->objectToArray($res);

		if($res['msg'] == 'ok'){
			if(!isset($res['data']['info'])){
				$isTwoDimension = false;
			}else{
				foreach($res['data']['info'] as $rkey => $rval){
					if(is_array($rval)){
						$rec[$rkey] = $this->parseUserPair($rval);
					}
				}
			}
			if($isTwoDimension === false){
				$rec = $this->parseUserPair($res['data']);
			}
			return $rec;
		}else{
			//$res->errcode
		}
 	}
 	
 	private function parseUserPair($res){
		$rec = array();
 		if(empty($res)) return $rec;
		
 		if(!is_array($res) || !isset($res)) return $rec;
 		//$res = $res['data'];
		
		foreach($this->_kv_user as $key=>$value){
			if($key=='created')
				$rec[$key]=time();
			elseif(array_key_exists($value, $res))
				$rec[$key]=$res[$value];
		}
		return $rec;
 	}

	/**
	 * 获取在sns中关注的人
	 * @param type $sns_id	sns id
	 * @param type $cursor	起始位置
	 * @param type $count	查询记录数
	 * @return type 
	 */
 	public function getAttentions($sns_id, $cursor = 0, $count = 200){
 		$this->initClient();
		try{
			$res = $this->_client->getfans(array('n'=>'', 'start'=>$cursor, 'num'=>$count, 'type'=>1));
			if(!$this->handleError($res)) 
				return array("result"=>0,"error_code"=>$this->getErrorCode(),"error_message"=>$this->getErrorMessage());
		}catch(Exception $e){
			return array("result"=>0,"error_code"=>$e->getCode(),"error_message"=>$e->getMessage());
		}
		
		//组织数据
		$i=0;
		$data = array();
		foreach($res['data']['info'] as $val){
			$data[$i]['sns_id'] = $val['openid'];
			$data[$i]['nick'] = $val['nick'];
			$data[$i]['name'] = $val['name'];
			$data[$i]['avatar'] = !empty($val['head'])?$val['head'].'/50':Doo::conf()->STATIC_URL."images/boy_60x60.jpg";
			$data[$i]['gender'] = $val['sex']==2?2:1;//m男f女
			$data[$i]['area'] = $val['location'];
			$i++;
		}
		
		$hasnext = $res['data']['hasnext']==0?true:false;
		return array("result"=>1,'data'=>$data,'hasnext'=>$hasnext,'next_cursor'=>$res['data']['nextstartpos']);
	}
}
