<?php
/**
 * 腾讯微博开放平台2.0 鉴权类
 * @date 2012/6/27 
 * @author lhj
 */
 defined('MB_RETURN_FORMAT') || define('MB_RETURN_FORMAT','json');
include_once('OAuthV2.php');
 class TencentAuth extends OAuthV2{
	protected  $client_id = '';
    protected  $client_secret = ''; 
	protected  $_key = "qq";
	protected  $access_token = '';
	protected  $refresh_token = '';
	protected  $openid = '';

	public  $host = 'https://open.t.qq.com/';
	protected  $format = 'json';
	protected  $useragent = 'PHP-SDK OAuth2.0';
	protected  $ssl_verifypeer = FALSE; 
	/**
	 * boundary of multipart
	 * @ignore
	 */
	protected $decode_json = TRUE;
	/**
	 * print the debug info
	 *
	 * @ignore
	 */
	protected $debug = FALSE;

    function accessTokenURL()  { return $this->host.'cgi-bin/oauth2/access_token'; } 
    function authorizeURL()    { return  $this->host.'cgi-bin/oauth2/authorize'; } 

	function lastStatusCode() { return $this->http_status; } 

    function __construct($client_id, $client_secret, $access_token = NULL, $refresh_token = NULL, $openid=NULL, $debug = false) {
        $this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->access_token = $access_token;
		$this->refresh_token = $refresh_token;
		$this->openid = $openid;
		$this->debug = $debug;
		parent::__construct();
	}

    /**
     * 获取授权URL
     * @param $redirect_uri 授权成功后的回调地址，即第三方应用的url
     * @param $response_type 授权类型，为code
     * @param $wap 用于指定手机授权页的版本，默认PC，值为1时跳到wap1.0的授权页，为2时同理
     * @return string
     */
    public function getAuthorizeURL($redirect_uri, $response_type = 'code', $wap = false)
    {
        $params = array(
            'client_id' => $this->client_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => $response_type,
            'type' => $wap,
			'with_offical_account'=>1
        );
        return $this->authorizeURL().'?'.http_build_query($params);
    }

   /**
	 * access_token接口
	 *
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
		} elseif ( $type === 'password' ) {
			$params['grant_type'] = 'password';
			$params['username'] = $keys['username'];
			$params['password'] = $keys['password'];
		} else {
			throw new OAuthException("wrong auth type");
		}

		$response = $this->oAuthRequest($this->accessTokenURL(), 'POST', $params);
		parse_str($response,$token);
		if ( is_array($token) && !isset($token['error']) ) {
			$this->access_token = $token['access_token'];
			$this->refresh_token = isset($token['refresh_token'])?$token['refresh_token']:'';
		} else {
			throw new OAuthException("get access token failed." . $token['error']);
		}
		return $token;
	}

	
	//获取公用参数
	public function getOauth(){
		$params = array();
		$params['oauth_consumer_key'] = $this->client_id;
		$params['access_token'] = $this->access_token;
		$params['openid'] = $this->openid;
		$params['clientip'] = $this->getClientIp();
		$params['oauth_version'] = '2.a';
		$params['scope'] = 'all';
		$params['appfrom'] = 'php-sdk2.0beta';
		$params['seqid'] = time();
		$params['serverip'] = $_SERVER['SERVER_ADDR'];
		return $params;
	}

	function jsonDecode($response, $assoc=true)	{
		//echo $response;
		$response = preg_replace('/[^\x20-\xff]*/', "", $response);	
		$jsonArr = json_decode($response, $assoc);
		if(!is_array($jsonArr))
		{
			throw new Exception('格式错误!');
		}
		$ret = $jsonArr["ret"];
		$msg = $jsonArr["msg"];
		/**
		 *Ret=0 成功返回
		 *Ret=1 参数错误
		 *Ret=2 频率受限
		 *Ret=3 鉴权失败
		 *Ret=4 服务器内部错误
		 */
		switch ($ret) {
			case 0:
				return $jsonArr;;
				break;
			case 1:
				throw new OAuthException('参数错误!');
				break;
			case 2:
				throw new OAuthException('频率受限!');
				break;
			case 3:
				throw new OAuthException('鉴权失败!');
				break;
			default:
				$errcode = $jsonArr["errcode"];
				if(isset($errcode))			//统一提示发表失败
				{
					throw new OAuthException("发表失败");
					break;
					//require_once MB_COMM_DIR.'/api_errcode.class.php';
					//$msg = ApiErrCode::getMsg($errcode);
				}
				throw new OAuthException('服务器内部错误!');
				break;
		}
	}
	
    /** 
     * 重新封装的get请求. 
     * @return mixed 
     */ 
    function gets($url='', array $parameters=array()) { 
		$oauthor = $this->getOauth();
		$parameters = array_merge($oauthor,$parameters);
		$response = parent::get($url, $parameters);
        return $response;  
	}

	 /** 
     * 重新封装的post请求. 
     * @return mixed 
     */ 
    function posts($url='', array $parameters = array() , $multi = false) {
		$oauthor = $this->getOauth();
		$parameters = array_merge($oauthor,$parameters);
        $response = parent::post($url, $parameters, $multi);
        return $response;
	}
}


