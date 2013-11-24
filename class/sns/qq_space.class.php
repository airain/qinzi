<?php
/**
 * url http://wiki.opensns.qq.com/wiki/%E3%80%90QQ%E7%99%BB%E5%BD%95%E3%80%91API%E6%96%87%E6%A1%A3
 * PHP SDK for qq.com (using OAuth2)
 * 
 * @author airain <airain2010@hotmail.com>
 */

include_once('OAuthV2.php');


/**
 * 新浪微博 OAuth 认证类(OAuth2)
 *
 * 授权机制说明请大家参考微博开放平台文档：{@link http://open.weibo.com/wiki/Oauth2}
 *
 * @package sae
 * @author Elmer Zhang
 * @version 1.0
 */
class TenTOAuthV2 extends OAuthV2{
	/**
	 * @ignore
	 */
	public $client_id;
	/**
	 * @ignore
	 */
	public $client_secret;
	/**
	 * @ignore
	 */
	public $access_token;
	/**
	 * @ignore
	 */
	public $refresh_token;
	/**
	 * Contains the last HTTP status code returned. 
	 *
	 * @ignore
	 */
	public $http_code;
	/**
	 * Contains the last API call.
	 *
	 * @ignore
	 */
	public $url;
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://graph.qq.com/oauth2.0";
	/**
	 * Set timeout default.
	 *
	 * @ignore
	 
	public $timeout = 30;
	*//**
	 * Set connect timeout.
	 *
	 * @ignore
	 
	public $connecttimeout = 30;
	*//**
	 * Verify SSL Cert.
	 *
	 * @ignore
	
	public $ssl_verifypeer = FALSE;
	 *//**
	 * Respons format.
	 *
	 * @ignore
	 */
	public $format = 'json';
	/**
	 * Decode returned json data.
	 *
	 * @ignore
	 */
	public $decode_json = TRUE;
	/**
	 * Contains the last HTTP headers returned.
	 *
	 * @ignore
	 */
	public $http_info;
	/**
	 * Set the useragnet.
	 *
	 * @ignore
	 */
	public $useragent = 'Sae T OAuth2 v0.1';

	/**
	 * print the debug info
	 *
	 * @ignore
	 */
	public $debug = FALSE;

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	function accessTokenURL()  { return 'https://graph.qq.com/oauth2.0/token'; }
	/**
	 * @ignore
	 */
	function authorizeURL()    { return 'https://graph.qq.com/oauth2.0/authorize'; }

	/**
	 * construct WeiboOAuth object
	 */
	function __construct($client_id, $client_secret, $access_token = NULL, $refresh_token = NULL, $debug=false) {
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->access_token = $access_token;
		$this->refresh_token = $refresh_token;
		$this->debug = $debug;
		parent::__construct();
	}

