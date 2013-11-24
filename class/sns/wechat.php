<?php
/**
* wechat callback php test
*/

//define your token
define("TOKEN", "mp_topka20110919");

class Wechat
{
	private $m_postObj = null;
	private $m_msgtype = 'text';
	private $m_funcflag = 0;
	private $m_content = '';
	private $m_data = array();

	private $m_result = '';

	private $m_debug = false;

	private $m_logfile = 'api_mp';

	public function __construct(){
		if($this->m_debug){
			$error_tpl = "
			-------------- POST ------------------
			%s
			-------------- GET ------------------
			%s
			-------------- HTTP_RAW_POST_DATA ------------------
			%s
			";
			$error_msg = sprintf($error_tpl, print_r($_POST,true), print_r($_GET,true), $GLOBALS["HTTP_RAW_POST_DATA"]);
			$this->logs($error_msg);
		}
	}

	public function __destruct(){
		if($this->m_debug){
			$error_tpl = "
			-------------- result ------------------
			%s
			==========================================
			";
			$error_msg = sprintf($error_tpl, $this->m_result);
			$this->logs($error_msg);
		}
	}

	private function logs($error_msg){
		log_trace($error_msg,0, $this->m_logfile);
	}

	public function valid()
    {
        $echoStr = isset($_GET["echostr"])?$_GET["echostr"]:'';

        //valid signature , option
        if($this->checkSignature()){
        	return $echoStr;
        }
    }

	protected function getRequestMsg(){
		if($this->m_postObj == null){
			//get post data, May be due to the different environments
			$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
			$this->m_postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		}
	}
	
	/**
	* fun: getMsg
	* des: 获取请求所有数据
	*/
	public function getMsg(){
		$this->getRequestMsg();
		return $this->m_postObj;
	}//getMsg
	
	/**
	* fun: getKeyword
	* des: 获取请求文本字符串
	* 
	* @return string A
	*/
	public function getKeyword(){
		$this->getRequestMsg();
		$content = isset($this->m_postObj->Content)?$this->m_postObj->Content:'速腾';
		return trim($content);
	}

	/**
	* @param int $funcFlag 0 是否加星标 1对消息进行星标 0不加
	*/
	public function setFlag($funcFlag=0){
		$this->m_funcflag = intval($funcFlag);
	}//setFlag

	/**
	* @param string $msgType text 消息类型 text(文本),news(新闻即图文并存)
	*/
	public function setType($msgType='text'){
		$this->m_msgtype = $msgType;
	}//setType
	
	/**
	* @param string $contentStr 内容
	*/
	public function setContent($content=''){
		$this->m_content = $content;
	}//setContent

	/**
	* @param array $data array() arrar = [title, desc, picurl, link] $data为二维数组
	*/
	public function setData(array $data = array()){
		$this->m_data = $data;
	}//setData

	/**
	* fun: getResponseMsg
	*
	* @return string A
	*/
	public function getResponseMsg(){
		if(empty($this->m_content) && empty($this->m_data)) return $this->logs('data empty!');
		if(!$this->checkSignature()) return $this->logs('checked signature error!');
		$resultStr = "<xml>\n";
		if($this->m_msgtype == 'text'){
			$resultStr .= $this->getBaseInfo($this->m_content, $this->m_msgtype, $this->m_funcflag);
		}elseif($this->m_msgtype == 'news'){
			$resultStr .= $this->getImages($this->m_data, $this->m_content, $this->m_funcflag);
		}
		$resultStr .= "</xml>\n";
		return $this->m_result = $resultStr;
	}//getResponseMsg
	
	/**
	* fun:getImages
	* des:图文并存
	*
	* @param array $data array() arrar = [title, desc, picurl, link] $data为二维数组
	* @param string $contentStr 内容
	* @param int $funcFlag 0 是否加星标 1对消息进行星标 0不加
	* @return string A
	*/
	private function getImages(array $data=array(), $contentStr='' ,$funcFlag=0){
		$resultStr = '';
		$resultStr .= $this->getBaseInfo($contentStr, 'news', 1);
		$count = count($data);
		$resultStr .= '<ArticleCount>'.$count."</ArticleCount>\n";
		$resultStr .= "<Articles>\n";
		if($count > 0)
			foreach($data as $val){
				if(empty($val)) continue;
				$resultStr .= "<item>\n";
				$resultStr .= $this->getImage($val);
				$resultStr .= "</item>\n";
			}//foreach
		$resultStr .= "</Articles>\n";
		return $resultStr;
	}//getImages
	
	/**
	* fun:getImage
	* des:图文并存
	*
	* @param array $data array() [title, desc, picurl, link] 
	* @return string A;
	*/
	private function getImage(array $data = array()){
		$textTpl = "	
					<Title><![CDATA[%s]]></Title>
					<Discription><![CDATA[%s]]></Discription>
					<PicUrl><![CDATA[%s]]></PicUrl>
					<Url><![CDATA[%s]]></Url>
					";
		$title = isset($data['title'])?$data['title']:'';
		$desc = isset($data['desc'])?$data['desc']:'';
		$picurl = isset($data['picurl'])?$data['picurl']:'';
		$link = isset($data['link'])?$data['link']:'';
		$resultStr = sprintf($textTpl, $title, $desc, $picurl, $link);
		return $resultStr;
	}
	
	/**
	* fun: getBaseInfo
	* des: 只有文本内容
	* 
	* @param string $contentStr 信息内容
	* @param string $msgType text 消息类型 text(文本),news(新闻即图文并存)
	* @param int $funcFlag 0 是否加星标 1对消息进行星标 0不加
	*/
	private function getBaseInfo($contentStr='', $msgType='text', $funcFlag=0){
		$this->getRequestMsg();
		if(empty($this->m_postObj)) return '';
		$fromUsername = $this->m_postObj->FromUserName;
		$toUsername = $this->m_postObj->ToUserName;
		$msgType = empty($msgType)?$this->m_msgtype : $msgType;
		$time = time();
		$textTpl = "	
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>%d</FuncFlag>
					";
		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr, $funcFlag);
		return $resultStr;
	}//tplText
		
	private function checkSignature()
	{return true;
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}
