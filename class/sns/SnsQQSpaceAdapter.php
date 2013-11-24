<?php
/**
 * Sns Gateway module
 *
 * @author airain2010@hotmail.com
 * create_time : 2012/5/22
 *
 * qq api https://graph.qq.com/oauth2.0
 * akey = 100270894
 * skey = c5dbb79ce962694ba411d9a7a24b4229
 * 
 */

require_once('qq_space.class.php');

class SnsQQSpaceAdapter{
	// sina key
	private $_key = 'space';
 	//app key
 	private $_akey = '100270894';
 	//app secret
 	private $_skey = 'c5dbb79ce962694ba411d9a7a24b4229';

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

	public  $_session = null;

	private $_scope = 'get_user_info,add_share,add_t,add_pic_t,del_t,get_repost_list,get_info,get_other_info,get_fanslist,get_idollist,add_idol,del_idol';

 	public function __construct($akey=null,$skey=null, $debug=false){
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
			$this->_auth = new TenTOAuthV2($this->_akey,$this->_skey,null,null,$this->_debug);
			$this->_auth->setLogFile($this->_log_file);
		}
	}
 	
 	// probe login to website
 	public function probeLogin($callback_url){
 		if(empty($callback_url)) return null;
 		
		$this->initServer();
		//$callback_url = preg_replace('@&key=.*@','',$callback_url);
		$url = $this->_auth->getAuthorizeURL('http://'.$_SERVER['HTTP_HOST'].$callback_url,'code', $this->_scope);
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
		$_SESSION[$this->_key.'last_key']['oauth_expires'] = intval($token['expires_in']);
		$_SESSION[$this->_key.'last_key']['oauth_startime'] = time();
		return true;
 	}
	
	public function isLogin(){
 		if(isset($_SESSION[$this->_key.'last_key']) || isset($this->_session[$this->_key.'last_key'])) 
			return true;
		return false;
	}

	public function getOrgId(){
		return $this->_org_weibo_id;
	}

 	// get my information
 	public function getMyInfor(){
 		$this->initClient();

		$res = $this->_client->getSpaceUserInfo(null);
		
		if(!$this->handleError($res)) return false;
		return $this->parseUserPair($res);
 	}
 	
 	//get user attentions ok
 	public function getAttentionsByUser($uid_name,$start=0,$limit=20){
 		$this->initClient();
 		
 		$res = $this->_client->getAttention($start,$limit);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseUserPairs($res);
 	}
 	
 	//get user fancy ok
 	public function getFanciesByUser($uid_name,$start=0,$limit=20){
 		$this->initClient();
 		
 		$res = $this->_client->getfans($start,$limit);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseUserPairs($res);
 	}
 	/* get user information ok
	 * 可选，name和fopenid至少选一个，若同时存在则以name值为主。 
	 * @param string $name 要取消收听的用户的账户名。
	 * @param string $fopenid 要取消收听的用户的openid。
	*/
 	public function getUserInfor($name='',$fopenid=''){
 		$this->initClient();
 		
 		$res = $this->_client->getUserInfo($name,$fopenid);
 		
 		if(!$this->handleError($res)) return false;
			
 		return $this->parseUserPairs($res);
 	}

 	/* add attention ok
	 * 可选，names和fopenids至少选一个，若同时存在则以name值为主。 
	 * @param string $names 要收听的用户的账户名列表。多个账户名之间用“,”隔开，例如：abc,bcde,cde。最多30个。
		如果$fopenids 不为空 
		$names 为 要收听的用户的openid列表。多个openid之间用“_”隔开，例如：B624064BA065E01CB73F835017FE96FA_B624064BA065E01CB73F835017FE96FB。最多30个。
	 * @param string $fopenids
	*/
 	public function addAttention($names='', $fopenids=''){
 		$this->initClient();
		if($fopenids){
			$p['name'] = '';
			$p['fopenids'] = $names;
		}else{
			$p['name'] = $names;
			$p['fopenids'] = '';
		}
 		$res = $this->_client->addAttention($p);
 		
 		if(!$this->handleError($res)) return false;
			
 		return true;
 	}
 	/* cancel attention ok
	 * 可选，name和fopenid至少选一个，若同时存在则以name值为主。 
	 * @param string $name 要取消收听的用户的账户名。
		如果$fopenids 不为空 
		$names 为 要收听的用户的openid列表。多个openid之间用“_”隔开，例如：B624064BA065E01CB73F835017FE96FA_B624064BA065E01CB73F835017FE96FB。最多30个。
	 * @param string $fopenids
	*/
 	public function cancelAttention($name='', $fopenid=''){
 		$this->initClient();
 		if($fopenids){
			$p['fopenids'] = $names;
		}else{
			$p['name'] = $names;
		}
 		$res = $this->_client->cancelAttention($p);
 		
 		if(!$this->handleError($res)) return false;
			
 		return true;
 	}/*
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
 	}*/
 	// delete mention by sid ok
 	public function deleteMentionBySid($sid){
 		$this->initClient();
 		
 		$res = $this->_client->delIssued(array('id'=>$sid));
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
 	// issue mention ok
 	public function issueMention($content, $ip='', $j='0', $w='0'){
 		$this->initClient();
 		
 		$res = $this->_client->issued(array('c'=>$content, 'ip'=>$ip, 'j'=>$j, 'w'=>$w, 'sync'=>0));
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}

	//send note
	public function sendNote($title='',$content='', $imgurl=''){
		$this->initClient();
 		
 		$res = $this->_client->sendNote(array('c'=>$content, 't'=>$title,'p'=>$imgurl));
 		
 		if(!$this->handleError($res)) return false;

 		return true;
	}
 	
 	//issue have image mention ok
 	public function issueImageMention($content, $img_url, $ip='', $j='0', $w='0'){
 		$this->initClient();
 		
 		$res = $this->_client->issuedImg(array('c'=>$content, 'p'=>$img_url, 'ip'=>$ip, 'j'=>$j, 'w'=>$w, 'sync'=>0));
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
 	/* forward mention illustrated： $title and $url no empty  ok
		@param string $title 分享内容标题
		@param string $url 分享内容链接地址
		@param string $comment 评论内容
		@param string $summary 分享内容
		@param string $images 分享带图片内容 
		@param string $source	评论来源(取值说明：1.通过网页 2.通过手机 3.通过软件 4.通过IPHONE 5.通过 IPAD。)
		@param string $type 分享内容的类型。4表示网页；5表示视频（type=5时，必须传入playurl）。 
		@param string $palyurl 长度限制为256字节。仅在type=5的时候有效，表示视频的swf播放地址。
		@param string $site 分享内容的来源。
		@param string $nswb 值为1时，表示分享不默认同步到微博，其他值或者不传此参数表示默认同步到微博。 
	*/
 	public function shareSpace($title='', $summary='', $url='',$images='',$site='', $comment='',$source=5,$type=4,$playurl='',$nswb=0,$fromurl=''){
 		$this->initClient();
		if(empty($title) || empty($url)) return false;
        if(empty($fromurl)) $fromurl = $url;
 		$params = compact('title','summary','url','images','site','comment','source','type','playurl','nswb','fromurl');
 		$res = $this->_client->postMessage($params);
 		
 		if(!$this->handleError($res)) return false;

 		return true;
 	}
	/*	
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
 	*/
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
            writeLog('handleError--error_code'.$this->_error_code,'space');
            writeLog('handleError--error_message'.$this->_error_message,'space');
			return false;
		}
		return true;
 	}
 	
 	private function initClient(){
 		if(!$this->_client){
            if(isset($this->_session[$this->_key.'last_key']['oauth_token'])){
                writeLog('initClient--2','space');
                $oauth_token = $this->_session[$this->_key.'last_key']['oauth_token'];
                $oauth_token_secret = $this->_session[$this->_key.'last_key']['oauth_token_secret'];
            }else{
                writeLog('initClient--1','space');
                $oauth_token = $_SESSION[$this->_key.'last_key']['oauth_token'];
                $oauth_token_secret = $_SESSION[$this->_key.'last_key']['oauth_token_secret'];
            }
            writeLog('initClient--oauth_token:'.$oauth_token,'space');
 			$this->_client = new TenToClientV2($this->_akey,$this->_skey,$oauth_token,$oauth_token_secret,$this->_debug);
			$this->_client->setLogFile($this->_log_file);
 		}
 	}
 	
 	private function initConvertList(){
 		
		if(!$this->_kv_user){
			$this->_kv_user = array('uid'=>'uid','nickname'=>'nickname','url'=>'figureurl_2',
	 			'avatar_url'=>'figureurl_2','gender'=>'gender'
				);
		}
		/*
		if(!$this->_kv_line){
 			$this->_kv_line = array('sid'=>'id','mid'=>'id','uid'=>'name','nickname'=>'nick','image'=>'image', 'c_count'=>'count', 'content'=>'text', 'user_name'=>'name','location'=>'location','avatar_url'=>'head', 'derive_from'=>'source','forward'=>'from',
				
				'created'=>'created_at');
		}

		if(!isset($this->_kv_msg)) {
			$this->_kv_msg = array('n'=>'hasnext', 't'=>'timestamp', 'total'=>'totalnum', 'user'=>'user', 'data'=>'info');
		}*/
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
 		if(!is_array($res)) return $res;
		$isTwoDimension = true;
		//$res = $this->objectToArray($res);
		if($res['data']){
			foreach($res['data'] as $rkey => $rval){
				if(is_array($rval)){
					$rec[$rkey] = $this->parseUserPair($rval);
				}else{
					if($tmp_key = array_search($rkey, $this->_kv_user)){
						$rec[$tmp_key] = $rval;
					}
				}
			}
		}
		return $rec;
 	}
 	
 	private function parseUserPair($res){
		$rec = array();
 		if(empty($res)) return $rec;
		
 		if(!is_array($res) || !isset($res) || $res['ret']) return $rec;
 		//$res = $res['data'];
		
		foreach($this->_kv_user as $key=>$value){
			if(array_key_exists($value, $res))
				$rec[$key]=$res[$value];
		}
		return $rec;
 	}
	/*
	private function parseLinePairs($res){
		$rec = array();
		if(empty($res)) return $rec;
		$res = json_decode($res);
 		if(!is_object($res)) return $res;
 		
		$isTwoDimension = true;
		$res = $this->objectToArray($res);
		
		if($res['msg'] == 'ok'){
			if(!isset($res['data']['info'])){
				$isTwoDimension = false;
			}else{
				foreach($res['data']['info'] as $rkey => $rval){
					if(is_array($rval)){
						$rec[$rkey] = $this->parseLinePair($rval);
					}
				}
			}

			if($isTwoDimension === false){
				$rec = $this->parseLinePair($res['data']);
			}
			return $rec;
		}else{
			//$res->errcode
		}
		return $res;
	}
 	
 	private function parseLinePair($res){
 		$rec = array();
 		if(empty($res)) return $rec;
 		
 		if(!is_array($res)) return $res;
 		
		foreach($res as $key => $value){
			if($key=='source')
				$rec[$key]=$this->parseLinePair($value);
			elseif(in_array($key, $this->_kv_line)){
				$rec[$key]=$value;
			}
		}
		return $rec;
 	}*/
	
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
			$res = $this->_client->getfans($cursor, $count);
			if(!$this->handleError($res)) 
				return array("result"=>0,"error_code"=>$this->getErrorCode(),"error_message"=>$this->getErrorMessage());
		}catch(Exception $e){
			return array("result"=>0,"error_code"=>$e->getCode(),"error_message"=>$e->getMessage());
		}
		
		//组织数据
		$i=0;
		$data = array();
		$hasnext = false;
		if(!empty($res['data'])){
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
		}
		
		return array("result"=>1,'data'=>$data,'hasnext'=>$hasnext,'next_cursor'=>$res['data']['nextstartpos']);
	}
}