class TencentClient
{
    /** 
     * 构造函数 
     *  
     * @access public 
     * @param mixed $wbakey 应用APP KEY 
     * @param mixed $wbskey 应用APP SECRET 
     * @param mixed $accecss_token OAuth认证返回的token 
     * @param mixed $accecss_token_secret OAuth认证返回的token secret 
     * @return void 
	 */
	public $host = 'https://open.t.qq.com/';
    function __construct( $wbakey , $wbskey , $accecss_token , $accecss_token_secret, $openid=null, $debug=false) 
	{
        $this->oauth = new TencentAuth( $wbakey , $wbskey , $accecss_token , $accecss_token_secret, $openid, $debug); 
		$this->host = $this->oauth->host;
	}
	//set log file 
	public function setLogFile($log_file = ''){
		$this->oauth->setLogFile($log_file);
	}

	/******************
	*发表一条消息
	*@c: 微博内容
	*@ip: 用户IP(以分析用户所在地)
	*@j: 经度（可以填空）
	*@w: 纬度（可以填空）
	*@p: 图片
	*@f: 微博同步到空间分享标记（可选，0-同步，1-不同步，默认为0）
	*@r: 父id
	*@u: Url:音乐地址
	*@tit Title:音乐名
	*@a Author:演唱者
	*@type: 1 发表 2 转播 3 回复 4 点评 5 发音乐微博 6 发视频微博
	**********************/
	public function postOne($p){
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'content' => $p['c'],
			'clientip' => $p['ip'],
			'jing' => $p['j'],
			'wei' => $p['w'],
			'syncflag'=>$p['f']
		);
		switch($p['type']){
			case 2:
				$url = $this->host.'api/t/re_add';
				$params['reid'] = $p['r'];
				return $this->oauth->posts($url,$params); 
				break;
			case 3:
				$url = $this->host.'api/t/reply';
				$params['reid'] = $p['r'];
				return $this->oauth->posts($url,$params); 
				break;
			case 4:
				$url = $this->host.'api/t/comment';
				$params['reid'] = $p['r'];
				return $this->oauth->posts($url,$params); 
				break;
			case 5:
				$url = $this->host.'api/t/add_music';
				$params['url'] = $p['u'];
				$params['title'] = $p['tit'];
				$params['author'] = $p['a'];
				return $this->oauth->posts($url,$params); 
				break;
			case 6:
				$url = $this->host.'api/t/add_video';
				$params['url'] = $p['u'];
				return $this->oauth->posts($url,$params); 
				break;
				
			default:
				if(!empty($p['p'])){
					$url = $this->host.'api/t/add_pic';
					$params['pic'] = '@'.$p['p'];
					return $this->oauth->posts($url,$params,array('pic'=>$params['pic'])); 
				}else{
					$url = $this->host.'api/t/add';
					return $this->oauth->posts($url,$params); 
				}	
			break;			
		}	
	}

	/******************
	*获取当前用户的信息
	*@n:用户名 空表示本人
	**********************/
	public function getUserInfo($p=false){
		if(!$p || !$p['n']){
			$url = $this->host.'api/user/info';
			$params = array(
				'format' => MB_RETURN_FORMAT
			);
		}else{
			$url = $this->host.'api/user/other_info';
			$params = array(
				'format' => MB_RETURN_FORMAT,
				'name' => $p['n']
			);
		}			
	 	return $this->oauth->gets($url,$params); 	
	}

	/******************
	*获取听众列表/偶像列表
	*@num: 请求个数(1-30)
	*@start: 起始位置
	*@n:用户名 空表示本人
	*@type: 0 听众 1 偶像
	**********************/
	public function getfans($p){
		try{
			if($p['n']  == ''){
				$p['type']?$url = $this->host.'api/friends/idollist':$url = $this->host.'api/friends/fanslist';
			}else{
				$p['type']?$url = $this->host.'api/friends/user_idollist':$url = $this->host.'api/friends/user_fanslist';
			}
			$params = array(
				'format' => MB_RETURN_FORMAT,
				'name' => $p['n'],
				'reqnum' => $p['num'],
				'startindex' => $p['start']
			);
		 	return $this->oauth->gets($url,$params);
		} catch(OAuthException $e) {
			$ret = array("ret"=>0, "msg"=>"ok"
					, "data"=>array("timestamp"=>0, "hasnext"=>1, "info"=>array()));
			return $ret;
		}
	}

	/******************
	*收听/取消收听某人
	*@n: 用户名
	*@type: 0 取消收听,1 收听 ,2 特别收听 3 取消特别收听 4 加入黑名单 5 从黑名单中删除
	**********************/	
	public function setMyidol($p){
		switch($p['type']){
			case 0:
				$url = $this->host.'api/friends/del';
				break;
			case 1:
				$url = $this->host.'api/friends/add';
				break;
			case 2:
				$url = $this->host.'api/friends/addspecail';
				break;
			case 3:
				$url = $this->host.'api/friends/delspecial';
				break;
			case 4:
				$url = $this->host.'api/friends/addblacklist';
				break;
			case 5:
				$url = $this->host.'api/friends/delblacklist';
				break;
		}
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'name' => $p['n'],
			'fopenids' => $p['ids']
		);
	 	return $this->oauth->posts($url,$params);
	}
	
	/******************
	*检测是否我粉丝或偶像
	*@n: 其他人的帐户名列表（最多30个,逗号分隔）
	*@flag: 0 检测粉丝，1检测偶像
	**********************/	
	public function checkFriend($p){
		$url = $this->host.'api/friends/check';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'names' => $p['n'],
			'flag' => $p['type']
		);
		return $this->oauth->gets($url,$params);
	}

	/******************
	*发私信
	*@c: 微博内容
	*@ip: 用户IP(以分析用户所在地)
	*@j: 经度（可以填空）
	*@w: 纬度（可以填空）
	*@n: 接收方微博帐号
	*@fid:接收方openid (n or fid 必选其一)
	*@pic:文件域表单名。本字段不要放在签名的参数中，不然请求时会出现签名错误，图片大小限制在2M。
	*@f:私信类型标识，1-普通私信，2-带图私信	
	**********************/
	public function postOneMail($p){
		$url = $this->host.'api/private/add';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'content' => $p['c'],
			'clientip' => $p['ip'],
			'jing' => $p['j'],
			'wei' => $p['w'],
			'name' => $p['n'],
			'fopenid'=> $p['fid'],
			'pic'=>$p['pic'],
			'contentflag'=> $p['f']
			);
		
		return $this->oauth->posts($url,$params, $p['f'] == 2); 
	}
	
	/******************
	*删除一封私信
	*@id: 微博ID
	**********************/
	public function delOneMail($p){
		$url = $this->host.'api/private/del';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'id' => $p['id']
		);
	 	return $this->oauth->posts($url,$params); 
	}
	
	/******************
	*私信收件箱和发件箱
	*@f 分页标识（0：第一页，1：向下翻页，2向上翻页）
	*@t: 本页起始时间（第一页 0，继续：根据返回记录时间决定）
	*@n: 每次请求记录的条数（1-20条）
	*@type : 0 发件箱 1 收件箱
	**********************/	
	public function getMailBox($p){
		if($p['type']){
			$url = $this->host.'api/private/recv';
		}else{
			$url = $this->host.'api/private/send';
		}
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'pageflag' => $p['f'],
			'pagetime' => $p['t'],
			'reqnum' => $p['n']
		);
	 	return $this->oauth->gets($url,$params);		
	}	

	/******************
	*搜索
	*@k:搜索关键字
	*@n: 每页大小
	*@p: 页码
	*@type : 0 用户 1 消息 2 话题 3 标签 
	**********************/	
	public function getSearch($p){
		switch($p['type']){
			case 0:
				$url = $this->host.'api/search/user';
				break;
			case 1:
				$url = $this->host.'api/search/t';
				break;/*
			case 2:
				$url = $this->host.'api/search/ht';
				break;*/
			case 3:
				$url = $this->host.'api/search/userbytag';
				break;
			default:
				$url = $this->host.'api/search/t';
				break;
		}	
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'keyword' => $p['c'],
			'pagesize' => $p['n'],
			'Page' => $p['p']
		);
	 	return $this->oauth->gets($url,$params);		
	}			

	/******************
	*查看数据更新条数
	*@op :请求类型 0：只请求更新数，不清除更新数，1：请求更新数，并对更新数清零
	*@type：5 首页未读消息记数，6 @页消息记数 7 私信页消息计数 8 新增粉丝数 9 首页广播数（原创的）
	**********************/	
	public function getUpdate($p){
		$url = $this->host.'api/info/update';
		if(isset($p['type'])){
			if($p['op']){
				$params = array(
					'format' => MB_RETURN_FORMAT,
					'op' => $p['op'],
					'type' => $p['type']
				);			
			}else{
				$params = array(
					'format' => MB_RETURN_FORMAT,
					'op' => $p['op']
				);			
			}
		}else{
			$params = array(
				'format' => MB_RETURN_FORMAT,
				'op' => $p['op']
			);
		}
	 	return $this->oauth->gets($url,$params);		
	}	

	/******************
	*添加/删除 收藏的微博
	*@id : 微博id
	*@type：1 添加 0 删除
	**********************/	
	public function postFavMsg($p){
		if($p['type']){
			$url = $this->host.'api/fav/addt';
		}else{
			$url = $this->host.'api/fav/delt';
		}
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'id' => $p['id']
		);
	 	return $this->oauth->posts($url,$params);		
	}

	/******************
	*添加/删除 收藏的话题
	*@id : 微博id
	*@type：1 添加 0 删除
	**********************/	
	public function postFavTopic($p){
		if($p['type']){
			$url = $this->host.'api/fav/addht';
		}else{
			$url = $this->host.'api/fav/delht';
		}
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'id' => $p['id']
		);
	 	return $this->oauth->posts($url,$params);		
	}	

	/******************
	*获取收藏的内容
	*******话题
	n:请求数，最多15
	f:翻页标识  0：首页   1：向下翻页 2：向上翻页
	t:翻页时间戳0
	lid:翻页话题ID，第次请求时为0
	*******消息
	f 分页标识（0：第一页，1：向下翻页，2向上翻页）
	t: 本页起始时间（第一页 0，继续：根据返回记录时间决定）
	n: 每次请求记录的条数（1-20条）
	*@type 0 收藏的消息  1 收藏的话题
	**********************/	
	public function getFav($p){
		if($p['type']){
			$url = $this->host.'api/fav/list_ht';
			$params = array(
				'format' => MB_RETURN_FORMAT,
				'reqnum' => $p['n'],		
				'pageflag' => $p['f'],		
				'pagetime' => $p['t'],		
				'lastid' => $p['lid']		
				);
		}else{
			$url = $this->host.'api/fav/list_t';	
			$params = array(
				'format' => MB_RETURN_FORMAT,
				'reqnum' => $p['n'],		
				'pageflag' => $p['f'],		
				'pagetime' => $p['t']		
				);
		}
	 	return $this->oauth->gets($url,$params);		
	}

	/******************
	*获取话题id
	*@list: 话题名字列表（abc,efg,）
	**********************/	
	public function getTopicId($p){
			$url = $this->host.'api/ht/ids';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'httexts' => $p['list']
		);
	 	return $this->oauth->gets($url,$params);		
	}	

	/******************
	*获取话题内容
	*@list: 话题id列表（abc,efg,）
	**********************/	
	public function getTopicList($p){
			$url = $this->host.'api/ht/info';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'ids' => $p['list']
		);
	 	return $this->oauth->gets($url,$params);		
	}	

	/******************
	*添加标签
	*@t: 标签内容
	**********************/	
	public function addTag($p){
		$url = $this->host.'api/tag/add';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'tag' => $p['t']
		);
	 	return $this->oauth->posts($url,$params);		
	}	

	/******************
	*删除标签
	*@i: 标签ID
	**********************/	
	public function delTag($p){
		$url = $this->host.'api/tag/del';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'tagid' => $p['i']
		);
	 	return $this->oauth->posts($url,$params);		
	}	
	

	/******************
	*话题热榜
	*@t  请求类型 1 话题名，2 搜索关键字 3 两种类型都有
	*@num Reqnum: 请求个数（最多20）
	*@p Pos :请求位置，第一次请求时填0，继续填上次返回的POS
	**********************/	
	public function htHot($p){
		$url = $this->host.'api/trends/ht';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'type' => $p['t'],
			'reqnum' => $p['num'],
			'pos' => $p['p'],
		);
	 	return $this->oauth->posts($url,$params);		
	}			
	
	/******************
	*转播热榜
	*@num Reqnum: 请求个数（最多20）
	*@p Pos :请求位置，第一次请求时填0，继续填上次返回的POS
	**********************/	
	public function rHot($p){
		$url = $this->host.'api/trends/t';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'reqnum' => $p['num'],
			'pos' => $p['p'],
		);
	 	return $this->oauth->posts($url,$params);		
	}

	/******************
	*我可能认识的人
	*@num Reqnum: 请求个数（最多20）
	*@p Pos :请求位置，第一次请求时填0，继续填上次返回的POS
	**********************/	
	public function kownPerson(){
		$url = $this->host.'api/other/kownperson';
		$params = array(
			'format' => MB_RETURN_FORMAT
		);
	 	return $this->oauth->posts($url,$params);		
	}	
	
	/******************
	*数组格式化输出函数
	*******************/	
	function printArr($var){
		echo '<pre>';
		print_r($var);
	}
}
