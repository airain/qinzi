<?php
/**
 * Sns Gateway module
 *
 * @author hesen2006cn@126.com
 * create_time : 2011-06-23
 */

class SnsGateway{
	//app key
	private $_akeys = null;
	//app secret
	private $_skeys = null;
	// adapter obj
	private $_sns = null;

	private $_sns_param_suffix = '_sns_param';
	
	private $_allow_attention = array('sina','qq','space');

	private $_allow_ten_attention = array('qq','space');

	private $_log_file = null;

	private $_debug = false;
	//

	public function __construct($akeys=null,$skeys=null){
		$this->_akeys = $akeys;
		$this->_skeys = $skeys;
		$this->_log_file = dirname(__FILE__).'/../../logs/api_http_auth.log';
	}

	// probe login to website
	// $gwks is sns adapter keys array
	// $querystr is param
	// return $gwks=>url;
	public function probeLogin($gwks,$callback_url, $querystr = array()){
		if(!is_array($gwks)) return null;
		if(empty($callback_url))return null;
		
		$callback_url = trim($callback_url);
		
		//$querystr = empty($querystr)? '' : '&'.http_build_query($querystr);

		if(substr($callback_url,-1,1) != '/')
			$callback_url .= '/';
		$q_s = '';
		foreach($querystr as $k => $v){
			$q_s .= '&'.$k.'='.$v;
		}
		$res = array();
		foreach($gwks as $value){
			$value = strtolower(trim($value));
			$sns = $this->initAdapter($value);
			$_SESSION[$value.$this->_sns_param_suffix] = $querystr;
			if(!$sns)
				$res[$value]='';
			else
				$res[$value] = $sns->probeLogin($callback_url."?gwk=".$value.$q_s);
		}
		return $res;
	}

	//login callback by gwk
	//success return array ,failed return null;
	public function probeCallBack($gwk){
		$this->_sns = $this->initAdapter($gwk);
		$sns_p = $gwk.$this->_sns_param_suffix;
		if(isset($_SESSION[$sns_p]) && is_array($_SESSION[$sns_p])){
			foreach($_SESSION[$sns_p] as $key => $val){
				$_GET[$key] = $val;
			}
			unset($_SESSION[$sns_p]);
		}
		if(!$this->_sns) return null;

		if(!$this->_sns->probeCallBack()) return null;
	
		$res = $this->_sns->getMyInfor();
		return $res;
	}
	
	//腾讯微博更新token qq
	public function refreshToken($gwk){
		$this->_sns = $this->initAdapter($gwk);
		
		return $this->_sns->refreshToken();
	}
	
	//get content adapter key
	public function getSnsKey(){
		return $this->_sns->getAdapterKey();
	}

    // issue weibo multicast website	
	public function issueMention($src,$gwks){
		if(!is_array($gwks)) return null;

		$res = array();
		foreach($gwks as $value){
			$value = strtolower(trim($value));
			$sns = $this->initAdapter($value);
			if(!$sns)
				$res[$value]='';
			else
				$res[$value] = $sns->issueMention($src);
		}
		return $res;
	}

	//转发
	// @param @data 数据
	// @param $gwks 类型 多个可数组或以‘,’分割的字符串
	public function forwardMention($data,$gwks){
		if(!is_array($gwks) && $gwks) $gwks = explode(',', $gwks);

		$res = array();
		foreach($gwks as $value){
			$value = strtolower(trim($value));
			$sns = $this->initAdapter($value);
			if(!$sns)
				$res[$value]='';
			else{
				$res[$value] = $sns->forwardMention($data['sid'],$data['content'],$data['ip']);
			}
		}
		return $res;
	}
	//分享到qq空间
	public function shareSpace($data, $type,$session=null){
		$sns = $this->initAdapter($type);
		if(!$sns) return false;
		$images = isset($data['upimg'])?$data['upimg']:'';
		$data = $this->do_content($data, $type);
		$data = $this->get_small_content($data, '', 80);
		if(empty($data['title'])){
			$length = 54;
			$title =  csub_str($data['content']) > $length? mb_substr($data['content'], 0, $length, 'UTF-8').'...':$data['content'];
			$summary = '';
		}else{
			$title = $data['title'];
			$summary = $data['content'];
		}
		$url = $data['url'];
		$site = isset($data['from'])?$data['from']:'';;
		$comment = isset($data['comment'])&&$data['comment']?substr($data['comment'],0,-2):'';
		
        $sns->_session = $session;
		$res = $sns->shareSpace($title, $summary, $url,$images,$site, $comment,$source=5,$type=4,$playurl='',$nswb=1);
		return array("result"=>$res,'message'=>$sns->getErrorMessage());
	}

	public function getSnsInstanceOf($gwk){
		$sns = $this->initAdapter($gwk);
		return $sns;
	}

	public function addAttention($gwk='', $uid=''){		
		if(!in_array($gwk, $this->_allow_attention)) return false;
		$this->_sns = $this->initAdapter($gwk);
		$org_id = empty($uid)?$this->_sns->getOrgId():$uid;
		if(in_array($gwk,$this->_allow_ten_attention))
			$this->_sns->addAttention($org_id, true);
		else
			$this->_sns->addAttention($org_id);
	}

