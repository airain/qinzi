<?php
/**
 * Sns Gateway module
 *
 * @author hesen2006cn@126.com
 * create_time : 2011-06-23
 *
 * sina api http://open.weibo.com/wiki/index.php/API%E6%96%87%E6%A1%A3
 * akey = 3643515601
 * skey = 0df0f52097098cc728c59fe5ba0d63b6
 */

//require_once('oauth.class.php');
require_once('sina_weibo.class.php');
 
class SnsSinaAdapter{
	// sina key
	private $_key = 'sina';
 	//app key
 	private $_akey = '2696040561';
 	//app secret
 	private $_skey = '1eebb5ea03730ac599aa236d3fcb4acb';

	private $_org_weibo_id = '1815976901';
 	 	
 	private $_auth = null;
 	
 	private $_client = null;
 	
 	private $_error_code = null;
 	
 	private $_error_message = null;

 	private $_kv_user = null;
		
	private $_kv_line = null;

	private $_log_file = null;

	private  $_debug = false;
			
 	public function __construct($akey=null,$skey=null,$debug=false){
		if($akey)
 			$this->_akey = $akey;
		if($skey)
 			$this->_skey = $skey;
 		$this->_debug = $debug;
 		$this->initConvertList();
 		
 		if(!isset($_SESSION)) session_start();
 	}

	//set log file 
	public function setLogFile($log_file = ''){
		$this->_log_file = $log_file;
	}

	private function initServer(){
		if($this->_auth == null){
			$this->_auth = new SaeTOAuthV2($this->_akey,$this->_skey,null,null,$this->_debug);
			$this->_auth->setLogFile($this->_log_file);
		}
	}
 	