	/**
	 * authorize接口
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/Oauth2/authorize Oauth2/authorize}
	 *
	 * @param string $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
	 * @param string $response_type 支持的值包括 code 和token 默认值为code
	 * @param string $scope QQ授权api接口.按需调用(get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo)
	 * @param string $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 * @param string $display 授权页面类型 可选范围: 
	 *  - default		默认授权页面		
	 *  - mobile		支持html5的手机		
	 *  - popup			弹窗授权页		
	 *  - wap1.2		wap1.2页面		
	 *  - wap2.0		wap2.0页面		
	 *  - js			js-sdk 专用 授权页面是弹窗，返回结果为js-sdk回掉函数		
	 *  - apponweibo	站内应用专用,站内应用不传display参数,并且response_type为token时,默认使用改display.授权后不会返回access_token，只是输出js刷新站内应用父框架
	 * @return array
	 */
	function getAuthorizeURL( $url, $response_type = 'code', $scope = 'get_user_info,add_share,get_info', $state = NULL, $display = NULL ) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['state'] = empty($state)? md5('codestate'):$state;
		$params['display'] = $display;
		$params['scope'] = $scope;
		return $this->authorizeURL() . "?" . http_build_query($params);
	}

	/**
	 * access_token接口
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/OAuth2/access_token OAuth2/access_token}
	 *
	 * @param string $type 请求的类型,可以为:code, password, token
	 * @param array $keys 其他参数：
	 *  - 当$type为code时： array('code'=>..., 'redirect_uri'=>...)
	 *  - 当$type为password时： array('username'=>..., 'password'=>...)
	 *  - 当$type为token时： array('refresh_token'=>...)
	 * @return array
	 */
	function getAccessToken( $type = 'code', $keys ) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['client_secret'] = $this->client_secret;
		if ( $type === 'token' ) {
			$params['grant_type'] = 'refresh_token';
			$params['refresh_token'] = $keys['refresh_token'];
		} elseif ( $type === 'code' ) {
			$params['grant_type'] = 'authorization_code';
			$params['code'] = $keys['code'];
			$params['redirect_uri'] = $keys['redirect_uri'];
			$params['state'] = isset($keys['state'])?$keys['state']:'';
		} elseif ( $type === 'password' ) {
			$params['grant_type'] = 'password';
			$params['username'] = $keys['username'];
			$params['password'] = $keys['password'];
		} else {
			throw new OAuthException("wrong auth type");
		}

		$response = $this->oAuthRequest($this->accessTokenURL(), 'POST', $params);
		if (strpos($response, "callback") !== false)
        {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);
            if (isset($msg->error))
            {
				$tmp_str = parse_url($keys['redirect_uri']);
				header("location: http://".$tmp_str['host']);
				exit;
               // echo "<h3>error:</h3>" . $msg->error;
               // echo "<h3>msg  :</h3>" . $msg->error_description;
               // exit;
            }
        }
		$token = array();
		parse_str($response, $token);
		if ( is_array($token) && !isset($token['error']) ) {
			$this->access_token = $token['access_token'];	//topken 值
			//$this->expires_in = $token['expires_in'];		//topken 有效时间	
			//$this->access_startime = time(); //topken 开始时间
			$this->refresh_token = isset($token['refresh_token'])?$token['refresh_token']:'';
		} else {
			throw new OAuthException("get access token failed." . $token['error']);
		}
		return $token;
	}

	/**
	 * 解析 signed_request
	 *
	 * @param string $signed_request 应用框架在加载iframe时会通过向Canvas URL post的参数signed_request
	 *
	 * @return array
	 */
	function parseSignedRequest($signed_request) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
		$sig = self::base64decode($encoded_sig) ;
		$data = json_decode(self::base64decode($payload), true);
		if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') return '-1';
		$expected_sig = hash_hmac('sha256', $payload, $this->client_secret, true);
		return ($sig !== $expected_sig)? '-2':$data;
	}

	/**
	 * @ignore
	 */
	function base64decode($str) {
		return base64_decode(strtr($str.str_repeat('=', (4 - strlen($str) % 4)), '-_', '+/'));
	}

	/**
	 * 读取jssdk授权信息，用于和jssdk的同步登录
	 *
	 * @return array 成功返回array('access_token'=>'value', 'refresh_token'=>'value'); 失败返回false
	 */
	function getTokenFromJSSDK() {
		$key = "weibojs_" . $this->client_id;
		if ( isset($_COOKIE[$key]) && $cookie = $_COOKIE[$key] ) {
			parse_str($cookie, $token);
			if ( isset($token['access_token']) && isset($token['refresh_token']) ) {
				$this->access_token = $token['access_token'];
				$this->refresh_token = $token['refresh_token'];
				return $token;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * 从数组中读取access_token和refresh_token
	 * 常用于从Session或Cookie中读取token，或通过Session/Cookie中是否存有token判断登录状态。
	 *
	 * @param array $arr 存有access_token和secret_token的数组
	 * @return array 成功返回array('access_token'=>'value', 'refresh_token'=>'value'); 失败返回false
	 */
	function getTokenFromArray( $arr ) {
		if (isset($arr['access_token']) && $arr['access_token']) {
			$token = array();
			$this->access_token = $token['access_token'] = $arr['access_token'];
			if (isset($arr['refresh_token']) && $arr['refresh_token']) {
				$this->refresh_token = $token['refresh_token'] = $arr['refresh_token'];
			}

			return $token;
		} else {
			return false;
		}
	}

}


/**
 * QQ登录操作类V2
 *
 *
 * @package sae
 * @author airain
 * @version 1.0
 */
class TenToClientV2
{
	private $_request_url = 'https://graph.qq.com/';
	private $_access_token = '';
	private $_appid = '';
	private $_appkey = '';
	private $_openid = null;
	private $_client_id = null;
	private $_format = 'json';

	private $_errormsg = null;
	/**
	 * 构造函数
	 * 
	 * @access public
	 * @param mixed $akey QQ开放平台应用APP KEY
	 * @param mixed $skey QQ开放平台应用APP SECRET
	 * @param mixed $access_token OAuth认证返回的token
	 * @param mixed $refresh_token OAuth认证返回的token secret
	 * @return void
	 */
	function __construct( $akey, $skey, $access_token, $refresh_token = NULL, $debug = false)
	{
		$this->_access_token = $access_token;
		$this->_appid = $akey;
		$this->_appkey = $skey;
		$this->oauth = new TenTOAuthV2( $akey, $skey, $access_token, $refresh_token,$debug );
	}

	//set log file 
	public function setLogFile($log_file = ''){
		$this->oauth->setLogFile($log_file);
	}
	
	//获取用户id
	public function getUserId($topken=''){
        writeLog('getUserId--1','space');
		if($this->_openid == null){
			$url = $this->_request_url.'oauth2.0/me';
			$params['access_token'] = (empty($topken)?$this->_access_token:$topken);
            writeLog('getUserId--1 topken:#'.$topken.'#','space');
            writeLog('getUserId--1 this->_access_token:#'.$this->_access_token.'#','space');
            writeLog('getUserId--1 access_token:#'.$params['access_token'].'#','space');
			$response = $this->oauth->oAuthRequest($url,'GET',$params);
			$msg = '';
			if (strpos($response, "callback") !== false)
			{
				$lpos = strpos($response, "(");
				$rpos = strrpos($response, ")");
				$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
				$msg = json_decode($response);
				
				if(isset($msg->error) && $msg->error){
					$this->_errormsg['errno'] = $msg->error;
					$this->_errormsg['errmsg'] = $msg->error_description;
                    writeLog('getUserId--2','space');
                    writeLog('getUserId--2 error code:'.$msg->error,'space');
                    writeLog('getUserId--2 error message:'.$msg->error_description,'space');
					return false;
				}
				$this->_client_id = $msg->client_id;
				$this->_openid = $msg->openid;
			}else{
                writeLog('getUserId--3','space');
				return false;
			}
		}
		return true;
	}

	public function getErrorMsg(){
		return $this->_errormsg;
	}
	
	//获取微博帐号信息
	public function getUserInfo($name='',$fopenid=''){
		if($this->getUserId()){
			$params = array();
			if($name || $fopenid){				
 				$params = array('name'=>$name,'fopenid'=>$fopenid);
				$url = $this->_request_url.'user/get_other_info';	
			}else{
				$url = $this->_request_url.'user/get_info';				
			}
			$params = $this->getRequestParams($params);
			return $this->oauth->get($url,$params);
		}
		return false;
	}

	/*
	 * 获取用户空间个人信息
	*/
	public function getSpaceUserInfo(){
		$userinfo = array();
		if($this->getUserId()){
			$params = $this->getRequestParams();
			$url = $this->_request_url.'user/get_user_info';
			$userinfo = $this->oauth->get($url,$params);
			$userinfo['uid'] = $this->_openid;
		}
		return $userinfo;
	}

	/**
	 * 同步分享到QQ空间、朋友网、腾讯微博 
	 * url:http://wiki.opensns.qq.com/wiki/%E3%80%90QQ%E7%99%BB%E5%BD%95%E3%80%91add_share
	 * @param array $params
	*/
	public function postMessage(array $params=array()){
		/*
		$params['title'] = $data['title']; //分享内容标题
		$params['url'] = $data['url']; //分享内容链接地址
		$params['comment'] = ''; //评论内容
		$params['summary'] = ''; // 分享内容
		$params['images'] = '';	//分享带图片内容
		$params['source'] = '';	//评论来源(取值说明：1.通过网页 2.通过手机 3.通过软件 4.通过IPHONE 5.通过 IPAD。)
		$params['type'] = '';	//分享内容的类型。4表示网页；5表示视频（type=5时，必须传入playurl）。 
		$params['playurl'] = ''; //长度限制为256字节。仅在type=5的时候有效，表示视频的swf播放地址。
		$params['site'] =''; //分享内容的来源。
		$params['nswb'] = 0; //值为1时，表示分享不默认同步到微博，其他值或者不传此参数表示默认同步到微博。 
		
		$flg = false;
		if(!empty($params['images'])) {
			$params['images'] = "@".$params['images'];
			$flg = true;
		}*/$flg = false;
        writeLog('postMessage--1','space');
		if($this->getUserId()){
			$params = $this->getRequestParams($params);
			$url = $this->_request_url.'share/add_share';
            writeLog('postMessage--url:'.$url,'space');
            foreach($params as $k=>$v)
                writeLog('postMessage--params:'.$k.'='.$v,'space');
            writeLog('postMessage--flg:'.$flg,'space');
			return $this->oauth->post($url,$params,$flg);
		}
		return false;
	}

	/**
	 * 发日志
	*/
	public function sendNote(array $data=array()){
		$params['title'] = $data['t'];
		$params['content'] = $data['c'];
		if($this->getUserId()){
			$params = $this->getRequestParams($params);
			$url = $this->_request_url.'blog/add_one_blog';
			return $this->oauth->post($url,$params);
		}
		return false;
	}

	/**
	 * 发布微博
	*/
	public function issued(array $data=array()){
		$params['clientip'] = $data['ip'];
		$params['content'] = $data['c'];
		$params['jing'] = $data['j'];
		$params['wei'] = $data['w'];
		$params['syncflag'] = $data['sync'];
		if($this->getUserId()){
			$params = $this->getRequestParams($params);
			$url = $this->_request_url.'t/add_t';
			return $this->oauth->post($url,$params);
		}
		return false;
	}

	/**
	 * 发布图片微博
	*/
	public function issuedImg(array $data=array()){
		$params['clientip'] = $data['ip'];
		$params['content'] = $data['c'];
		$params['pic'] = '@'.$data['p'];
		$params['jing'] = $data['j'];
		$params['wei'] = $data['w'];
		$params['syncflag'] = $data['sync'];
		if($this->getUserId()){
			$params = $this->getRequestParams($params);
			$url = $this->_request_url.'t/add_pic_t';
			return $this->oauth->post($url,$params,true);
		}
		return false;
	}

	/**
	 * 删除一条微博
	*/
	public function delIssued($params){
		if($this->getUserId()){
			$params = $this->getRequestParams($params);
			$url = $this->_request_url.'t/del_t';
			return $this->oauth->post($url,$params);
		}
		return false;
	}
	/**
	 * 取消关注
	*/
	public function cancelAttention($params){
		if($this->getUserId()){
			$params = $this->getRequestParams($params);
			$url = $this->_request_url.'relation/del_idol';
			return $this->oauth->post($url,$params);
		}
		return false;
	}

	/**
	 * 添加关注
	*/
	public function addAttention($params){
		if($this->getUserId()){
			$params = $this->getRequestParams($params);
			$url = $this->_request_url.'relation/add_idol';
			return $this->oauth->post($url,$params);
		}
		return false;
	}
	
	/**
	 * 获取用户粉丝
	 * @param string $startindex 请求获取收听列表的起始位置。第一页：0；继续向下翻页：reqnum*（page-1）。 
	 * @param string $reqnum 请求获取的收听个数。取值范围为1-30。
	 * @param string $install 判断获取的是安装了应用的收听好友，还是未安装应用的收听好友。
		0：不考虑该参数；
		1：获取已安装应用的收听好友信息；
		2：获取未安装应用的收听好友信息。 
	*/
	public function getfans($startindex=0,$reqnum=30,$install=0){
		if($this->getUserId()){
			$params = compact('startindex','reqnum','install');
			$params = $this->getRequestParams($params);
			$url = $this->_request_url.'relation/get_idollist';
			return $this->oauth->post($url,$params);
		}
		return false;
	}

	/**
	 * 获取用户关注
	 * @param string $startindex 请求获取收听列表的起始位置。第一页：0；继续向下翻页：reqnum*（page-1）。 
	 * @param string $reqnum 请求获取的收听个数。取值范围为1-30。
	 * @param string $mode 获取听众信息的模式，默认值为0。
		0：旧模式，新添加的听众信息排在前面，最多只能拉取1000个听众的信息。
		1：新模式，可以拉取所有听众的信息，暂时不支持排序。 
	 * @param string $install 判断获取的是安装了应用的收听好友，还是未安装应用的收听好友。
		0：不考虑该参数；
		1：获取已安装应用的收听好友信息；
		2：获取未安装应用的收听好友信息。 
	*/
	public function getAttention($startindex=0,$reqnum=30, $mode=0, $install=0){
		if($this->getUserId()){
			$params = compact('startindex','reqnum','mode','install');
			$params = $this->getRequestParams($params);
			$url = $this->_request_url.'relation/get_idollist';
			return $this->oauth->post($url,$params);
		}
		return false;
	}



	//请求参数
	private function getRequestParams($params=array()){		
		$params['access_token'] = $this->_access_token;
		$params['oauth_consumer_key'] = $this->_client_id;
		$params['openid'] = $this->_openid;
		$params['format'] = $this->_format;
		return $params;
	}
	
	
}