	private function initAdapter($gwk){
		if(empty($gwk)) return null;
		
		if(!isset($this->_akeys[$gwk]))
			$this->_akeys[$gwk]=null;
		if(!isset($this->_skeys[$gwk]))
			$this->_skeys[$gwk]=null;

		$sns = null;
		switch($gwk){
			case 'sina':
				require_once('SnsSinaAdapter.php');
				$sns = new SnsSinaAdapter($this->_akeys[$gwk],$this->_skeys[$gwk],$this->_debug);
				break;
			case 'qq':
				require_once('SnsQQ2.0Adapter.php');
				$sns = new SnsQQTenAdapter($this->_akeys[$gwk],$this->_skeys[$gwk],$this->_debug);
				break;
			case 'space':
				require_once('SnsQQSpaceAdapter.php');
				$sns = new SnsQQSpaceAdapter($this->_akeys[$gwk],$this->_skeys[$gwk],$this->_debug);
				break;
			default:
				break;
		}
		$sns->setLogFile($this->_log_file);
		if($this->_debug) $this->save_log("------------- get sns object【 $gwk 】ok --------------\r\n");
		return $sns;
	}
	
	/*
	 * @param string $content
	 * @param mixed $shares eg: 'sina' or 'sina,qq' or array
	*/
	public function synchro_info($content, $shares,$session=null)
	{
		if($this->_debug){
			$this->save_log("\r\n\r\n------------- ".date('Y-m-d H:i:s')." shares type: $shares --------------\r\n");
			$shares_starttime = explode(' ',microtime());
		}
		if(is_array($shares))
			$synch_shares = $shares;
		else
			$synch_shares = explode(',', $shares);
		$rec = null;
		//是否同时存在qq space
		$is_qq_space = (in_array('space',$synch_shares) && in_array('qq',$synch_shares))? 1 : 0;
		foreach($synch_shares as $val){ 
			if(!in_array($val, Doo::conf()->UC_SHARE_TYPES)){
				continue;
			}
			if($this->_debug) $this->save_log("------------- option sns object: $val --------------\r\n");
			if($val == 'space'){
				$rec[$val] =  $this->shareSpace($content, $val, $session);
			}else{
				$rec[$val] =  $this->do_synchro_info($content, $val, $is_qq_space);
			}
		}
		if($this->_debug){
			$shares_endtime = explode(' ',microtime());
			$tmp_dif = (($shares_endtime[1] - $shares_starttime[1] + $shares_endtime[0] - $shares_starttime[0])*1000);
			$this->save_log("\r\n------------- shares time spend: $tmp_dif ms --------------\r\n");
		}
		return $rec;
	}
	
	/**
	 * save log 
	*/
	function save_log($msg){		
		error_log($msg,3,$this->_log_file);
	}

	private function do_content($content, $type){		
		global $config;
		//$res = processEmotionImageToChar($content,$type);
		$content['content'] = stripslashes($content['content']);
		if($type == 'space'){
			$content['content'] = processEmtSinaToQQ($content['content']);
			return $content;
		}else{
			$res['content'] = $content['content'];
			$res['upimg'] = $content['upimg'];
			$res['url'] = $content['url'];
			if(in_array($type, $this->_allow_ten_attention)){
				$res['content'] = processEmtSinaToQQ($res['content']);
			}
			return $res;
		}
	}

	private function do_synchro_info($content,$type, $is_qq_space=0)
	{
		$content = $this->do_content($content, $type);
		switch($type){
			case 'sina': $res = $this->do_synchro_sina($content); break;
			case 'qq':  $res = $this->do_synchro_qq($content); break;
			case 'space':  $res = $this->do_synchro_qq($content); break;
			default: return ;
		}
		
		$this->probeLogin($type,'/');
		$sns = $this->getSnsInstanceOf($type);
		if(!isset($res['upimg']) || empty($res['upimg'])){
			if($type == 'qq' && $is_qq_space)
				$rec = $sns->issueMention($res['content'],'',0,0,1);
			else
				$rec = $sns->issueMention($res['content']);
		}else{
			if($type == 'qq' && $is_qq_space)
				$rec = $sns->issueImageMention($res['content'], $res['upimg'],'',0,0,1);
			else
				$rec = $sns->issueImageMention($res['content'], $res['upimg']);
		}	
		return array('result'=>$rec,'message'=>$sns->getErrorMessage());
	}

	private function do_synchro_qq($des)
	{
		$rec = array();
		// 可接受长度为140; 一个url 为11个字
		$rec = $this->get_small_content($des, $des['url'], 120);
		return $rec;
	}

	private function do_synchro_sina($des)
	{
		$rec = array();
		// 可接受长度为140; 一个url 为 10 个字
		$rec = $this->get_small_content($des, $des['url'], 120);	
		return $rec;
	}

	private function get_small_content($des, $forward='', $length=140)
	{
		$str = $des['content'];
		if(mb_strlen($str) > $length){
			$des['content'] = mb_substr($str, 0, $length, 'UTF-8').'...'.$forward;
		}else{
			$des['content'] = $str.' '.$forward;
		}
		return $des;
	}
	
	/**
	 * 获取在sns中关注的人
	 * @param type $sns_site	sns站点
	 * @param type $sns_id		sns id
	 * @param type $cursor		起始位置
	 * @param type $count		返回数量
	 * @return return array("result"=>0,"error_code"=>$this->getErrorCode(),"error_message"=>$this->getErrorMessage());
	 *			array("result"=>1,'data'=>$data,'next_cursor'=>$res['next_cursor'],'total_number'=>$res['total_number']) 
	 */
	public function getAttentions($sns_site,$sns_id='', $cursor = 0, $count = 200){
		$sns = $this->initAdapter($sns_site);
		return $sns->getAttentions($sns_id, $cursor, $count);
	}
}