 	// probe login to website
 	public function probelogin($callback_url){
 		if(empty($callback_url)) return null;
 		$this->initServer();
 		
		//$callback_url = preg_replace('@&key=[^&]*@','',$callback_url);
		$url = $this->_auth->getAuthorizeURL('http://'.$_SERVER['HTTP_HOST'].$callback_url);
		//echo $url;exit;
		return $url;
 	}
 	// login call back
 	public function probeCallBack(){
 		$this->initServer();
		$keys['code'] = $_REQUEST['code'];
		$keys['redirect_uri'] = preg_replace('@&code=.*@','', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		try{
			$token = $this->_auth->getAccessToken('code', $keys);
		}catch(Exception $e){
			return false;
		}
		
		if(empty($token)) return false;

		$_SESSION[$this->_key.'last_key']['oauth_token'] = $token['access_token'];
		$_SESSION[$this->_key.'last_key']['oauth_token_secret'] = isset($token['refresh_token'])?$token['refresh_token']:'';
		//$_SESSION[$this->_key.'last_key']['uid'] = $token['uid'];
		$_SESSION[$this->_key.'last_key']['oauth_expires'] = $token['expires_in'];		
		$_SESSION[$this->_key.'last_key']['oauth_startime'] = time();
		return true;
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
		//$ms  = $this->_client->home_timeline(); // done
		$uid_get = $this->_client->get_uid(); 
		//echo '<br>',decbin(2545272930),'<br>';
		//echo '<br>',decbin($uid_get['uid']),'<br>';
		//echo '<br>',bindec(decbin($uid_get['uid'])),'<br>';
		$uid = $this->numToPlus($uid_get['uid']);//$_SESSION[$this->_key.'last_key']['uid'];
		$res = $this->_client->show_user_by_id($uid);//根据ID获取用户等基本信息

		if(!$this->handleError($res)) return false;
			
		return $this->parseUserPairs($res);
 	}
 	
 	//获取当前登录用户及其所关注用户的最新微博消息
 	public function getMyTimeLine(){
 		$this->initClient();
 		
 		$res = $this->_client->home_timeline();
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseLinePairs($res);
 	}
 	
 	//获取最新的公共微博消息 
 	public function getPublicTimeLine(){
 		$this->initClient();

 		$res = $this->_client->public_timeline();
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseLinePairs($res);
 	}
 	// 获取用户发布的微博消息列表 
 	public function getUserTimeLine($uid_name,$page=1,$limit=20){
 		$this->initClient();

 		$res = $this->_client->user_timeline($page,$limit,$uid_name);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseLinePairs($res);
 	}
 	//获取@当前用户的微博列表 
 	public function getMyMentions($page=1,$limit=20){
 		$this->initClient();
 		
 		$res = $this->_client->mentions($page,$limit);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseLinePairs($res);
 	}
 	//获取当前用户发出的评论
 	public function getMyComments($page=1,$limit=20){
 		$this->initClient();
 		
 		$res = $this->_client->comments_by_me($page,$limit);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseLinePairs($res);
 	}
 	//获取当前用户发送及收到的评论列表
 	public function getMyCommentsTimeline($page=1,$limit=20){
 		$this->initClient();
 		
 		$res = $this->_client->comments_timeline($page,$limit);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseLinePairs($res);
 	}
 	
 	//批量获取一组微博的评论数及转发数
 	public function getCountsBySid($sids){
 		$this->initClient();
 		
 		$res = $this->_client->get_comments_by_sid($sids,$page,$limit);
 		
 		if(!$this->handleError($res)) return false;
		
		$rec = array();
		foreach($res as $value){
			$rec[$value['id']]['comment_count']=$value['comments'];
			$rec[$value['id']]['forward_count']=$value['rt'];
		}
		
 		return $rec;
 	}
 	
 	//get user attentions
 	public function getAttentionsByUser($uid_name,$start=0,$limit=20){
 		$this->initClient();
 		
 		$res = $this->_client->friends($start,$limit,$uid_name);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseUserPairs($res);
 	}
 	
 	//get user fancy
 	public function getFanciesByUser($uid_name,$start=0,$limit=20){
 		$this->initClient();
 		
 		$res = $this->_client->followers($start,$limit,$uid_name);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseUserPairs($res);
 	}
 	//get user information
 	public function getUserInfor($uid_name){
 		$this->initClient();
 		
 		$res = $this->_client->show_user($uid_name);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseUserPairs($res);
 	}
 	// add attention ok
 	public function addAttention($uid_name){
 		$this->initClient();
 		if(is_numeric($uid_name))
 			$res = $this->_client->follow_by_id($uid_name);
		else
			$res = $this->_client->follow_by_name($uid_name);
 		
 		if(!$this->handleError($res)) return false;
			
 		return true;
 	}
 	//cancel attention
 	public function cancelAttention($uid_name){
 		$this->initClient();
 		
 		$res = $this->_client->unfollow($uid_name);
 		
 		if(!$this->handleError($res)) return false;
			
 		return true;
 	}
 	// is attention to user
 	public function isAttention($uid_name){
 		$this->initClient();
 		
 		$res = $this->_client->is_followed($uid_name);
 		
		if(!$this->handleError($res)) return false;
			
 		return true;
 	}
 	// get my favorites
 	public function getMyFavorites($page=0){
 		$this->initClient();
 		
 		$res = $this->_client->get_favorites($page);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseUserPairs($res);
 	}
 	// delete mention by sid
 	public function deleteMentionBySid($sid){
 		$this->initClient();
 		
 		$res = $this->_client->delete($sid);
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
 	// issue mention
 	public function issueMention($src){
 		$this->initClient();
 		
 		$res = $this->_client->update($src);
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
 	
 	//issue have image mention
 	public function issueImageMention($src,$img_url){
 		$this->initClient();
 		
 		$res = $this->_client->upload($src,$img_url);
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
 	// forward mention
 	public function forwardMention($sid,$src=null){
 		$this->initClient();
 		
 		$res = $this->_client->repost($sid,$src);
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
 	// issue comment
 	public function issueComment($sid,$src,$cid=null){
 		$this->initClient();
 		
 		$res = $this->_client->send_comment($sid,$src,$cid);
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
 	// replay comment
 	public function replyComment($sid,$src,$cid=null){
 		$this->initClient();
 		
 		$res = $this->_client->reply($sid,$src,$cid);
 		
 		if(!$this->handleError($res)) return false;
 		
 		return true;
 	}
 	// add faorite mention
 	public function addFavorite($sid){
 		$this->initClient();
 		
 		$res = $this->_client->add_to_favorites($sid);
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
	//send private message 
	public function sendPrivateMsg($uids, $text='', $id=null){
		$this->initClient();
 		
 		$res = $this->_client->send_dm_by_id($uids,$text,$id);
 		
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
		
		if (isset($src['error_code']) && isset($src['error'])){
			$this->_error_code=$src['error_code'];
			$this->_error_message=$src['error'];
			return false;
		}
		return true;
 	}
 	
 	private function initClient(){
 		if(!$this->_client){
 			$this->_client = new SaeTClientV2($this->_akey,$this->_skey,
 				$_SESSION[$this->_key.'last_key']['oauth_token'],
 				$_SESSION[$this->_key.'last_key']['oauth_token_secret'],
				$this->_debug);
			$this->_client->setLogFile($this->_log_file);
 		}
 	}
 	
 	private function initConvertList(){
 		if(!$this->_kv_user){
 			$this->_kv_user = array('uid'=>'idstr','nickname'=>'screen_name','url'=>'url',
	 			'user_name'=>'name','location'=>'location','avatar_url'=>'avatar_large',
				'gender'=>'gender','att_count'=>'friends_count','fan_count'=>'followers_count',
				'mms_count'=>'statuses_count',//weibo count
				'email'=>'id',
				'created'=>'created_at');
		}
		
		if(!$this->_kv_line){
 			$this->_kv_line = array('sid'=>'id','content'=>'text','mid'=>'mid','user'=>'user',
	 			'derive_from'=>'source','forward'=>'retweeted_status');
		}
 	}

	private function parseUserPairs($res){
		$rec = array();
		if(empty($res)) return $rec;
		if(!is_array($res)) return $res;
		$isTwoDimension = true;

		foreach($res as $rkey => $rval){
			if(is_array($rval)){
				$rec[$rkey] = $this->parseUserPair($rval);
			}else{
				$isTwoDimension = false;
				break;
			}
		}

		if($isTwoDimension === false){
			$rec = $this->parseUserPair($res);
		}

		return $rec;
	}
 	
 	private function parseUserPair($res){
		$rec = array();
 		if(empty($res)) return $rec;
 		
 		if(!is_array($res)) return $res;
 		
		foreach($this->_kv_user as $key=>$value){
			if($key=='created'){
				if(isset($res[$value]))
					$rec[$key]=strtotime($res[$value]);
			}elseif(array_key_exists($value, $res)){
				if(is_int($res[$value])){
					$res[$value] = $this->numToPlus($res[$value]);
				}
				$rec[$key]=$res[$value];
			}
		}
		return $rec;
 	}

	private function parseLinePairs($res){
		$rec = array();
		if(empty($res)) return $rec;
		if(!is_array($res)) return $res;
		$isTwoDimension = true;

		foreach($res as $rkey => $rval){
			if(is_array($rval)){
				$rec[$rkey] = $this->parseLinePair($rval);
			}else{
				$isTwoDimension = false;
				break;
			}
		}

		if($isTwoDimension === false){
			$rec = $this->parseLinePair($res);
		}

		return $rec;
	}
 	
 	private function parseLinePair($res){
 		$rec = array();
 		if(empty($res)) return $rec;
 		
 		if(!is_array($res)) return $res;
 		
		foreach($this->_kv_line as $key=>$value){
			if($key=='created'){
				if(isset($res[$value]))
					$rec[$key]=strtotime($res[$value]);
			}
			if($key=='user'){
				if(isset($res[$value]))
					$rec[$key]=$this->parseUserPair($res[$value]);
			}
			if($key=='forward'){
				if(isset($res[$value]))
					$rec[$key]=$this->parseLinePair($res[$value]);
			}elseif(array_key_exists($value, $res))
				$rec[$key]=$res[$value];
		}
		return $rec;
 	}
	
	/**
	 * 获取在sns中关注的人
	 * @param string $sns_id	sns id
	 * @param integer $cursor	起始位置
	 * @param integer $count	查询记录数
	 * @return array("result"=>1,'data'=>$data,'hasnext'=>$hasnext,'next_cursor'=>$res['next_cursor']) 
	 */
 	public function getAttentions($sns_id, $cursor = 0, $count = 200){
 		$this->initClient();
		try{
			$res = $this->_client->friends_by_id( $sns_id, $cursor, $count);
			if(!$this->handleError($res)) 
				return array("result"=>0,"error_code"=>$this->getErrorCode(),"error_message"=>$this->getErrorMessage());
		}catch(Exception $e){
			return array("result"=>0,"error_code"=>$e->getCode(),"error_message"=>$e->getMessage());
		}
		
		//组织数据
		$i=0;
		$data = array();
		foreach($res['users'] as $val){
			$data[$i]['sns_id'] = $this->numToPlus($val['id']);
			$data[$i]['nick'] = $val['name'];
			$data[$i]['name'] = $val['name'];
			$data[$i]['avatar'] = $val['profile_image_url'];
			$data[$i]['gender'] = $val['gender']=="f"?2:1;//m男f女
			$data[$i]['area'] = $val['location'];
			$i++;
		}
		
		$hasnext = $res['next_cursor']>0?true:false;
		return array("result"=>1,'data'=>$data,'hasnext'=>$hasnext,'next_cursor'=>$res['next_cursor']);
	}

	//第三方uid 负数转正数
	private function numToPlus($num){
		return sprintf('%u',getUint($num));
		//return bindec(decbin($num));
	}
}
